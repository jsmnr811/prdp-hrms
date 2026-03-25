<div>

    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">

        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">
                My TimeLogs
            </h1>
            <p class="text-sm text-gray-500 dark:text-zinc-400">
                Record your Work From Home time in and out
            </p>
        </div>

        <a href="{{ route('timelogs.export', [
            'user_id' => auth()->id(),
            'date_from' => $filterDateFrom,
            'date_to' => $filterDateTo,
        ]) }}"
            target="_blank"
            class="w-full sm:w-auto justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors flex items-center gap-2">

            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>

            Export PDF
        </a>

    </div>

    {{-- Flash Messages --}}
    @if (session()->has('flash.success'))
    <div class="mb-4 p-4 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 rounded-lg">
        <p class="text-sm text-green-800 dark:text-green-400">{{ session('flash.success') }}</p>
    </div>
    @endif

    {{-- Time In/Out Section --}}
    <div
        class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-700 overflow-hidden mb-6">
        {{-- Header with Date --}}
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 dark:from-blue-700 dark:to-blue-800 px-6 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Today's Date</p>
                    <p class="text-white text-2xl font-bold">{{ now()->format('l, F d, Y') }}</p>
                </div>
                <div class="text-right">
                    <p class="text-blue-100 text-sm font-medium">Current Time</p>
                    <p class="text-white text-2xl font-bold" x-data x-init="setInterval(() => { $el.textContent = new Date().toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', second: '2-digit' }) }, 1000)">
                        {{ now()->format('h:i:s A') }}
                    </p>
                </div>
            </div>
        </div>

        <div class="p-6">
            @php
            $currentTimelog = $this->getCurrentTimelog();
            $completedTimelog = $this->getTodayCompletedTimelog();
            // Use component properties - they are set from env() in mount()
            $requireLocation = $this->requireLocation;
            $requireImage = $this->requireImage;
            $requireImageLocation = $this->requireImageLocation;
            @endphp

            @if ($completedTimelog)
            {{-- Completed Timelog --}}
            <div
                class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl p-6 text-center">
                <div
                    class="w-16 h-16 bg-green-100 dark:bg-green-800 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-green-800 dark:text-green-400 mb-2">Day Completed!</h3>
                <p class="text-sm text-green-600 dark:text-green-500 mb-4">
                    You have successfully timed in and out for today.
                </p>
                <div class="flex justify-center gap-4 text-sm text-gray-600 dark:text-zinc-400">
                    <span>Time In:
                        <strong>{{ \Carbon\Carbon::parse($completedTimelog->time_in)->format('h:i A') }}</strong></span>
                    <span>Time Out:
                        <strong>{{ \Carbon\Carbon::parse($completedTimelog->time_out)->format('h:i A') }}</strong></span>
                </div>
            </div>
            @elseif ($currentTimelog)
            {{-- Time Out Form --}}
            <form wire:submit.prevent="timeOut">
                <div
                    class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl p-4 mb-4">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-12 h-12 bg-green-100 dark:bg-green-800 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-green-800 dark:text-green-400">Currently Timed In</p>
                            <p class="text-sm text-green-600 dark:text-green-500">Since
                                {{ \Carbon\Carbon::parse($currentTimelog->time_in)->format('h:i A') }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Accomplishments (Required for time out) --}}
                <div class="mb-4">
                    <label for="accomplishments"
                        class="block text-sm font-medium text-gray-700 dark:text-zinc-300 mb-2">
                        Accomplishments <span class="text-red-500">*</span>
                    </label>
                    <textarea id="accomplishments" wire:model="accomplishments" rows="4" placeholder="What did you accomplish today?"
                        class="w-full px-4 py-3 border border-gray-300 dark:border-zinc-600 rounded-xl bg-white dark:bg-zinc-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"></textarea>
                    @error('accomplishments')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                    class="w-full sm:w-auto px-8 py-4 bg-red-600 hover:bg-red-700 active:bg-red-800 text-white text-lg font-semibold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl flex items-center justify-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    Time Out
                </button>
            </form>
            @else
            {{-- Time In Form --}}
            <form wire:submit.prevent="timeIn" x-data="{
                    getLocation() {
                            if (!navigator.geolocation) {
                                alert('Geolocation is not supported by your browser');
                                return;
                            }
                
                            navigator.geolocation.getCurrentPosition(
                                (position) => {
                                    @this.set('deviceLatitude', position.coords.latitude);
                                    @this.set('deviceLongitude', position.coords.longitude);
                                    this.locationStatus = 'Location obtained!';
                                    this.locationError = '';
                                },
                                (error) => {
                                    this.locationError = 'Unable to get location: ' + error.message;
                                    this.locationStatus = '';
                                }, { enableHighAccuracy: true, timeout: 10000 }
                            );
                        },
                        locationStatus: '',
                        locationError: '',
                        async extractGpsFromFile(file) {
                                // Client-side EXIF extraction using a simple GPS parser
                                if (!file) return null;
                
                                try {
                                    const arrayBuffer = await file.arrayBuffer();
                                    const view = new DataView(arrayBuffer);
                
                                    // Look for EXIF marker (0xFFE1)
                                    let offset = 2;
                                    while (offset < view.byteLength) {
                                        if (view.getUint16(offset) === 0xFFE1) {
                                            // Found APP1 marker
                                            const exifStart = offset + 4;
                
                                            // Check for 'Exif' header
                                            if (view.getUint32(exifStart) === 0x45786966 && view.getUint16(exifStart + 4) === 0x0000) {
                                                const tiffStart = exifStart + 6;
                                                const littleEndian = view.getUint16(tiffStart) === 0x4949;
                
                                                const ifdOffset = view.getUint32(tiffStart + 4, littleEndian);
                                                const numEntries = view.getUint16(tiffStart + ifdOffset, littleEndian);
                
                                                let gpsLatitude = null;
                                                let gpsLongitude = null;
                                                let latRef = 'N';
                                                let lonRef = 'E';
                
                                                for (let i = 0; i < numEntries; i++) {
                                                    const entryOffset = tiffStart + ifdOffset + 12 + (i * 12);
                                                    const tag = view.getUint16(entryOffset, littleEndian);
                
                                                    if (tag === 0x0001) latRef = String.fromCharCode(view.getUint8(entryOffset + 8));
                                                    if (tag === 0x0003) lonRef = String.fromCharCode(view.getUint8(entryOffset + 8));
                                                    if (tag === 0x0002) {
                                                        const latValues = this.readGpsCoordinate(view, entryOffset, tiffStart, littleEndian);
                                                        if (latValues) gpsLatitude = latValues;
                                                    }
                                                    if (tag === 0x0004) {
                                                        const lonValues = this.readGpsCoordinate(view, entryOffset, tiffStart, littleEndian);
                                                        if (lonValues) gpsLongitude = lonValues;
                                                    }
                                                }
                
                                                if (gpsLatitude && gpsLongitude) {
                                                    const lat = gpsLatitude[0] + gpsLatitude[1] / 60 + gpsLatitude[2] / 3600;
                                                    const lon = gpsLongitude[0] + gpsLongitude[1] / 60 + gpsLongitude[2] / 3600;
                
                                                    return {
                                                        latitude: latRef === 'S' ? -lat : lat,
                                                        longitude: lonRef === 'W' ? -lon : lon
                                                    };
                                                }
                                            }
                                            break;
                                        }
                                        offset += 2 + view.getUint16(offset + 2);
                                    }
                                } catch (e) {
                                    console.error('Error reading EXIF:', e);
                                }
                                return null;
                            },
                            readGpsCoordinate(view, entryOffset, tiffStart, littleEndian) {
                                const valueOffset = tiffStart + view.getUint32(entryOffset + 8, littleEndian);
                                const deg = this.readRational(view, valueOffset, littleEndian);
                                const min = this.readRational(view, valueOffset + 8, littleEndian);
                                const sec = this.readRational(view, valueOffset + 16, littleEndian);
                                return [deg, min, sec];
                            },
                            readRational(view, offset, littleEndian) {
                                const num = view.getUint32(offset, littleEndian);
                                const den = view.getUint32(offset + 4, littleEndian);
                                return den ? num / den : 0;
                            },
                            handleFileSelect(event) {
                                const file = event.target.files[0];
                                if (file && {{ $requireImageLocation ? 'true' : 'false' }}) {
                                    this.extractGpsFromFile(file).then(gps => {
                                        if (gps) {
                                            @this.set('deviceLatitude', gps.latitude);
                                            @this.set('deviceLongitude', gps.longitude);
                                            this.locationStatus = 'GPS extracted from image!';
                                        } else {
                                            this.locationError = 'No GPS data found in image';
                                        }
                                    });
                                }
                            }
                }">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    {{-- Selfie Upload (Filepond) --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-zinc-300 mb-2">
                            Selfie Photo
                            @if ($requireLocation || $requireImage)
                            <span class="text-red-500">*</span>
                            @endif
                        </label>
                        @if ($requireLocation || $requireImage)
                        <p class="mt-1 text-xs text-gray-500 dark:text-zinc-400 mb-3">
                            @if ($requireImageLocation)
                            📍 Location will be extracted from image metadata
                            @else
                            📍 Device location will be captured automatically
                            @endif
                            @if ($requireImage)
                            <br>
                            📸 Photo is required
                            @endif
                        </p>
                        @endif
                        {{-- Livewire File Upload with Preview --}}
                        <div>
                            <div class="relative">
                                <input type="file" wire:model="selfie" accept="image/jpeg,image/jpg,image/png"
                                    class="hidden" id="selfie-upload" x-on:change="handleFileSelect($event)">

                                <label for="selfie-upload"
                                    class="flex flex-col items-center justify-center w-full h-48 border-2 border-dashed border-gray-300 dark:border-zinc-600 rounded-xl cursor-pointer bg-gray-50 dark:bg-zinc-700 hover:bg-gray-100 dark:hover:bg-zinc-600 transition-colors">
                                    @if ($selfie && $selfie->temporaryUrl())
                                    <div class="relative w-full h-full">
                                        <img src="{{ $selfie->temporaryUrl() }}"
                                            class="w-full h-full object-cover rounded-xl" alt="Preview">
                                        <div
                                            class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center rounded-xl opacity-0 hover:opacity-100 transition-opacity">
                                            <span class="text-white text-sm font-medium">Click to
                                                change</span>
                                        </div>
                                    </div>
                                    @else
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <svg class="w-10 h-10 mb-3 text-gray-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <p class="mb-2 text-sm text-gray-500 dark:text-zinc-400"><span
                                                class="font-semibold">Click to upload</span> or drag and
                                            drop</p>
                                        <p class="text-xs text-gray-500 dark:text-zinc-400">JPG, JPEG or PNG
                                        </p>
                                    </div>
                                    @endif
                                </label>
                            </div>
                            @if ($requireImageLocation)
                            <p class="mt-2 text-xs text-gray-500 dark:text-zinc-400">
                                📍 GPS will be extracted from image metadata automatically
                            </p>
                            @endif
                        </div>
                        @error('selfie')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Location Info Card --}}
                    <div class="flex flex-col justify-center">
                        <div
                            class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-4 mb-4">
                            <h3 class="font-semibold text-blue-800 dark:text-blue-400 mb-2">Ready to Start Your
                                Day?
                            </h3>
                            <p class="text-sm text-blue-600 dark:text-blue-500">
                                Click the Time In button to automatically record your current time and date.
                            </p>
                        </div>

                        {{-- Location Status --}}
                        @if ($requireLocation && !$requireImageLocation)
                        <div class="bg-gray-50 dark:bg-zinc-700/50 rounded-xl p-4">
                            <div class="flex items-center justify-between mb-2">
                                <span
                                    class="text-sm font-medium text-gray-700 dark:text-zinc-300">Location</span>
                                <span x-text="locationStatus"
                                    class="text-sm text-green-600 dark:text-green-400"></span>
                            </div>
                            <div x-show="locationError" x-text="locationError"
                                class="text-sm text-red-600 dark:text-red-400 mb-2"></div>
                            <button type="button" x-on:click="getLocation()"
                                class="w-full px-4 py-2 bg-gray-200 dark:bg-zinc-600 hover:bg-gray-300 dark:hover:bg-zinc-500 text-gray-700 dark:text-gray-200 text-sm font-medium rounded-lg transition-colors flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Get Device Location
                            </button>
                            <p class="mt-2 text-xs text-gray-500 dark:text-zinc-400">
                                Please ensure location services are enabled in your browser/device settings.
                            </p>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="mt-6">
                    <button type="submit"
                        class="w-full sm:w-auto px-10 py-4 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white text-lg font-semibold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl flex items-center justify-center gap-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Time In
                    </button>
                </div>
            </form>
            @endif
        </div>
    </div>

    {{-- Timelogs Table --}}
    <div
        class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-700 overflow-hidden">
        <div
            class="px-6 py-4 border-b border-gray-200 dark:border-zinc-700 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Recent TimeLogs</h2>

            {{-- Date Range Filter --}}
            <div class="flex items-center gap-2">
                <div class="flex items-center gap-2">
                    <input type="date" wire:model="filterDateFrom" wire:change="$refresh"
                        class="px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-lg bg-white dark:bg-zinc-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <span class="text-gray-500 dark:text-zinc-400">to</span>
                    <input type="date" wire:model="filterDateTo" wire:change="$refresh"
                        class="px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-lg bg-white dark:bg-zinc-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                @if ($filterDateFrom || $filterDateTo)
                <button wire:click="clearFilter"
                    class="px-3 py-2 text-sm text-gray-600 dark:text-zinc-400 hover:text-gray-900 dark:hover:text-white">
                    Clear
                </button>
                @endif
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-zinc-700">
                    <tr>
                        <th
                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-zinc-400 uppercase tracking-wider">
                            Date</th>
                        <th
                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-zinc-400 uppercase tracking-wider">
                            Time In</th>
                        <th
                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-zinc-400 uppercase tracking-wider">
                            Time Out</th>
                        <th
                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-zinc-400 uppercase tracking-wider">
                            Hours</th>
                        <th
                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-zinc-400 uppercase tracking-wider">
                            Location</th>
                        <th
                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-zinc-400 uppercase tracking-wider">
                            Status</th>
                        <th
                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-zinc-400 uppercase tracking-wider">
                            Accomplishments</th>
                    </tr>
                </thead>
                <tbody x-data="{ open: {} }" class="divide-y divide-gray-200 dark:divide-zinc-700">
                    @forelse($timelogs as $timelog)
                    @php($uniqueId = $timelog->id)
                    <!-- Main Row -->
                    <tr class="hover:bg-gray-50 dark:hover:bg-zinc-700/50 transition-colors">
                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-zinc-300">
                            {{ \Carbon\Carbon::parse($timelog->date)->format('M d, Y') }}
                        </td>

                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-zinc-300">
                            {{ $timelog->time_in ? \Carbon\Carbon::parse($timelog->time_in)->format('h:i A') : '-' }}
                        </td>

                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-zinc-300">
                            {{ $timelog->time_out ? \Carbon\Carbon::parse($timelog->time_out)->format('h:i A') : '-' }}
                        </td>

                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-zinc-300">
                            {{ $timelog->total_hours ? number_format($timelog->total_hours, 2) . ' hrs' : '-' }}
                        </td>

                        <td class="px-4 py-3">
                            @if ($timelog->latitude && $timelog->longitude)
                            <span
                                class="inline-flex items-center gap-1 px-2 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 text-xs font-medium rounded-full">
                                Verified
                            </span>
                            @else
                            <span class="text-gray-400">-</span>
                            @endif
                        </td>

                        <td class="px-4 py-3">
                            @switch($timelog->status)
                            @case('pending')
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
                                Pending
                            </span>
                            @break

                            @case('completed')
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                                Completed
                            </span>
                            @break
                            @endswitch
                        </td>

                        <td class="px-4 py-3">
                            @if ($timelog->accomplishments)
                            <button @click="open[{{ $uniqueId }}] = !open[{{ $uniqueId }}]"
                                class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 text-sm"
                                x-text="open[{{ $uniqueId }}] ? 'Close' : 'View'">
                            </button>
                            @else
                            <span class="text-gray-400">-</span>
                            @endif
                        </td>
                    </tr>

                    <!-- Accomplishment Row -->
                    <tr x-show="open[{{ $uniqueId }}]" x-transition x-cloak
                        class="bg-gray-50 dark:bg-zinc-700/30">
                        <td colspan="7" class="px-6 py-5">

                            <div class="w-full">
                                <h3
                                    class="text-xs font-semibold text-gray-500 dark:text-zinc-400 uppercase tracking-wide mb-3">
                                    Accomplishments
                                </h3>

                                <div class="space-y-2 text-sm text-gray-800 dark:text-zinc-300 leading-relaxed">
                                    @foreach (preg_split('/\r\n|\r|\n/', $timelog->accomplishments) as $item)
                                    @if (trim($item) !== '')
                                    <p class="whitespace-pre-line">{{ trim($item) }}</p>
                                    @endif
                                    @endforeach
                                </div>
                            </div>

                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-gray-500 dark:text-zinc-400">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-gray-300 dark:text-zinc-600 mb-2" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p>No timelogs found. Time in to get started!</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

        {{-- Pagination --}}
        @if ($timelogs->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 dark:border-zinc-700">
            {{ $timelogs->links() }}
        </div>
        @endif
    </div>
</div>