<?php

namespace App\Livewire\Admin;

use App\Models\WfhTimelog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\ActivityLog;

class WfhTimelogs extends Component
{
    use WithFileUploads, WithPagination;

    public $date;

    public $selfie;

    public $accomplishments;

    public $deviceLatitude;

    public $deviceLongitude;

    public $requireLocation;

    public $requireImage;

    public $requireImageLocation;

    public $filterDateFrom;

    public $filterDateTo;

    // Edit properties
    public $editingId;

    public $editAccomplishments;

    // Timeout update properties
    public $showTimeoutModal = false;
    public $newTimeout;
    public $currentTimePreview;

    // Debug properties
    public $debugInfo = '';

    protected $rules = [
        'date' => 'required|date',
        'selfie' => 'nullable|image|max:10240',
    ];

    public function mount()
    {
        // Read config values - convert string 'true' to boolean
        $this->requireLocation = filter_var(env('WFH_REQUIRE_LOCATION', false), FILTER_VALIDATE_BOOLEAN);
        $this->requireImage = filter_var(env('WFH_REQUIRE_IMAGE', false), FILTER_VALIDATE_BOOLEAN);
        $this->requireImageLocation = filter_var(env('WFH_REQUIRE_IMAGE_LOCATION', false), FILTER_VALIDATE_BOOLEAN);
        $this->date = now()->toDateString();
        $this->filterDateFrom = now()->startOfMonth()->toDateString();
        $this->filterDateTo = now()->endOfMonth()->toDateString();

        $this->debugInfo = 'Config - requireLocation: ' . ($this->requireLocation ? 'true' : 'false') .
            ', requireImage: ' . ($this->requireImage ? 'true' : 'false') .
            ', requireImageLocation: ' . ($this->requireImageLocation ? 'true' : 'false');
    }

    public function render()
    {
        $query = WfhTimelog::with('user')->where('user_id', Auth::id());

        // Apply date range filter
        if ($this->filterDateFrom && $this->filterDateTo) {
            $query->whereBetween('date', [$this->filterDateFrom, $this->filterDateTo]);
        } elseif ($this->filterDateFrom) {
            $query->where('date', '>=', $this->filterDateFrom);
        } elseif ($this->filterDateTo) {
            $query->where('date', '<=', $this->filterDateTo);
        }

        $timelogs = $query->orderBy('date', 'desc')
            ->orderBy('time_in', 'desc')
            ->paginate(10);

        return view('livewire.admin.wfh-timelogs', [
            'timelogs' => $timelogs,
        ])->layout('components.layouts.admin');
    }

