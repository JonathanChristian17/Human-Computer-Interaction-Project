<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $booking->id }}</title>
    <style>
        @page {
            margin: 0px;
        }
        body {
            margin: 0;
            font-family: 'Helvetica', sans-serif;
            font-size: 14px;
            line-height: 1.5;
            color: #333;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 40px;
        }
        .header {
            text-align: center;
            margin-bottom: 40px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .invoice-details {
            margin-bottom: 40px;
        }
        .invoice-details table {
            width: 100%;
        }
        .invoice-details td {
            padding: 5px 0;
        }
        .invoice-items {
            margin-bottom: 40px;
        }
        .invoice-items table {
            width: 100%;
            border-collapse: collapse;
        }
        .invoice-items th,
        .invoice-items td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .invoice-items th {
            background-color: #f5f5f5;
        }
        .total {
            text-align: right;
            margin-top: 20px;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            color: #666;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">{{ config('app.name') }}</div>
            <div>Invoice #{{ $booking->id }}</div>
            <div>{{ now()->format('F d, Y') }}</div>
        </div>

        <div class="invoice-details">
            <table>
                <tr>
                    <td width="50%">
                        <strong>Billed To:</strong><br>
                        {{ $booking->full_name }}<br>
                        {{ $booking->email }}<br>
                        {{ $booking->phone }}<br>
                        {{ $booking->billing_address }}<br>
                        {{ $booking->billing_city }}, {{ $booking->billing_province }}<br>
                        {{ $booking->billing_postal_code }}
                    </td>
                    <td width="50%" style="text-align: right;">
                        <strong>Hotel Address:</strong><br>
                        {{ config('app.name') }}<br>
                        123 Hotel Street<br>
                        City, Province 12345<br>
                        Indonesia<br>
                        Phone: (123) 456-7890<br>
                        Email: info@hotel.com
                    </td>
                </tr>
            </table>
        </div>

        <div class="invoice-items">
            <table>
                <thead>
                    <tr>
                        <th>Description</th>
                        <th>Nights</th>
                        <th>Rate</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($booking->rooms as $room)
                    <tr>
                        <td>
                            Room {{ $room->room_number }} ({{ $room->type }})<br>
                            <small>Check-in: {{ $booking->check_in_date->format('M d, Y') }}<br>
                            Check-out: {{ $booking->check_out_date->format('M d, Y') }}</small>
                        </td>
                        <td>{{ $booking->check_in_date->diffInDays($booking->check_out_date) }}</td>
                        <td>Rp{{ number_format($room->pivot->price_per_night, 0, ',', '.') }}</td>
                        <td>Rp{{ number_format($room->pivot->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="total">
                <table style="width: 300px; margin-left: auto;">
                    <tr>
                        <td><strong>Subtotal:</strong></td>
                        <td>Rp{{ number_format($booking->total_price, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Tax (10%):</strong></td>
                        <td>Rp{{ number_format($booking->total_price * 0.1, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Total:</strong></td>
                        <td>Rp{{ number_format($booking->total_price * 1.1, 0, ',', '.') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="footer">
            <p>Thank you for choosing {{ config('app.name') }}!</p>
            <p>For any inquiries, please contact us at info@hotel.com or call (123) 456-7890</p>
        </div>
    </div>
</body>
</html> 