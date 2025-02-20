<?php

namespace App\Hashing;

use Illuminate\Contracts\Hashing\Hasher as HasherContract;

class CustomHasher implements HasherContract
{
    public function make($value, array $options = [])
    {
        return $this->encrypt($value);
    }

    public function check($value, $hashedValue, array $options = [])
    {
        return $this->encrypt($value) === $hashedValue;
    }

    public function needsRehash($hashedValue, array $options = [])
    {
        return false;
    }

    public function info($hashedValue)
    {
        return [
            'algo' => 'la2pts',
            'options' => [],
        ];
    }

    public function verifyConfiguration($options)
    {
        return true;
    }

    private function encrypt(string $str): string
    {
        $key = array_fill(0, 16, 0);
        $dst = array_fill(0, 16, 0);

        $nBytes = strlen($str);

        for ($i = 0; $i < $nBytes; $i++) {
            $key[$i] = ord($str[$i]);
            $dst[$i] = $key[$i];
        }

        for ($i = 0; $i < 16; $i += 4) {
            $rslt = $key[$i] + ($key[$i + 1] << 8) + ($key[$i + 2] << 16) + ($key[$i + 3] << 24);
            $multiplier = [213119, 213247, 213203, 213821][$i / 4];
            $adder = [2529077, 2529089, 2529589, 2529997][$i / 4];

            $calc = ($rslt * $multiplier + $adder) % 4294967296;

            $key[$i] = $calc & 0xFF;
            $key[$i + 1] = ($calc >> 8) & 0xFF;
            $key[$i + 2] = ($calc >> 16) & 0xFF;
            $key[$i + 3] = ($calc >> 24) & 0xFF;
        }

        $dst[0] = $dst[0] ^ $key[0];
        for ($i = 1; $i < 16; $i++) {
            $dst[$i] = $dst[$i] ^ $dst[$i - 1] ^ $key[$i];
            if ($dst[$i] == 0) {
                $dst[$i] = 102;
            }
        }

        $binaryData = "";
        for ($i = 0; $i < 16; $i++) {
            $binaryData .= chr($dst[$i]);
        }

        return '0x' . bin2hex($binaryData);
    }
}
