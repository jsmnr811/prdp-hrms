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
use App\Mail\WelcomeEmail;
use App\Models\User;

class SendWelcomeEmailToUser implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user;

    /**
     * Create a new job instance.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $user = $this->user;

        // Skip if employee relation is missing
        if (!$user->employee) {
            Log::warning("User {$user->email} has no employee record. Skipped.");
            return;
        }

        // Generate default password
        $firstInitial = strtoupper(substr($user->employee->first_name, 0, 1));
        $lastName = preg_replace('/\s+/', '', strtolower($user->employee->last_name));
        $defaultPassword = $firstInitial . $lastName . $user->employee_number;

        // Update user password
        $user->update([
            'password' => Hash::make($defaultPassword),
            'must_change_password' => false,
            'temp_password' => null,
            'temp_password_expires_at' => null,
        ]);

        // Send email
        Mail::to($user->email)->send(
            new WelcomeEmail($user, $defaultPassword)
        );

        Log::info("Sent welcome email to user: {$user->email}");
    }
}
