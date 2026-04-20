<?php

namespace App\Livewire\Admin;

use App\Models\ActivityLog;
use App\Models\Office;
use App\Models\OfficeCategory;
use App\Models\Position;
use App\Models\Unit;
use App\Models\WfhTimelog;
use Flux\Flux;
use Livewire\Component;

class WfhDashboard extends Component
{
    public $totalTimelogs;

    public $pendingCount;

    public $completedCount;

    public $todayTimelogs;

    public $weekTimelogs;

    public $monthTimelogs;

    public $avgHoursToday;

    public $avgHoursWeek;

    public $avgHoursMonth;

    public $pendingByOffice;

    public $pendingByUnit;

    public $filteredTimelogs;

    // Edit modal
    public $showEditModal = false;

    public $editingTimelog;

    public $editTimeIn;

    public $editTimeOut;

    public $editAccomplishments;

    // Filters
    public $date_from;

    public $date_to;

    public $office_id;

    public $unit_id;

    public $position_id;

    public $office_category_id;

    public $status;

    public $employee_search;

    public function mount()
    {
        $this->date_from = now()->toDateString();
        $this->date_to = now()->toDateString();
        $this->status = '';
        $this->office_id = '';
        $this->unit_id = '';
        $this->position_id = '';
        $this->office_category_id = '1';
        $this->employee_search = '';
        $this->loadStats();
    }

