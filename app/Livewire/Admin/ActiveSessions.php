<?php

namespace App\Livewire\Admin;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ActiveSessions extends Component
{
    public function render()
    {
        // Get active sessions (last activity within last 30 minutes)
        $activeSessions = DB::table('sessions')
            ->join('users', 'sessions.user_id', '=', 'users.id')
            ->join('employees', 'users.employee_number', '=', 'employees.employee_number')
            ->whereNotNull('sessions.user_id')
            ->where('sessions.last_activity', '>', now()->subMinutes(30))
            ->select('sessions.id', DB::raw("CONCAT(employees.first_name, ' ', employees.last_name) as name"), 'employees.email', 'sessions.ip_address', 'sessions.user_agent', 'sessions.last_activity')
            ->get();

        return view('livewire.admin.active-sessions', [
            'activeSessions' => $activeSessions,
        ])->layout('components.layouts.app');
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }

    public function endSession($sessionId)
    {
        // Prevent ending own session
        $currentSessionId = session()->getId();
        if ($sessionId === $currentSessionId) {
            session()->flash('flash.error', 'You cannot end your own session.');
            return;
        }

        // Retrieve the session to get user_id
        $session = DB::table('sessions')->where('id', $sessionId)->first();
        if ($session) {
            // Create ActivityLog entry for logout
            ActivityLog::create([
                'user_id' => $session->user_id,
                'action' => 'logout',
                'description' => 'Session ended by admin',
                'ip_address' => request()->ip(),
            ]);
        }

        DB::table('sessions')->where('id', $sessionId)->delete();
        $this->addFlash('success', 'Session ended successfully!');
    }

    protected function addFlash($type, $message)
    {
        session()->flash('flash.'.$type, $message);
    }
}