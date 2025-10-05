<?php

namespace App\Exports;

use App\Models\Students;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StudentsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Students::select(
            'id',
            'name',
            'email',
            'phone',
            'gender',
            'enrollment_date',
            'expiry_date',
            'total_amount',
            'paid_amount',
            'payment_status',
            'hall'
        )->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Email',
            'Phone',
            'Gender',
            'Enrollment Date',
            'Expiry Date',
            'Total Amount',
            'Paid Amount',
            'Payment Status',
            'Hall'
        ];
    }
}