    /**
     * Extract GPS coordinates from image EXIF data
     */
    private function extractGpsFromImage($image): ?array
    {
        if (! $image) {
            Log::info('WFH: No image provided');

            return null;
        }

        // Check if EXIF extension is loaded
        if (! extension_loaded('exif')) {
            Log::info('WFH: EXIF extension not loaded');

            return null;
        }

        try {
            // Get the temporary file path
            $filePath = $image->getRealPath();
            Log::info('WFH: Reading EXIF from: ' . $filePath);

            // Check if file exists
            if (! file_exists($filePath)) {
                Log::info('WFH: File does not exist at: ' . $filePath);

                return null;
            }

            // Get file info
            $fileSize = filesize($filePath);
            $mimeType = mime_content_type($filePath);
            Log::info('WFH: File info', ['size' => $fileSize, 'mime' => $mimeType]);

            // Read EXIF data
            $exif = @exif_read_data($filePath, 'EXIF', true);

            if (! $exif) {
                Log::info('WFH: No EXIF data found');

                return null;
            }

            Log::info('WFH: EXIF data found', [
                'GPSLatitude' => isset($exif['GPS']['GPSLatitude']),
                'GPSLongitude' => isset($exif['GPS']['GPSLongitude']),
                'sections' => implode(',', array_keys($exif)),
            ]);

            // Check for GPS tags - check both possible locations
            $gpsLatitude = null;
            $gpsLongitude = null;
            $gpsLatitudeRef = null;
            $gpsLongitudeRef = null;

            // Try GPS section first (more common)
            if (isset($exif['GPS']['GPSLatitude'])) {
                $gpsLatitude = $exif['GPS']['GPSLatitude'];
                $gpsLatitudeRef = $exif['GPS']['GPSLatitudeRef'] ?? 'N';
                Log::info('WFH: Found GPSLatitude in GPS section', ['value' => json_encode($gpsLatitude), 'ref' => $gpsLatitudeRef]);
            } elseif (isset($exif['EXIF']['GPSLatitude'])) {
                $gpsLatitude = $exif['EXIF']['GPSLatitude'];
                $gpsLatitudeRef = $exif['EXIF']['GPSLatitudeRef'] ?? 'N';
            }

            if (isset($exif['GPS']['GPSLongitude'])) {
                $gpsLongitude = $exif['GPS']['GPSLongitude'];
                $gpsLongitudeRef = $exif['GPS']['GPSLongitudeRef'] ?? 'E';
                Log::info('WFH: Found GPSLongitude in GPS section', ['value' => json_encode($gpsLongitude), 'ref' => $gpsLongitudeRef]);
            } elseif (isset($exif['EXIF']['GPSLongitude'])) {
                $gpsLongitude = $exif['EXIF']['GPSLongitude'];
                $gpsLongitudeRef = $exif['EXIF']['GPSLongitudeRef'] ?? 'E';
            }

            if (! $gpsLatitude || ! $gpsLongitude) {
                Log::info('WFH: No GPS coordinates found in EXIF');

                return null;
            }

            // Parse GPS coordinates
            $lat = $this->convertGpsToDecimal($gpsLatitude, $gpsLatitudeRef);
            $lon = $this->convertGpsToDecimal($gpsLongitude, $gpsLongitudeRef);

            Log::info('WFH: Converted GPS', ['lat' => $lat, 'lon' => $lon]);

            if ($lat !== null && $lon !== null) {
                return ['latitude' => $lat, 'longitude' => $lon];
            }

            return null;
        } catch (\Exception $e) {
            Log::info('WFH: Exception extracting GPS', ['error' => $e->getMessage()]);

            return null;
        }
    }

    /**
     * Convert GPS coordinates from EXIF format to decimal
     */
    private function convertGpsToDecimal($coords, string $ref): ?float
    {
        if (! is_array($coords) || count($coords) < 3) {
            return null;
        }

        $degrees = $this->fractionToDecimal($coords[0]);
        $minutes = $this->fractionToDecimal($coords[1]);
        $seconds = $this->fractionToDecimal($coords[2]);

        if ($degrees === null || $minutes === null || $seconds === null) {
            return null;
        }

        $decimal = $degrees + ($minutes / 60) + ($seconds / 3600);

        // Apply reference direction
        if ($ref === 'S' || $ref === 'W' || $ref === 'South' || $ref === 'West') {
            $decimal = -$decimal;
        }

        return $decimal;
    }

    /**
     * Convert fraction string or value to decimal
     */
    private function fractionToDecimal($value): ?float
    {
        if ($value === null) {
            return null;
        }

        if (is_float($value) || is_int($value)) {
            return (float) $value;
        }

        if (is_string($value)) {
            $parts = explode('/', $value);
            if (count($parts) === 2) {
                $numerator = (float) $parts[0];
                $denominator = (float) $parts[1];
                if ($denominator !== 0) {
                    return $numerator / $denominator;
                }
            }

            // Try to parse as direct float
            return (float) $value;
        }

        return null;
    }

    /**
     * Handle device location from JavaScript
     */
    public function updatedDeviceLatitude($value)
    {
        // This is called when JavaScript updates the device location
    }

