<?php

namespace App\Exports;

use App\Models\Line;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LinesExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Line::with('customer')
            ->get()
            ->map(function ($line) {
                return [
                    'رقم الهاتف'     => $line->phone_number,
                    'اسم العميل'     => $line->customer->full_name ?? '',
                    'نوع الخط'       => $line->line_type,
                    'المزود'         => $line->provider,
                    'النظام'         => $line->plan->name ?? '',
                    'تاريخ الدفع'    => $line->payment_date,
                ];
            });
    }

    public function headings(): array
    {
        return ['رقم الهاتف', 'اسم العميل', 'نوع الخط', 'المزود', 'النظام', 'تاريخ الدفع'];
    }
}
