<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\WfhTimelog;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class WfhTimelogExportController extends Controller
{
    public function exportPdf(Request $request)
    {
        // Check authorization for all users export
        $isSingleUser = $request->has('user_id') && $request->user_id;
        if (! $isSingleUser && ! auth()->user()->hasRole('administrator')) {
            abort(403, 'Unauthorized access');
        }

        $query = WfhTimelog::with(['user', 'user.employee', 'user.employee.position', 'user.employee.office', 'user.employee.unit']);

        // Apply search filter
        if ($request->has('search') && $request->search) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                    ->orWhere('employee_id', 'like', '%'.$request->search.'%');
            });
        }

        // Apply user filter
        if ($request->has('user_id') && $request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        // Apply status filter
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Apply date range filter
        if ($request->has('date_from') && $request->has('date_to') && $request->date_from && $request->date_to) {
            $query->whereBetween('date', [$request->date_from, $request->date_to]);
        } elseif ($request->has('date_from') && $request->date_from) {
            $query->where('date', '>=', $request->date_from);
        } elseif ($request->has('date_to') && $request->date_to) {
            $query->where('date', '<=', $request->date_to);
        }

        $timelogs = $query->orderBy('date', 'asc')
            ->orderBy('time_in', 'asc')
            ->get();

        $dateRange = 'All';
        if ($request->date_from && $request->date_to) {
            $dateRange = Carbon::parse($request->date_from)->format('M d, Y').' - '.Carbon::parse($request->date_to)->format('M d, Y');
        } elseif ($request->date_from) {
            $dateRange = 'From: '.Carbon::parse($request->date_from)->format('M d, Y');
        } elseif ($request->date_to) {
            $dateRange = 'Until: '.Carbon::parse($request->date_to)->format('M d, Y');
        }

        // Check if exporting for a specific user (single user timelog)
        // or for all users (all timelogs)
        $isSingleUser = $request->has('user_id') && $request->user_id;

        // Check authorization for all users export
        if (! $isSingleUser && ! auth()->user()->hasRole('administrator')) {
            abort(403, 'Unauthorized access');
        }

        $employeeName = null;
        $employeePosition = null;
        $employeeOffice = null;
        $employeeUnit = null;
        if ($isSingleUser) {
            $user = User::with('employee.position', 'employee.office', 'employee.unit')->find($request->user_id);
            $employeeName = $user ? $user->name : null;
            $employeePosition = $user && $user->employee && $user->employee->position ? $user->employee->position->name : null;
            $employeeOffice = $user && $user->employee && $user->employee->office ? $user->employee->office->name : null;
            $employeeUnit = $user && $user->employee && $user->employee->unit ? $user->employee->unit->name : null;
        }

        // Determine which view to use based on whether it's single user or all
        if ($isSingleUser) {
            // Single user export - use wfh-timelogs-pdf (no employee column, name in header)
            $pdf = Pdf::loadView('livewire.admin.wfh-timelogs-pdf', [
                'timelogs' => $timelogs,
                'dateRange' => $dateRange,
                'filterStatus' => $request->status,
                'employeeName' => $employeeName,
                'employeePosition' => $employeePosition,
                'employeeOffice' => $employeeOffice,
                'employeeUnit' => $employeeUnit,
            ]);
            $filename = 'wfh-timelogs-'.now()->format('Y-m-d').'.pdf';
        } else {
            // All users export - use wfh-all-timelogs-pdf (grouped by date)
            $pdf = Pdf::loadView('livewire.admin.wfh-all-timelogs-pdf', [
                'timelogs' => $timelogs,
                'dateRange' => $dateRange,
                'filterStatus' => $request->status,
            ]);
            $filename = 'wfh-all-timelogs-'.now()->format('Y-m-d').'.pdf';
        }

        return $pdf->stream($filename);
    }
}
