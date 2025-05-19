<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            font-size: 12px;
        }
        h1 {
            text-align: center;
            color: #1a1a1a;
            margin-bottom: 20px;
            font-size: 18px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            page-break-inside: auto;
        }
        tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
            font-size: 11px;
            word-wrap: break-word;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .status {
            padding: 2px 4px;
            border-radius: 3px;
            font-size: 11px;
            display: inline-block;
        }
        .status-confirmed { 
            background-color: #d1fae5; 
            color: #065f46; 
        }
        .status-pending { 
            background-color: #fef3c7; 
            color: #92400e; 
        }
        .status-cancelled { 
            background-color: #fee2e2; 
            color: #991b1b; 
        }
        .status-checked_in { 
            background-color: #dbeafe; 
            color: #1e40af; 
        }
        .status-checked_out { 
            background-color: #e5e7eb; 
            color: #374151; 
        }
        .customer-info {
            margin: 0;
            line-height: 1.3;
        }
        .customer-email {
            color: #666;
            font-size: 10px;
        }
        .room-info {
            margin: 0;
            line-height: 1.3;
        }
        .footer {
            position: fixed;
            bottom: 20px;
            right: 20px;
            font-size: 10px;
            color: #666;
        }
        @page {
            margin: 20px;
        }
    </style>
</head>
<body>
    <h1>{{ $title }}</h1>

    <table>
        <thead>
            <tr>
                <th style="width: 5%">ID</th>
                <th style="width: 20%">Customer</th>
                <th style="width: 20%">Rooms</th>
                <th style="width: 10%">Check In</th>
                <th style="width: 10%">Check Out</th>
                <th style="width: 12%">Total Price</th>
                <th style="width: 13%">Status</th>
                <th style="width: 10%">Managed By</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bookings as $booking)
                <tr>
                    <td>#{{ $booking->id }}</td>
                    <td>
                        <p class="customer-info">{{ $booking->full_name }}</p>
                        <p class="customer-email">{{ $booking->email }}</p>
                    </td>
                    <td>
                        @foreach($booking->rooms as $room)
                            <p class="room-info">Room {{ $room->room_number }} ({{ $room->type }})</p>
                        @endforeach
                    </td>
                    <td>{{ $booking->check_in_date->format('d/m/Y') }}</td>
                    <td>{{ $booking->check_out_date->format('d/m/Y') }}</td>
                    <td>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                    <td>
                        <span class="status status-{{ $booking->status }}">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </td>
                    <td>{{ $booking->receptionist ? $booking->receptionist->name : 'N/A' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Generated on: {{ now()->format('d F Y H:i:s') }}
    </div>
</body>
</html> 