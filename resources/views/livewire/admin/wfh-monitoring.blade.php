<div>
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">WFH Employee Monitoring</h1>
            <p class="text-gray-600 dark:text-zinc-400">View real-time locations of employees working from home</p>
        </div>
        <div class="flex items-center gap-4">
            <label class="text-sm text-gray-600 dark:text-zinc-400">Select Date:</label>
            <input type="date" wire:model.live="selectedDate"
                class="px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-lg bg-white dark:bg-zinc-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>
    </div>

    <!-- Map Container -->
    <div class="relative bg-white dark:bg-zinc-800 rounded-lg shadow overflow-hidden" style="height: 600px;" wire:ignore>
        <div id="map" class="w-full h-full"></div>

        <!-- Loading State -->
        <div id="loading" class="absolute inset-0 flex items-center justify-center bg-white dark:bg-zinc-800 ">
            <div class="text-center">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto mb-4"></div>
                <p class="text-gray-600 dark:text-zinc-400">Loading map...</p>
            </div>
        </div>
    </div>

    <!-- Legend -->
    <div class="mt-4 flex items-center gap-6 text-sm text-gray-600 dark:text-zinc-400">
        <div class="flex items-center gap-2">
            <div class="w-4 h-4 rounded-full bg-blue-500 border-2 border-white"></div>
            <span>Timed In</span>
        </div>
        <div class="flex items-center gap-2">
            <div class="w-4 h-4 rounded-full bg-yellow-500 border-2 border-white"></div>
            <span>Pending</span>
        </div>
        <div class="flex items-center gap-2">
            <div class="w-4 h-4 rounded-full bg-green-500 border-2 border-white"></div>
            <span>Completed</span>
        </div>
    </div>

    <!-- Details Dialog -->
    @if ($selectedTimelog)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4" x-data="{ open: true }" x-show="open"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

            <!-- Backdrop -->
            <div class="absolute inset-0 bg-black/50" @click="$wire.closeDialog()"></div>

            <!-- Dialog Content -->
            <div class="relative bg-white dark:bg-zinc-800 rounded-lg shadow-xl max-w-lg w-full p-6"
                x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100">

                <!-- Header -->
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">Employee Details</h2>
                    <button @click="$wire.closeDialog()"
                        class="text-gray-400 hover:text-gray-600 dark:hover:text-zinc-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Employee Info -->
                <div class="flex items-start gap-4 mb-6">
                    <div class="shrink-0">
                        @if ($selectedTimelog['image_path'])
                            <img src="{{ Storage::url($selectedTimelog['image_path']) }}"
                                alt="{{ $selectedTimelog['name'] }}"
                                class="w-20 h-20 rounded-full object-cover border-2 border-gray-200 dark:border-zinc-700">
                        @else
                            <div
                                class="w-20 h-20 rounded-full bg-gray-200 dark:bg-zinc-700 flex items-center justify-center">
                                <span class="text-2xl font-semibold text-gray-600 dark:text-zinc-300">
                                    {{ substr($selectedTimelog['name'], 0, 1) }}
                                </span>
                            </div>
                        @endif
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            {{ $selectedTimelog['name'] }}
                        </h3>
                        <p class="text-gray-600 dark:text-zinc-400">
                            {{ $selectedTimelog['employee_number'] }}
                        </p>
                        <p class="text-sm text-gray-500 dark:text-zinc-500">
                            {{ $selectedTimelog['position'] }} - {{ $selectedTimelog['office'] }}
                        </p>
                    </div>
                </div>

                <!-- Time Details -->
                <div class="bg-gray-50 dark:bg-zinc-700 rounded-lg p-4 mb-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-gray-500 dark:text-zinc-400 uppercase tracking-wide">Date</p>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $selectedTimelog['date'] }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-zinc-400 uppercase tracking-wide">Status</p>
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if ($selectedTimelog['status'] === 'completed') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                @elseif($selectedTimelog['status'] === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200 @endif">
                                {{ ucfirst($selectedTimelog['status']) }}
                            </span>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-zinc-400 uppercase tracking-wide">Time In</p>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $selectedTimelog['time_in'] }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-zinc-400 uppercase tracking-wide">Time Out</p>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $selectedTimelog['time_out'] }}</p>
                        </div>
                        <div class="col-span-2">
                            <p class="text-xs text-gray-500 dark:text-zinc-400 uppercase tracking-wide">Total Hours</p>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $selectedTimelog['total_hours'] }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Accomplishments -->
                @if ($selectedTimelog['accomplishments'])
                    <div>
                        <p class="text-xs text-gray-500 dark:text-zinc-400 uppercase tracking-wide mb-1">Accomplishments
                        </p>
                        <p class="text-gray-700 dark:text-zinc-300 text-sm">{{ $selectedTimelog['accomplishments'] }}
                        </p>
                    </div>
                @endif

                <!-- Close Button -->
                <div class="mt-6 flex justify-end">
                    <button @click="$wire.closeDialog()"
                        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-zinc-700 dark:hover:bg-zinc-600 text-gray-900 dark:text-white rounded-lg transition-colors">
                        Close
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>

