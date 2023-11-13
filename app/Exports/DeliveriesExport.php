<?php

namespace App\Exports;

use App\Models\Customer;
use App\Models\Delivery;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\AfterSheet;

class DeliveriesExport implements FromCollection, WithHeadings, WithMapping, WithEvents, WithColumnWidths
{

    public function collection()
    {
        $data = Delivery::orderBy('id', 'desc')->where('deleted_at', null)->with(['customer','delivery_relationships']);
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
                $data =  $data->where('code', 'like', '%' . $_GET['keyword'] . '%');
            }
            if (!empty($_GET['code'])) {
                $data =  $data->where('products', 'like', '%' . $_GET['code'] . '%');
            }
            if (!empty($_GET['customer_id'])) {
                $data =  $data->where('customer_id', $_GET['customer_id']);
            }
            if (!empty($_GET['status'])) {
                $data =  $data->where('status', $_GET['status']);
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
        $data = $data->get();
        $tmp = [];
        if (!empty($data)) {
            foreach ($data as $item) {
                if(!empty($item->advanced)){
                    //hệ thống mới
                    $products = $item->delivery_relationships;
                    if(!empty($products) && count($products) > 0){
                        foreach ($products as $key => $val) {
                            $tmp[] = [
                                'date' => $item->created_at,
                                'bill' => $item->code,
                                'customer_code' => !empty($item->customer->code) ? $item->customer->code : '',
                                'code' => $val->code,
                                'weight' =>  $val->weight,
                                'note' =>  $val->note,
                                'price' => $item->price,
                            ];
                        }
                    }
                }else{
                    $products =  json_decode($item->products, TRUE);
                    if (!empty($products) && count($products) > 0) {
                        if (!empty($products['code'])) {
                            foreach ($products['code'] as $key => $val) {
                                if (!empty($val)) {
                                    $tmp[] = [
                                        'date' => $item->created_at,
                                        'bill' => $item->code,
                                        'customer_code' => !empty($item->customer->code) ? $item->customer->code : '',
                                        'code' => $val,
                                        'weight' => !empty($products['weight'][$key]) ? $products['weight'][$key] : '',
                                        'note' => !empty($products['note'][$key]) ? $products['note'][$key] : '',
                                        'price' => $item->price,
                                    ];
                                }
                            }
                        }
                    }
                }
            }
        }
        $tmp = collect($tmp);
        return $tmp;
    }
    public function headings(): array
    {
        return [
            'Ngày giao',
            'Mã bill',
            'Mã khách hàng',
            'Cân nặng',
            'Mã vận dơn',

            'Đơn giá',
            'Thành tiền',
            'Ghi chú',
        ];
    }
    public function map($row): array
    {
        return [
            $row['date'],
            $row['bill'],
            $row['customer_code'],
            $row['weight'],
            $row['code'],
            $row['price'],
            (float)$row['price'] * (float)$row['weight'],
            $row['note'],
        ];
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function (AfterSheet $event) {
                $cellRange = 'A1:H1'; // All headers
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
            'C' => 10,
            'D' => 10,
            'E' => 20,
            'F' => 20,
            'G' => 20,
            'H' => 20,
        ];
    }
}
