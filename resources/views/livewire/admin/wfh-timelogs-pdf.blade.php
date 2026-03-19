<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>WFH Timelogs Report</title>
    <style>
        @page {
            size: A4;
            margin: 0.5in;
            /* 12.7mm all around */
        }

        html {
            width: 210mm;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.6;
            width: auto;
            /* full width inside page margins */
            min-height: 277mm;
            margin: 0;
            /* body starts at the page margin, no extra centering */
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
        <table style="width: 100%; border: none; border-collapse: collapse;">
            <tr>
                <td style="border: none; padding: 0; vertical-align: top;">
                    <p style="margin: 0; line-height: 1.2;"><strong>Employee:</strong> {{ $employeeName ?? 'N/A' }}</p>
                    <p style="margin: 0; line-height: 1.2;"><strong>Position:</strong> {{ $employeePosition ?? 'N/A' }}
                    </p>
                    <p style="margin: 0; line-height: 1.2;"><strong>Date Range:</strong> {{ $dateRange }}</p>
                </td>
                <td style="border: none; padding: 0; vertical-align: top;">
                    <p style="margin: 0; line-height: 1.2;"><strong>Office:</strong> {{ $employeeOffice ?? 'N/A' }}</p>
                    <p style="margin: 0; line-height: 1.2;"><strong>Unit:</strong> {{ $employeeUnit ?? 'N/A' }}</p>
                    <p style="margin: 0; line-height: 1.2;"><strong>Generated:</strong>
                        {{ \Carbon\Carbon::now()->format('M d, Y h:i A') }}</p>
                </td>
            </tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Time In</th>
                <th>Time Out</th>
                <th>Hours</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($timelogs as $timelog)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($timelog->date)->format('M d, Y') }}</td>
                    <td>{{ $timelog->time_in ? \Carbon\Carbon::parse($timelog->time_in)->format('h:i A') : '-' }}</td>
                    <td>{{ $timelog->time_out ? \Carbon\Carbon::parse($timelog->time_out)->format('h:i A') : '-' }}
                    </td>
                    <td>{{ $timelog->total_hours ? number_format($timelog->total_hours, 2) . ' hrs' : '-' }}</td>
                    <td class="status-{{ $timelog->status }}">
                        {{ ucfirst($timelog->status) }}
                    </td>
                </tr>
                @if ($timelog->accomplishments)
                    <tr class="accomplishments-row">
                        <td colspan="5" style="padding: 0; border-left: 1px solid #333; border-right: 1px solid #333; page-break-inside: avoid;">
                            <div class="accomplishments-box">
                                <strong>Accomplishments:</strong>
                                <div class="content">{{ ltrim(trim(preg_replace('/\t+/', '    ', $timelog->accomplishments))) }}</div>
                            </div>
                        </td>
                    </tr>
                @endif
            @empty
                <tr>
                    <td colspan="5" style="text-align: center;">No timelogs found</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Total Records: {{ $timelogs->count() }}</p>
    </div>

    {{-- Signature Section --}}
    <div style="margin-top: 40px; page-break-inside: avoid;">
        <table style="width: 100%; border: none; border-collapse: collapse;">
            <tr>
                <td style="width: 50%; border: none; padding-top: 10px; vertical-align: top;">
                    <p style="border-top: 1px solid #000; padding-top: 3px; width: 200px; margin: 0; line-height: 1.1;">
                        {{ $employeeName ?? 'Employee Signature' }}
                    </p>
                    <p style="font-size: 11px; margin: 0; line-height: 1.1;">
                        {{ $employeePosition ?? 'Position' }}
                    </p>
                </td>
                <td style="width: 50%; border: none; padding-top: 10px; vertical-align: top;">
                    <div style="margin-left: 60px;">
                        <p
                            style="border-top: 1px solid #000; padding-top: 3px; width: 200px; margin: 0; line-height: 1.1;">
                            Authorized Personnel
                        </p>
                        <p style="font-size: 11px; margin: 0; line-height: 1.1;">
                            <!-- Optional position -->
                        </p>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
