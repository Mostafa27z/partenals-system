<?php

namespace App\Exports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\FromCollection; // تأكد من استيراد هذه الواجهة
use Maatwebsite\Excel\Concerns\WithHeadings; // لو تريد رؤوس الأعمدة

class CustomersExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Customer::all();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Full Name',
            'Status',
            'Offer Name',
            'Branch Name',
            'Employee Name',
            'GCode',
            'Phone Number',
            'Provider',
            'National ID',
            'Created At',
            'Updated At',
        ];
    }
}
