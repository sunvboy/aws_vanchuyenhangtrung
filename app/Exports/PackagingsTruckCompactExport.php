<?php

namespace App\Exports;

use App\Models\Customer;
use App\Models\PackagingRelationships;
use App\Models\PackagingTruck;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\AfterSheet;

class PackagingsTruckCompactExport implements FromCollection, WithHeadings, WithMapping, WithEvents, WithColumnWidths
{

    public function collection()
    {

        $data = \App\Models\PackagingTruck::where('deleted_at', null)->orderBy('id', 'desc');
        $tmp = [];
        if (!empty($_GET['ids'])) {
            $idsEx = explode(',', $_GET['ids']);
            if (!empty($idsEx)) {
                foreach ($idsEx as $item) {
                    if (!empty($item)) {
                        $tmp[] = $item;
                    }
                }
            }
            $data =  $data->whereIn('id', $tmp);
        } else {
            if (!empty($_GET['keyword'])) {
                $keyword = $_GET['keyword'];
                $data =  $data->where(function ($query) use ($keyword) {
                    $query->where('packaging_code', 'like', '%' . $keyword . '%');
                });
            }
            $date_start = !empty($_GET['date_start']) ? $_GET['date_start'] : '';
            $date_end = !empty($_GET['date_end']) ? $_GET['date_end'] : '';

            if (isset($date_start) && !empty($date_start) && empty($date_end)) {
                $data =  $data->where('created_at', '>=', $date_start . ' 00:00:00')->where('created_at', '<=', $date_start . ' 23:59:59');
            }
            if (isset($date_end) && !empty($date_end) && empty($date_start)) {
                $data =  $data->where('created_at', '>=', $date_end . ' 00:00:00')->where('created_at', '<=', $date_end . ' 23:59:59');
            }
            if (isset($date_end) && !empty($date_end) && isset($date_start) && !empty($date_start)) {
                if ($date_end == $date_start) {
                    $data =  $data->where('created_at', '>=', $date_start . ' 00:00:00');
                } else {
                    $data =  $data->where('created_at', '>=', $date_start . ' 00:00:00')->where('created_at', '<=', $date_end . ' 23:59:59');
                }
            }
        }
        $data = $data->with('packagings');
        $data = $data->get();
        return $data;
    }
    public function headings(): array
    {
        return [
            'Ngày 日期',
            'Cân nặng(kg) 重量',
            'Mã bao 包号',
            'Mã khách 客户码',
            'Tên khách hàng 名字',
        ];
    }
    public function map($row): array
    {

        return [
            $row->created_at,
            $row->packagings->value_weight,
            $row->packagings->code,
            !empty($row->packagings->customer) ? $row->packagings->customer->code : '',
            !empty($row->packagings->customer) ? $row->packagings->customer->name : '',
        ];
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function (AfterSheet $event) {
                $cellRange = 'A1:E1'; // All headers
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
            'A' => 25,
            'B' => 10,
            'C' => 25,
            'D' => 25,
            'E' => 20,
        ];
    }
}
