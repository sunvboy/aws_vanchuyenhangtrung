<?php

namespace App\Exports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\AfterSheet;

class CustomerExport implements FromCollection, WithHeadings, WithMapping, WithEvents, WithColumnWidths
{

    public function collection()
    {
        return Customer::where('deleted_at',null)->select('id', 'name', 'email', 'phone', 'created_at', 'code', 'address')->with('orders')->get();
    }
    public function headings(): array
    {
        return [
            'id',
            'Mã khách hàng',
            'Họ và tên',
            // 'Email',
            'Điện thoại',
            'Địa chỉ',
            // 'Mua hàng',
            'Ngày đăng kí'
        ];
    }
    public function map($row): array
    {
        // $orders = '';
        // if (count($row->orders) > 0) {
        //     $orders = count($row->orders) . ' đơn hàng';
        // } else {
        //     $orders = 'Chưa mua hàng';
        // }
        return [
            $row->id,
            $row->code,
            $row->name,
            // $row->email,
            $row->phone,
            $row->address,
            // $orders,
            $row->created_at,
        ];
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function (AfterSheet $event) {
                $cellRange = 'A1:F1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
                $event->sheet->getDelegate()->getStyle($cellRange)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FF17a2b8');
                $event->sheet->setAutoFilter($cellRange);
            },
        ];
    }
    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 25,
            'C' => 45,
            'D' => 25,
            'E' => 50,
            'F' => 25,
        ];
    }
}
