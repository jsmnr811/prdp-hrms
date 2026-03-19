<?php

namespace App\Livewire\Admin;

use App\Models\WfhTimelog;
use Livewire\Attributes\Layout;
use Livewire\Component;

class WfhMonitoring extends Component
{
    public $selectedTimelog = null;
    public $mapCenter = [14.5995, 120.9842]; // Manila, Philippines
    public $zoom = 12;
    public $selectedDate = null;

    public function mount()
    {
        // Set map center based on config or default to Manila
        $defaultLat = env('WFH_DEFAULT_LATITUDE', 14.5995);
        $defaultLng = env('WFH_DEFAULT_LONGITUDE', 120.9842);
        $this->mapCenter = [(float) $defaultLat, (float) $defaultLng];
        $this->selectedDate = now()->toDateString();
    }

    public function updatedSelectedDate()
    {
        $locations = $this->getEmployeeLocations();

        // Log for debugging
        error_log('Date changed to: ' . $this->selectedDate);
        error_log('Locations count: ' . count($locations));

        $this->dispatch('mapUpdated', ['locations' => $locations]);
    }

    public function getEmployeeLocations()
    {
        // Get timelogs with location data for selected date
        $timelogs = WfhTimelog::with('user.employee')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->where('date', $this->selectedDate)
            ->get()
            ->map(function ($log) {
                $employee = $log->user->employee;
                return [
                    'id' => $log->id,
                    'user_id' => $log->user_id,
                    'name' => $log->user->name,
                    'employee_number' => $log->user->employee_number,
                    'position' => $employee && $employee->position ? $employee->position->name : 'N/A',
                    'office' => $employee && $employee->office ? $employee->office->name : 'N/A',
                    'latitude' => $log->latitude,
                    'longitude' => $log->longitude,
                    'image_path' => $log->image_path ?? ($employee->image ?? null),
                    'time_in' => $log->time_in ? $log->time_in->format('h:i A') : null,
                    'time_out' => $log->time_out ? $log->time_out->format('h:i A') : null,
                    'status' => $log->status,
                    'date' => $log->date,
                ];
            });

        return $timelogs;
    }

    public function selectTimelog($timelogId)
    {
        $timelog = WfhTimelog::with('user.employee')->find($timelogId);

        if ($timelog) {
            $employee = $timelog->user->employee;
            $this->selectedTimelog = [
                'id' => $timelog->id,
                'name' => $timelog->user->name,
                'employee_number' => $timelog->user->employee_number,
                'position' => $employee && $employee->position ? $employee->position->name : 'N/A',
                'office' => $employee && $employee->office ? $employee->office->name : 'N/A',
                'date' => $timelog->date->format('F d, Y'),
                'time_in' => $timelog->time_in ? $timelog->time_in->format('h:i A') : 'N/A',
                'time_out' => $timelog->time_out ? $timelog->time_out->format('h:i A') : 'N/A',
                'total_hours' => $timelog->total_hours ? number_format($timelog->total_hours, 2) . ' hours' : 'N/A',
                'status' => $timelog->status,
                'image_path' => $timelog->image_path ?? ($employee->image ?? null),
                'accomplishments' => $timelog->accomplishments,
            ];
        }
    }

    public function closeDialog()
    {
        $this->selectedTimelog = null;
    }


    #[Layout('components.layouts.admin')]
    public function render()
    {
        return view('livewire.admin.wfh-monitoring');
    }
}
