<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Pendapatan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            margin: 0;
            color: #333;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .summary-cards {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .summary-card {
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 8px;
            width: 30%;
        }
        .summary-card h3 {
            margin: 0;
            color: #666;
            font-size: 14px;
        }
        .summary-card .value {
            font-size: 20px;
            font-weight: bold;
            color: #333;
            margin: 10px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
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
        .section-title {
            color: #333;
            margin: 20px 0 10px;
            font-size: 18px;
        }
        .status-badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
        }
        .status-paid {
            background-color: #dcfce7;
            color: #166534;
        }
        .status-pending {
            background-color: #fef9c3;
            color: #854d0e;
        }
        .status-deposit {
            background-color: #dbeafe;
            color: #1e40af;
        }
        .status-checked_in, .status-checked_out {
            background-color: #dbeafe;
            color: #1e40af;
        }
        .status-confirmed {
            background-color: #dcfce7;
            color: #166534;
        }
        .status-cancelled, .status-expired {
            background-color: #fee2e2;
            color: #991b1b;
        }
        .room-list {
            margin: 0;
            padding: 0;
            list-style: none;
            max-height: 85px;
            overflow: hidden;
        }
        .room-list li {
            padding: 4px 6px;
            margin-bottom: 4px;
            background-color: #f5f5f5;
            border-radius: 4px;
            font-size: 11px;
        }
        .room-list li:last-child {
            margin-bottom: 0;
        }
        .room-list-overflow {
            color: #666;
            font-size: 11px;
            font-style: italic;
            margin-top: 2px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Pendapatan Hotel</h1>
        <p>Periode: {{ now()->format('F Y') }}</p>
        <p>Dicetak pada: {{ now()->format('d F Y H:i') }}</p>
    </div>

    <div class="summary-cards">
        <div class="summary-card">
            <h3>Total Pendapatan Bulan Ini</h3>
            <div class="value">
                Rp {{ number_format($monthlyRevenue->where('month', now()->month)->first()?->revenue ?? 0, 0, ',', '.') }}
            </div>
        </div>
        <div class="summary-card">
            <h3>Rata-rata Harga per Malam</h3>
            <div class="value">
                Rp {{ number_format($averageRate, 0, ',', '.') }}
            </div>
        </div>
        <div class="summary-card">
            <h3>Tingkat Hunian</h3>
            <div class="value">
                {{ number_format($occupancyRate, 1) }}%
            </div>
        </div>
    </div>

    <h2 class="section-title">Pemesanan per Bulan</h2>
    <table>
        <thead>
            <tr>
                <th>Bulan</th>
                <th>Total Pemesanan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($monthlyBookings->sortByDesc('month') as $booking)
                <tr>
                    <td>{{ \Carbon\Carbon::create()->month($booking->month)->format('F Y') }}</td>
                    <td>{{ number_format($booking->total) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2 class="section-title">Pendapatan per Bulan</h2>
    <table>
        <thead>
            <tr>
                <th>Bulan</th>
                <th>Total Pendapatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($monthlyRevenue->sortByDesc('month') as $revenue)
                <tr>
                    <td>{{ \Carbon\Carbon::create()->month($revenue->month)->format('F Y') }}</td>
                    <td>Rp {{ number_format($revenue->revenue, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2 class="section-title">Detail Pesanan</h2>
    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Nama Tamu</th>
                <th>Kamar</th>
                <th>Check-in</th>
                <th>Check-out</th>
                <th>Total</th>
                <th>Status</th>
                <th>Pembayaran</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bookingDetails as $detail)
                <tr>
                    <td>{{ $detail['tanggal']->format('d M Y H:i') }}</td>
                    <td>{{ $detail['guest_name'] }}</td>
                    <td>
                        @php
                            $rooms = explode(', ', $detail['rooms']);
                            $visibleRooms = array_slice($rooms, 0, 2);
                            $remainingCount = count($rooms) - 2;
                        @endphp
                        <ul class="room-list">
                            @foreach($visibleRooms as $room)
                                <li>{{ $room }}</li>
                            @endforeach
                        </ul>
                        @if($remainingCount > 0)
                            <div class="room-list-overflow">+{{ $remainingCount }} more rooms...</div>
                        @endif
                    </td>
                    <td>{{ $detail['check_in'] }}</td>
                    <td>{{ $detail['check_out'] }}</td>
                    <td>Rp {{ number_format($detail['total_price'], 0, ',', '.') }}</td>
                    <td>
                        <span class="status-badge status-{{ $detail['status'] }}">
                            {{ ucfirst($detail['status']) }}
                        </span>
                    </td>
                    <td>
                        <span class="status-badge status-{{ $detail['payment_status'] }}">
                            {{ ucfirst($detail['payment_status']) }}
                        </span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html> 