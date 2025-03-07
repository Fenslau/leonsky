<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;

class ServerStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:server-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $serverSatatus = \App\Models\ServerStatus::first();
        $host = 'ls1.leone-sky.ru';
        $port = 2106;
        $timeout = 1;

        try {
            $socket = fsockopen($host, $port, $errno, $errstr, $timeout);
            $serverSatatus->update(['login' => true]);
            fclose($socket);
        } catch (Exception $e) {
            $serverSatatus->update(['login' => false]);
        }

        $host = 'gs1.leone-sky.ru';
        $port = 7777;
        try {
            $socket = fsockopen($host, $port, $errno, $errstr, $timeout);
            $serverSatatus->update(['game' => true]);
            fclose($socket);
        } catch (Exception $e) {
            $serverSatatus->update(['game' => false]);
        }
    }
}
