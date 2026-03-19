<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>WFH Timelogs Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 18px;
            margin: 0;
        }
        .header p {
            font-size: 12px;
            color: #666;
            margin: 5px 0 0 0;
        }
        .info {
            margin-bottom: 20px;
        }
        .info p {
            margin: 3px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .status-pending {
            color: #d97706;
            font-weight: bold;
        }
        .status-completed {
            color: #2563eb;
            font-weight: bold;
        }
        .footer {
            margin-top: 20px;
            text-align: right;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>WFH Timelogs Report</h1>
        <p>Department of Agriculture - Human Resource Management System</p>
    </div>

    <div class="info">
        <p><strong>Date Range:</strong> {{ $dateRange }}</p>
        <p><strong>Status Filter:</strong> {{ $filterStatus ? ucfirst($filterStatus) : 'All' }}</p>
        <p><strong>Generated:</strong> {{ \Carbon\Carbon::now()->format('M d, Y h:i A') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Employee</th>
                <th>Date</th>
                <th>Time In</th>
                <th>Time Out</th>
                <th>Hours</th>
                <th>Status</th>
                {{-- <th>Accomplishments</th> --}}
            </tr>
        </thead>
        <tbody>
            @forelse($timelogs as $timelog)
                <tr>
                    <td>{{ $timelog->user->name ?? 'N/A' }}</td>
                    <td>{{ \Carbon\Carbon::parse($timelog->date)->format('M d, Y') }}</td>
                    <td>{{ $timelog->time_in ? \Carbon\Carbon::parse($timelog->time_in)->format('h:i A') : '-' }}</td>
                    <td>{{ $timelog->time_out ? \Carbon\Carbon::parse($timelog->time_out)->format('h:i A') : '-' }}</td>
                    <td>{{ $timelog->total_hours ? number_format($timelog->total_hours, 2) . ' hrs' : '-' }}</td>
                    <td class="status-{{ $timelog->status }}">
                        {{ ucfirst($timelog->status) }}
                    </td>
                    {{-- <td>{{ $timelog->accomplishments ?: '-' }}</td> --}}
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center;">No timelogs found</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Total Records: {{ $timelogs->count() }}</p>
    </div>
</body>
</html>
