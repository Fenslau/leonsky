<?php

namespace App\Jobs;

use App\Services\UserService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendUserDataJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected $user
    ) {}

    /**
     * Execute the job.
     */
    public function handle(UserService $userService): void
    {
        $userService->sendUserData($this->user);
    }
}
