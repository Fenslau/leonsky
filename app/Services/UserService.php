<?php

namespace App\Services;

use App\Exceptions\ExternalServiceException;
use App\Models\User;
use Illuminate\Support\Facades\Http;

class UserService
{
    public function sendUserData(User $user, null|string $oldPassword = null): void
    {
        if (env('EXTERNAL_SERVICE_SYNC')) {
            $data = [
                'token' => '0xb1be70e9a83f19192cb593935ec4e2e2',
                'req' => 'registeraccount2',
                'login' => $user->name,
                'email' => $user->email,
                'passw' => $user->password,
            ];
            if (!is_null($oldPassword)) {
                $data['oldpass'] = $oldPassword;
            }
            $response = Http::post('http://203.31.40.5:9246', $data);

            $responseData = $response->json();

            if (isset($responseData['resp'])) {
                if ($responseData['resp'] === 'OK') {
                    logger()->info('Обновлён пользователь: ' . $user->email);
                } elseif ($responseData['resp'] === 'ERR') {
                    logger()->warning('Ошибка: ' . $responseData['reason']);
                    throw new ExternalServiceException('Ошибка: ' . $responseData['reason']);
                }
            } else {
                logger()->error('Ошибка отправки пользователя: ' . $response->body());
                throw new ExternalServiceException('Ошибка отправки пользователя: ' . $response->body());
            }
        }
    }
}