    public function loadStats()
    {
        $user = auth()->user();

        // Base query with visibility scope and filters
        $baseQuery = WfhTimelog::query()
            ->whereHas('user.employee', function ($q) use ($user) {
                $q->visibleTo($user)
                    ->when($this->office_id, fn($query) => $query->where('office_id', $this->office_id))
                    ->when($this->unit_id, fn($query) => $query->where('unit_id', $this->unit_id))
                    ->when($this->position_id, fn($query) => $query->where('position_id', $this->position_id))
                    ->when($this->office_category_id, fn($query) => $query->where('office_category_id', $this->office_category_id))
                    ->when($this->employee_search, fn($query) => $query->where('first_name', 'like', '%' . $this->employee_search . '%')
                        ->orWhere('last_name', 'like', '%' . $this->employee_search . '%')
                        ->orWhere('employee_number', 'like', '%' . $this->employee_search . '%'));
            })
            ->when($this->date_from && $this->date_to, fn($query) => $query->whereBetween('date', [$this->date_from, $this->date_to]))
            ->when($this->status, fn($query) => $query->where('status', $this->status));

        // Counts
        $this->totalTimelogs = (clone $baseQuery)->count();

        $this->pendingCount = (clone $baseQuery)
            ->where('status', 'pending')
            ->count();

        $this->completedCount = (clone $baseQuery)
            ->where('status', 'completed')
            ->count();

        // Today's timelogs (filtered by today regardless of date filter for the table)
        $todayQuery = (clone $baseQuery)->where('date', now()->toDateString());

        $this->todayTimelogs = (clone $todayQuery)
            ->with('user.employee')
            ->orderBy('time_in', 'desc')
            ->get();

        // Filtered timelogs for the table
        $this->filteredTimelogs = (clone $baseQuery)
            ->with('user.employee.officeCategory', 'user.employee.office', 'user.employee.unit')
            ->orderBy('date', 'desc')
            ->orderBy('time_in', 'desc')
            ->get();

        // This week's timelogs
        $this->weekTimelogs = (clone $baseQuery)
            ->whereBetween('date', [
                now()->startOfWeek()->toDateString(),
                now()->endOfWeek()->toDateString(),
            ])
            ->count();

        // This month's timelogs
        $this->monthTimelogs = (clone $baseQuery)
            ->whereBetween('date', [
                now()->startOfMonth()->toDateString(),
                now()->endOfMonth()->toDateString(),
            ])
            ->count();

        // Average hours calculations
        $this->avgHoursToday = (clone $todayQuery)
            ->where('date', now()->toDateString())
            ->whereNotNull('time_in')
            ->whereNotNull('time_out')
            ->selectRaw('AVG(TIMESTAMPDIFF(MINUTE, time_in, time_out) / 60) as avg_hours')
            ->first()
            ->avg_hours ?? 0;

        $weekQuery = (clone $baseQuery)
            ->whereBetween('date', [
                now()->startOfWeek()->toDateString(),
                now()->endOfWeek()->toDateString(),
            ])
            ->whereNotNull('time_in')
            ->whereNotNull('time_out');

        $this->avgHoursWeek = $weekQuery
            ->selectRaw('AVG(TIMESTAMPDIFF(MINUTE, time_in, time_out) / 60) as avg_hours')
            ->first()
            ->avg_hours ?? 0;

        $monthQuery = (clone $baseQuery)
            ->whereBetween('date', [
                now()->startOfMonth()->toDateString(),
                now()->endOfMonth()->toDateString(),
            ])
            ->whereNotNull('time_in')
            ->whereNotNull('time_out');

        $this->avgHoursMonth = $monthQuery
            ->selectRaw('AVG(TIMESTAMPDIFF(MINUTE, time_in, time_out) / 60) as avg_hours')
            ->first()
            ->avg_hours ?? 0;

        // Filtered query for reports (apply all filters except status)
        $reportsBaseQuery = WfhTimelog::query()
            ->whereHas('user.employee', function ($q) use ($user) {
                $q->visibleTo($user)
                    ->when($this->office_id, fn($query) => $query->where('office_id', $this->office_id))
                    ->when($this->unit_id, fn($query) => $query->where('unit_id', $this->unit_id))
                    ->when($this->position_id, fn($query) => $query->where('position_id', $this->position_id))
                    ->when($this->office_category_id, fn($query) => $query->where('office_category_id', $this->office_category_id))
                    ->when($this->employee_search, fn($query) => $query->where('first_name', 'like', '%' . $this->employee_search . '%')
                        ->orWhere('last_name', 'like', '%' . $this->employee_search . '%')
                        ->orWhere('employee_number', 'like', '%' . $this->employee_search . '%'));
            })
            ->when($this->date_from && $this->date_to, fn($query) => $query->whereBetween('date', [$this->date_from, $this->date_to]));

        // Timelogs by office per day
        $officeCollection = (clone $reportsBaseQuery)
            ->join('users', 'wfh_timelogs.user_id', '=', 'users.id')
            ->join('employees', 'users.employee_number', '=', 'employees.employee_number')
            ->whereNull('employees.unit_id')
            ->join('offices', 'employees.office_id', '=', 'offices.id')
            ->selectRaw('date, offices.name as office_name,
                SUM(CASE WHEN LOWER(wfh_timelogs.status) = "pending" THEN 1 ELSE 0 END) as pending_count,
                SUM(CASE WHEN LOWER(wfh_timelogs.status) = "completed" THEN 1 ELSE 0 END) as completed_count')
            ->groupBy('date', 'offices.name')
            ->orderBy('date', 'desc')
            ->orderBy('offices.name')
            ->get();

        $this->pendingByOffice = $officeCollection->groupBy('date')->toArray();

        // Timelogs by unit per day
        $unitCollection = (clone $reportsBaseQuery)
            ->join('users', 'wfh_timelogs.user_id', '=', 'users.id')
            ->join('employees', 'users.employee_number', '=', 'employees.employee_number')
            ->join('units', 'employees.unit_id', '=', 'units.id')
            ->selectRaw('date, units.name as unit_name,
                SUM(CASE WHEN LOWER(wfh_timelogs.status) = "pending" THEN 1 ELSE 0 END) as pending_count,
                SUM(CASE WHEN LOWER(wfh_timelogs.status) = "completed" THEN 1 ELSE 0 END) as completed_count')
            ->groupBy('date', 'units.name')
            ->orderBy('date', 'desc')
            ->orderBy('units.name')
            ->get();

        $this->pendingByUnit = $unitCollection->groupBy('date')->toArray();
    }

    public function updated($property)
    {
        if (in_array($property, ['date_from', 'date_to', 'office_id', 'unit_id', 'position_id', 'office_category_id', 'status', 'employee_search'])) {
            $this->loadStats();
        }
    }

    public function clearFilters()
    {
        $this->date_from = now()->toDateString();
        $this->date_to = now()->toDateString();
        $this->status = '';
        $this->office_id = '';
        $this->unit_id = '';
        $this->position_id = '';
        $this->office_category_id = '1';
        $this->employee_search = '';
        $this->loadStats();
    }

    public function selectTimelog($id)
    {
        $this->editingTimelog = WfhTimelog::find($id);

        $this->editTimeIn = $this->editingTimelog->time_in
            ? $this->editingTimelog->time_in->format('H:i')
            : '';

        $this->editTimeOut = $this->editingTimelog->time_out
            ? $this->editingTimelog->time_out->format('H:i')
            : '';

        $this->editAccomplishments = $this->editingTimelog->accomplishments;

        $this->showEditModal = true;
    }

    public function saveTimelog()
    {
        $this->validate([
            'editTimeIn' => 'nullable|date_format:H:i',
            'editTimeOut' => 'nullable|date_format:H:i',
            'editAccomplishments' => 'nullable|string|max:1000',
        ]);

        $timelog = $this->editingTimelog;

        // Old data
        $oldData = [
            'time_in' => $timelog->time_in,
            'time_out' => $timelog->time_out,
            'accomplishments' => $timelog->accomplishments,
        ];

        // Update
        $timelog->time_in = $this->editTimeIn
            ? $timelog->date->format('Y-m-d') . ' ' . $this->editTimeIn . ':00'
            : null;

        $timelog->time_out = $this->editTimeOut
            ? $timelog->date->format('Y-m-d') . ' ' . $this->editTimeOut . ':00'
            : null;

        $timelog->accomplishments = $this->editAccomplishments;
        $timelog->status = 'completed';


        $timelog->save();

        // New data
        $newData = [
            'time_in' => $timelog->time_in,
            'time_out' => $timelog->time_out,
            'accomplishments' => $timelog->accomplishments,
        ];

        // Format helper
        $format = function ($field, $value) {
            if (!$value) return 'null';

            if (in_array($field, ['time_in', 'time_out'])) {
                return \Carbon\Carbon::parse($value)->format('h:i A');
            }

            return $value;
        };

        // Detect changes
        $changes = [];

        foreach ($oldData as $field => $oldValue) {
            $newValue = $newData[$field];

            $old = $format($field, $oldValue);
            $new = $format($field, $newValue);

            if ($old !== $new) {
                $changes[] = ucfirst(str_replace('_', ' ', $field)) .
                    ": '{$old}' → '{$new}'";
            }
        }

        // Actor (admin)
        $actorName = auth()->user()->name ?? 'System';

        // Affected employee
        $employee = $timelog->user->employee ?? null;
        $employeeName = $timelog->user->name ?? 'Unknown';
        $employeeNumber = $employee->employee_number ?? 'N/A';

        // Description
        $description = count($changes)
            ? "{$actorName} edited timelog of {$employeeName} ({$employeeNumber}): " . implode(', ', $changes)
            : "{$actorName} opened timelog of {$employeeName} ({$employeeNumber}) but made no changes";

        // ✅ Save using YOUR fields
        ActivityLog::create([
            'user_id' => auth()->id(), // admin (actor)
            'affected_user_id' => $timelog->user_id, // employee (user)
            'affected_employee_id' => $employee?->id, // employee record
            'action' => 'edit_timelog',
            'description' => $description,
            'ip_address' => request()->ip(),
        ]);


        $this->showEditModal = false;
        Flux::modal('edit-timelog-modal')->close();
        $this->editingTimelog = null;
        $this->loadStats();
    }

    public function render()
    {
        $offices = Office::all();
        $units = Unit::all();
        $positions = Position::all();
        $officeCategories = OfficeCategory::all();

        return view('livewire.admin.wfh-dashboard', compact('offices', 'units', 'positions', 'officeCategories'))
            ->layout('components.layouts.admin');
    }
}