    public function timeIn()
    {
        $this->validate();

        $latitude = null;
        $longitude = null;
        $imagePath = null;

        // Debug
        $this->debugInfo = 'timeIn called. selfie: ' . ($this->selfie ? 'yes' : 'no') .
            ', requireLocation: ' . ($this->requireLocation ? 'true' : 'false') .
            ', requireImageLocation: ' . ($this->requireImageLocation ? 'true' : 'false');

        // Handle image upload
        if ($this->selfie) {
            // Store the image first
            $imagePath = $this->selfie->store('wfh-selfies', 'public');

            // Debug - check the temp file
            $tempPath = $this->selfie->getRealPath();
            $this->debugInfo .= ' | temp file: ' . $tempPath . ' | exists: ' . (file_exists($tempPath) ? 'yes' : 'no');

            // If location is required from image metadata
            if ($this->requireLocation && $this->requireImageLocation) {
                // First check if JavaScript already extracted GPS from the image (client-side)
                if (! empty($this->deviceLatitude) && ! empty($this->deviceLongitude)) {
                    $latitude = $this->deviceLatitude;
                    $longitude = $this->deviceLongitude;
                    $this->debugInfo .= ' | GPS from client-side: lat=' . $latitude . ', lon=' . $longitude;
                } else {
                    // Fallback: try server-side EXIF extraction
                    $gpsData = $this->extractGpsFromImage($this->selfie);
                    if ($gpsData) {
                        $latitude = $gpsData['latitude'];
                        $longitude = $gpsData['longitude'];
                        $this->debugInfo .= ' | GPS found (server): lat=' . $latitude . ', lon=' . $longitude;
                    } else {
                        $this->debugInfo .= ' | GPS NOT found in image';
                    }
                }
            }
        } else {
            $this->debugInfo .= ' | No selfie uploaded';
        }

        // If location is required but not from image, check device location
        if ($this->requireLocation && ! $this->requireImageLocation) {
            // Check if device location was provided via JavaScript
            if (! empty($this->deviceLatitude) && ! empty($this->deviceLongitude)) {
                $latitude = $this->deviceLatitude;
                $longitude = $this->deviceLongitude;
            }
        }

        // Check if location is required but not found
        if ($this->requireLocation && (empty($latitude) || empty($longitude))) {
            if ($this->requireImageLocation) {
                $this->addError('selfie', 'Location not found in image. Please upload a photo with GPS coordinates enabled in camera settings.');
            } else {
                $this->addError('selfie', 'Location is required. Please enable location services and allow access.');
            }

            return;
        }

        // Check if image is required
        if ($this->requireImage && empty($imagePath)) {
            $this->addError('selfie', 'Selfie image is required for time in.');

            return;
        }

        // Check if there's already a time in for today
        $existingTimelog = WfhTimelog::where('user_id', Auth::id())
            ->where('date', $this->date)
            ->whereNotNull('time_in')
            ->first();

        if ($existingTimelog) {
            $this->addError('timeIn', 'You have already timed in for today.');

            return;
        }

        // Auto-log current date and time
        WfhTimelog::create([
            'user_id' => Auth::id(),
            'date' => now()->toDateString(),
            'time_in' => now()->format('H:i:s'),
            'latitude' => $latitude,
            'longitude' => $longitude,
            'image_path' => $imagePath,
            'status' => 'pending',
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'time_in',
            'description' => 'User timed in successfully',
            'ip_address' => request()->ip(),
        ]);

        // Reset the file upload and device location
        $this->selfie = null;
        $this->deviceLatitude = null;
        $this->deviceLongitude = null;

        $this->addFlash('success', 'Time in recorded successfully!');
    }

    public function timeOut()
    {
        // Find the latest timelog for today without time out
        $timelog = WfhTimelog::where('user_id', Auth::id())
            ->where('date', $this->date)
            ->whereNotNull('time_in')
            ->whereNull('time_out')
            ->first();

        if (! $timelog) {
            $this->addError('timeOut', 'No active time in found for today.');

            return;
        }

        // Validate accomplishments if required
        if (empty($this->accomplishments)) {
            $this->addError('accomplishments', 'Accomplishments is required when timing out.');

            return;
        }

        // Auto-log current time and save accomplishments
        $timelog->update([
            'time_out' => now()->format('H:i:s'),
            'accomplishments' => $this->accomplishments,
            'status' => 'completed',
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'time_out',
            'description' => 'User timed out successfully',
            'ip_address' => request()->ip(),
        ]);


        $this->reset(['accomplishments']);
        $this->addFlash('success', 'Time out recorded successfully!');
    }

    public function getCurrentTimelog()
    {
        return WfhTimelog::where('user_id', Auth::id())
            ->where('date', $this->date)
            ->whereNotNull('time_in')
            ->whereNull('time_out')
            ->first();
    }

    public function getTodayCompletedTimelog()
    {
        return WfhTimelog::where('user_id', Auth::id())
            ->where('date', $this->date)
            ->whereNotNull('time_in')
            ->whereNotNull('time_out')
            ->where('status', 'completed')
            ->first();
    }