@script
    <script>
        let map = null;

        function initMap(locationsData) {
            // Destroy existing map if it exists
            if (map) {
                map.remove();
                map = null;
            }

            // Get map center from PHP
            const mapCenter = @json($mapCenter);

            // Check if map element exists
            const mapElement = document.getElementById('map');
            if (!mapElement) {
                console.log('Map element not found, retrying...');
                setTimeout(() => initMap(locationsData), 100);
                return;
            }

            // Initialize map
            map = L.map('map').setView(mapCenter, 12);

            // Add OpenStreetMap tile layer
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            // Get employee locations from PHP or from passed data
            const locations = locationsData || @json($this->getEmployeeLocations());
            console.log('Locations data:', locations);

            // Add markers for each location
            let validLocations = 0;
            locations.forEach(function(location) {
                if (location.latitude && location.longitude) {
                    validLocations++;

                    let bgColor = '#3B82F6'; // default blue (Timed In)
                    if (location.status === 'completed') bgColor = '#22C55E'; // green
                    else if (location.status === 'pending') bgColor = '#EAB308'; // yellow
                    else if (location.time_in && !location.time_out) bgColor = '#3B82F6'; // blue (Timed In)

                    let iconHtml;
                    if (location.image_path) {
                        iconHtml = `<img src="/storage/${location.image_path}" style="width: 48px; height: 48px; object-fit: cover; object-position: center; border-radius: 50%;" />`;
                    } else {
                        // Use initials
                        const initials = location.name.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();
                        iconHtml = `<span style="color: white; font-weight: bold; font-size: 16px;">${initials}</span>`;
                    }

                    const customIcon = L.divIcon({
                        className: 'custom-marker',
                        html: `
                        <div style="
                            width: 48px;
                            height: 48px;
                            border-radius: 50%;
                            background: ${bgColor};
                            border: 3px solid ${bgColor};
                            box-shadow: 0 2px 8px rgba(0,0,0,0.3);
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            overflow: hidden;
                        ">
                            ${iconHtml}
                        </div>
                    `,
                        iconSize: [48, 48],
                        iconAnchor: [24, 24],
                        popupAnchor: [0, -24]
                    });

                    const marker = L.marker([location.latitude, location.longitude], {
                        icon: customIcon
                    }).addTo(map);

                    // Add popup with basic info
                    const popupContent = `
                    <div style="min-width: 180px;">
                        <strong>${location.name}</strong><br/>
                        <span style="font-size: 12px; color: #666;">${location.position}</span><br/>
                        <span style="font-size: 12px; color: #666;">${location.office}</span><br/>
                        <div style="margin-top: 4px; font-size: 11px;">
                            <span style="color: #3B82F6;">In: ${location.time_in || 'N/A'}</span> |
                            <span style="color: #22C55E;">Out: ${location.time_out || 'N/A'}</span>
                        </div>
                        <span style="font-size: 11px; color: ${bgColor}; font-weight: bold;">${location.status === 'completed' ? 'Completed' : location.status === 'pending' ? 'Pending' : 'Timed In'}</span>
                    </div>
                `;

                    marker.bindPopup(popupContent);

                    // Trigger Livewire on marker click
                    marker.on('click', function() {
                        window.Livewire.dispatch('selectTimelog', {
                            timelogId: location.id
                        });
                    });
                }
            });

            // Hide loading state
            const loading = document.getElementById('loading');
            if (loading) {
                loading.style.display = 'none';
            }

            // Fit map to markers if there are any
            if (validLocations > 0) {
                const group = new L.featureGroup();
                locations.forEach(function(location) {
                    if (location.latitude && location.longitude) {
                        group.addLayer(L.marker([location.latitude, location.longitude]));
                    }
                });
                if (validLocations > 1) {
                    map.fitBounds(group.getBounds().pad(0.1));
                }
            }
        }

        // Initialize map
        initMap();

        // Reinitialize on Livewire:init
        document.addEventListener('livewire:init', function() {
            initMap();
        });

        // Handle date change - get fresh data from server
        Livewire.on('mapUpdated', (locations) => {
            console.log('mapUpdated event received:', locations);
            // Handle the array-wrapped object format from Livewire event
            let locationsData = locations;
            if (locations.length === 1 && locations[0].locations) {
                locationsData = locations[0].locations;
            } else if (locations.locations) {
                locationsData = locations.locations;
            }
            initMap(locationsData);
        });
    </script>
@endscript
