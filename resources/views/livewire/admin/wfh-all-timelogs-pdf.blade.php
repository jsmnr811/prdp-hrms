<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>WFH Timelogs Report - All Employees</title>
    <style>
        @page {
            size: A4;
            margin: 0.5in;
        }

        html {
            width: 210mm;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.6;
            width: auto;
            min-height: 277mm;
            margin: 0;
            padding: 0;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
        }

        .header h1 {
            font-size: 20px;
            margin: 0 0 5px 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .header p {
            font-size: 12px;
            color: #666;
            margin: 0;
        }

        .info {
            margin-bottom: 20px;
        }

        .info p {
            margin: 2px 0;
            font-size: 11px;
        }

        .date-header {
            background-color: #f5f5f5;
            font-weight: bold;
            padding: 10px 12px;
            margin-top: 20px;
            margin-bottom: 8px;
            border-left: 4px solid #333;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 12px 10px;
            text-align: left;
        }

        th {
            background-color: #fff;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10px;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #333;
        }

        .status-pending {
            color: #b45309;
            font-weight: 600;
        }

        .status-completed {
            color: #3c5d32;
            font-weight: 600;
        }

        .accomplishments-row td {
            padding: 0;
            border: none;
        }

        .accomplishments-box {
            border-bottom: 1px solid #333;
            border-top: none;
            padding: 10px;
            font-size: 11px;
            line-height: 1.5;
            color: #333;
            overflow-wrap: break-word;
        }

        .accomplishments-box strong {
            display: block;
            margin-bottom: 4px;
            color: #555;
        }

        .accomplishments-box .content {
            white-space: pre-line;
        }

        .footer {
            margin-top: 15px;
            text-align: right;
            font-size: 10px;
            color: #666;
        }

        .watermark {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0.2;
            z-index: -1;
            background-image: url('{{ public_path('assets/images/Scale-Up Logo.png') }}');
            background-repeat: repeat;
            background-size: .75in .75in;
            background-position: center;
            filter: grayscale(100%);
            pointer-events: none;
        }
    </style>
</head>

<body>
    {{-- Watermark --}}
    <div class="watermark"></div>

    {{-- Header with Letterhead --}}
    <img src="{{ public_path('assets/images/Scale-Up Letterhead_Colored.png') }}" alt="Letterhead"
        style="width: 60%; margin-bottom: 10px; display: block; margin-left: auto; margin-right: auto;">

    <div class="info">
        <p><strong>Date Range:</strong> {{ $dateRange }}</p>
        <p><strong>Generated:</strong> {{ \Carbon\Carbon::now()->format('M d, Y h:i A') }}</p>
    </div>

    @php
        // Group timelogs by date and sort by date ascending
        $groupedTimelogs = $timelogs
            ->groupBy(function ($timelog) {
                return \Carbon\Carbon::parse($timelog->date)->format('Y-m-d');
            })
            ->sortBy(function ($timelogs, $date) {
                return $date;
            });
    @endphp

    @forelse($groupedTimelogs as $date => $dayTimelogs)
        <div class="date-header">
            {{ \Carbon\Carbon::parse($date)->format('l, F d, Y') }}
        </div>

        <table>
            <thead>
                <tr>
                    <th>Employee</th>
                    <th>Position</th>
                    <th>Time In</th>
                    <th>Time Out</th>
                    <th>Hours</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($dayTimelogs as $timelog)
                    <tr>
                        <td>{{ $timelog->user->name ?? 'N/A' }}</td>
                        <td>{{ $timelog->user->employee->position->name ?? '-' }}</td>
                        <td>{{ $timelog->time_in ? \Carbon\Carbon::parse($timelog->time_in)->format('h:i A') : '-' }}
                        </td>
                        <td>{{ $timelog->time_out ? \Carbon\Carbon::parse($timelog->time_out)->format('h:i A') : '-' }}
                        </td>
                        <td>{{ $timelog->total_hours ? number_format($timelog->total_hours, 2) . ' hrs' : '-' }}</td>
                        <td class="status-{{ $timelog->status }}">
                            {{ ucfirst($timelog->status) }}
                        </td>
                    </tr>
                    @if ($timelog->accomplishments)
                        <tr class="accomplishments-row">
                            <td colspan="6" style="padding: 0; border-left: 1px solid #333; border-right: 1px solid #333; page-break-inside: avoid;">
                                <div class="accomplishments-box">
                                    <strong>Accomplishments:</strong>
                                    <div class="content">{{ $timelog->accomplishments }}</div>
                                </div>
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    @empty
        <p style="text-align: center; padding: 20px;">No timelogs found</p>
    @endforelse

    <div class="footer">
        <p>Total Records: {{ $timelogs->count() }}</p>
    </div>

    {{-- Signature Section --}}
    <div style="margin-top: 40px; page-break-inside: avoid;">
        <table style="width: 100%; border: none;">
            <tr>
                <td style="width: 50%; border: none; padding-top: 40px;">
                    <p style="border-top: 1px solid #000; padding-top: 5px; width: 200px; margin-bottom: 5px;">Exported
                        By</p>
                    <p style="font-size: 11px;">HR Personnel</p>
                </td>
                <td style="width: 50%; border: none; padding-top: 40px;">
                    <p style="border-top: 1px solid #000; padding-top: 5px; width: 200px; margin-bottom: 5px;">
                        Authorized Personnel</p>
                    <p style="font-size: 11px;">HR/Supervisor</p>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
