<?php

namespace App\Http\Controllers;

use App\Models\WfhTimelog;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class WfhTimelogExportController extends Controller
{
    public function exportPdf(Request $request)
    {
        $query = WfhTimelog::with('user');

        // Apply search filter
        if ($request->has('search') && $request->search) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('employee_id', 'like', '%' . $request->search . '%');
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

        $timelogs = $query->orderBy('date', 'desc')
            ->orderBy('time_in', 'desc')
            ->get();

        $dateRange = 'All';
        if ($request->date_from && $request->date_to) {
            $dateRange = \Carbon\Carbon::parse($request->date_from)->format('M d, Y') . ' - ' . \Carbon\Carbon::parse($request->date_to)->format('M d, Y');
        } elseif ($request->date_from) {
            $dateRange = 'From: ' . \Carbon\Carbon::parse($request->date_from)->format('M d, Y');
        } elseif ($request->date_to) {
            $dateRange = 'Until: ' . \Carbon\Carbon::parse($request->date_to)->format('M d, Y');
        }

        $pdf = Pdf::loadView('livewire.admin.wfh-timelogs-pdf', [
            'timelogs' => $timelogs,
            'dateRange' => $dateRange,
            'filterStatus' => $request->status,
        ]);

        return $pdf->download('wfh-timelogs-' . now()->format('Y-m-d') . '.pdf');
    }
}
