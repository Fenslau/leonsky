<?php

namespace App\Console\Commands;

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
        $socket = fsockopen($host, $port, $errno, $errstr, $timeout);
        if (!$socket) {
            $serverSatatus->update(['login' => false]);
        } else {
            $serverSatatus->update(['login' => true]);
            fclose($socket);
        }
        $host = 'gs1.leone-sky.ru';
        $port = 7777;
        $socket = fsockopen($host, $port, $errno, $errstr, $timeout);
        if (!$socket) {
            $serverSatatus->update(['game' => false]);
        } else {
            $serverSatatus->update(['game' => true]);
            fclose($socket);
        }
    }
}
