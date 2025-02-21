<?php

use App\Exceptions\ExternalServiceException;
use Filament\Notifications\Notification;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Client\ConnectionException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (ExternalServiceException $e) {
            Notification::make()
                ->body($e->getMessage())
                ->danger()
                ->send();

            return back()->with([
                'error' => $e->getMessage(),
            ]);
        });
        $exceptions->render(function (ConnectionException $e) {
            Notification::make()
                ->title('Ошибка')
                ->body('Внешний сервис недоступен')
                ->danger()
                ->send();

            return back()->with([
                'error' => 'Внешний сервис недоступен',
            ]);
        });
    })->create();
