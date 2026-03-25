<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use App\Jobs\SendWelcomeEmailToUser;
use App\Models\User;

class SendWelcomeEmails implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $employeeNumbers;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        // 🔒 Force only these employee numbers (temporary restriction)
        // $this->employeeNumbers = [2416, 2000];
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        User::where('status', 1)
            ->with('employee')
            ->each(function ($user) {
                SendWelcomeEmailToUser::dispatch($user);
            });

        Log::info("Dispatched welcome email jobs for active users.");
    }
}