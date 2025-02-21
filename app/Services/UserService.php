<?php

namespace App\Services;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Http;

class UserService
{
    public function sendUserData(User $user)
    {
        try {
            $response = Http::post('http://203.31.40.5:9246', [
                'token' => '0xb1be70e9a83f19192cb593935ec4e2e2',
                'req' => 'registeraccount2',
                'login' => $user->name,
                'email' => $user->email,
                'passw' => $user->password,
            ]);

            if ($response->successful()) {
                logger()->info('Пользователь успешно отправлен: ' . $response->body());
            } else {
                logger()->error('Ошибка отправки пользователя: ' . $response->body());
            }
        } catch (Exception $e) {
            logger()->error('Ошибка отправки пользователя: ' . $e->getMessage());
        }
    }
}
