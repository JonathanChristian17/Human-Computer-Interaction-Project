<?php

namespace App\Exports;

use App\Models\Booking;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class BookingExport implements FromCollection, WithHeadings, WithMapping
{
    protected $bookings;
    protected $type;

    public function __construct($bookings, $type = 'daily')
    {
        $this->bookings = $bookings;
        $this->type = $type;
    }

    public function collection()
    {
        return $this->bookings;
    }

    public function headings(): array
    {
        return [
            'Booking ID',
            'Customer Name',
            'Email',
            'Phone',
            'Rooms',
            'Check In',
            'Check Out',
            'Total Price',
            'Status',
            'Payment Status',
            'Managed By'
        ];
    }

    public function map($booking): array
    {
        $rooms = $booking->rooms->map(function($room) {
            return "Room {$room->room_number} ({$room->type})";
        })->join(', ');

        return [
            $booking->id,
            $booking->full_name,
            $booking->email,
            $booking->phone,
            $rooms,
            $booking->check_in_date->format('d/m/Y'),
            $booking->check_out_date->format('d/m/Y'),
            'Rp ' . number_format($booking->total_price, 0, ',', '.'),
            ucfirst($booking->status),
            ucfirst($booking->payment_status),
            $booking->receptionist ? $booking->receptionist->name : 'N/A'
        ];
    }
} 