    public function updatedFilterDateFrom()
    {
        $this->resetPage();
    }

    public function updatedFilterDateTo()
    {
        $this->resetPage();
    }

    public function clearFilter()
    {
        $this->filterDateFrom = null;
        $this->filterDateTo = null;
        $this->resetPage();
    }

    public function startEditing($id)
    {
        $timelog = WfhTimelog::find($id);
        if ($timelog && $timelog->user_id == Auth::id() && $timelog->date->isToday() && $timelog->status === 'completed') {
            $this->editingId = $id;
            $this->editAccomplishments = $timelog->accomplishments;
        }
    }

    public function cancelEditing()
    {
        $this->editingId = null;
        $this->editAccomplishments = null;
    }

    public function openTimeoutModal($id)
    {
        $timelog = WfhTimelog::find($id);
        if ($timelog && $timelog->user_id == Auth::id() && $timelog->date->isToday() && $timelog->status === 'completed') {
            $this->editingId = $id;
            $this->newTimeout = now()->format('H:i');
            $this->currentTimePreview = now()->format('h:i A');
            $this->showTimeoutModal = true;
        }
    }

    public function closeTimeoutModal()
    {
        $this->showTimeoutModal = false;
        $this->newTimeout = null;
        $this->currentTimePreview = null;
    }

    public function updateTimeout()
    {
        $this->validate([
            'newTimeout' => 'required|date_format:H:i',
        ]);

        $timelog = WfhTimelog::find($this->editingId);
        if ($timelog && $timelog->user_id == Auth::id() && $timelog->date->isToday() && $timelog->status === 'completed') {
            $timelog->update([
                'time_out' => $this->newTimeout . ':00', // Add seconds
            ]);

            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'update_timeout',
                'description' => 'Updated timeout to ' . $this->newTimeout . ' for ' . $timelog->date,
                'ip_address' => request()->ip(),
            ]);

            $this->addFlash('success', 'Timeout updated successfully!');
            $this->closeTimeoutModal();
        }
    }


    public function confirmUpdateTimeoutToCurrent()
    {
        LivewireAlert::title('Update Timeout to Current Time')
            ->text('Are you sure you want to update your timeout to the current time?')
            ->question()
            ->timer(0)
            ->withConfirmButton('Yes, Update')
            ->withCancelButton('Cancel')
            ->onConfirm('updateTimeoutToCurrent')
            ->show();
    }

    public function updateTimeoutToCurrent()
    {
        $timelog = WfhTimelog::find($this->editingId);
        if ($timelog && $timelog->user_id == Auth::id() && $timelog->date->isToday() && $timelog->status === 'completed') {
            $currentTime = now()->format('H:i:s');
            $timelog->update([
                'time_out' => $currentTime,
            ]);

            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'update_timeout',
                'description' => 'Time-out updated for ' . $timelog->date->format('F j, Y'),
                'ip_address' => request()->ip(),
            ]);

            $this->addFlash('success', 'Timeout updated to current time successfully!');
        }
    }

    public function confirmUpdateAccomplishments()
    {
        LivewireAlert::title('Update Accomplishments')
            ->text('Are you sure you want to update the accomplishments?')
            ->question()
            ->timer(0)
            ->withConfirmButton('Yes, Update')
            ->withCancelButton('Cancel')
            ->onConfirm('updateAccomplishments')
            ->show();
    }

    public function updateAccomplishments()
    {
        $this->validate([
            'editAccomplishments' => 'required|string|min:1',
        ]);

        $timelog = WfhTimelog::find($this->editingId);
        if ($timelog && $timelog->user_id == Auth::id() && $timelog->date->isToday() && $timelog->status === 'completed') {
            $timelog->update([
                'accomplishments' => $this->editAccomplishments,
            ]);

            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'update_accomplishments',
                'description' => 'Updated accomplishments for ' . $timelog->date->format('F j, Y'),
                'ip_address' => request()->ip(),
            ]);

            $this->addFlash('success', 'Accomplishments updated successfully!');
            $this->cancelEditing();
        }
    }

    protected function addFlash($type, $message)
    {
        session()->flash('flash.' . $type, $message);
    }
}
