<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\WelcomeEmail;
use App\Models\User;

class SendWelcomeEmails implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $count = 0;
        User::whereIn('employee_number', [2416, 2000])
            ->where('status', 1)
            ->each(function ($user) use (&$count) {
                // Calculate default password: first initial + last name + employee_number
                $firstInitial = strtoupper(substr($user->employee->first_name, 0, 1));
                $lastName =  strtolower($user->employee->last_name);
                $defaultPassword = $firstInitial . $lastName . $user->employee_number;

                // Update user with hashed default password
                $user->update([
                    'password' => Hash::make($defaultPassword),
                    'must_change_password' => false, // Default password, may not require change immediately
                    'temp_password' => null,
                    'temp_password_expires_at' => null,
                ]);

                Mail::to($user->email)->send(new WelcomeEmail($user, $defaultPassword));
                $count++;
            });
        Log::info("Sent welcome emails to {$count} active users.");
    }
}
