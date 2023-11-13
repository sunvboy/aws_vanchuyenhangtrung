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

class DeliveriesFrontendExport implements FromCollection, WithHeadings, WithMapping, WithEvents, WithColumnWidths
{

    public function collection()
    {
        $tmp = [];
        $data = [];
        if (!empty($_GET['code'])) {
            $data = Delivery::orderBy('id', 'desc')->where('deleted_at', null)->with(['customer','delivery_relationships']);
            $data =  $data->where('products', 'like', '%' . $_GET['code'] . '%');
            $data = $data->get();
        }
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
            'Ghi chú',
            'Đơn giá',
            'Thành tiền',
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
