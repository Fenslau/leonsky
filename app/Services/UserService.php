<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Http;

class UserService
{
    public function sendUserData(User $user)
    {
        // $response = Http::post('https://external-service.com/api/users', [
        //     'name' => $user->name,
        //     'email' => $user->email,
        //     'password' => $user->password,
        // ]);

        // if ($response->successful()) {
        //     logger()->info('Пользователь успешно отправлен!');
        // } else {
        //     logger()->error('Ошибка отправки пользователя: ' . $response->body());
        // }
    }
}
