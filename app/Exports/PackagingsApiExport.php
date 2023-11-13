<?php

namespace App\Exports;

use App\Models\Customer;
use App\Models\PackagingRelationships;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\AfterSheet;
use Auth;

class PackagingsApiExport implements FromCollection, WithHeadings, WithMapping, WithEvents, WithColumnWidths
{

    public function collection()
    {

        $data = \App\Models\Packaging::where('deleted_at', null)->where('customer_id', Auth::guard('api')->user()->id)->with(
            ['packaging_relationships' => function ($query) {
                $query->with('warehouses_vietnam');
            }]
        )->with('packaging_v_n_s')->orderBy('id', 'desc');
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
                    $query->where('code', 'like', '%' . $keyword . '%')
                        ->orWhere('value_weight', 'like', '%' . $keyword . '%');
                });
            }
            if (!empty($_GET['customer_id'])) {
                $data =  $data->where('customer_id', $_GET['customer_id']);
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
        $data = $data->groupBy('code')->get();
        $result = [];
        if (!empty($data) && count($data) > 0) {
            foreach ($data as $key => $item) {
                $products = $item->packaging_relationships;
                $status = !empty($item->packaging_v_n_s) ? 'Đã về VN' : 'Nhập kho TQ';
                if (!empty($products) && count($products) > 0) {
                    foreach ($products as $k => $v) {
                        if (!empty($v->code)) {
                            $result[] = [
                                'created_at' => $item->created_at,
                                'code' => $item->code,
                                'code_cn' => !empty($v->warehouses_vietnam) ? $v->warehouses_vietnam->code_cn : '',
                                'code_vn' => !empty($v->warehouses_vietnam) ? $v->warehouses_vietnam->code_vn : '',
                                'weight' => $v->weight,
                                'quantity' => !empty($v->warehouses_vietnam) ? $v->warehouses_vietnam->quantity : '',
                                'name_cn' => !empty($v->warehouses_vietnam) ? $v->warehouses_vietnam->name_cn : '',
                                'name_vn' => !empty($v->warehouses_vietnam) ? $v->warehouses_vietnam->name_vn : '',
                                'price' => !empty($v->warehouses_vietnam) ? $v->warehouses_vietnam->price : '',
                                'customer_code' => !empty($item->customer) ? $item->customer->code : '',
                                'customer_name' => !empty($item->customer) ? $item->customer->name : '',
                                'fullname' => !empty($v->warehouses_vietnam) ? $v->warehouses_vietnam->fullname : '',
                                'address' => !empty($v->warehouses_vietnam) ? $v->warehouses_vietnam->address : '',
                                'phone' => !empty($v->warehouses_vietnam) ? $v->warehouses_vietnam->phone : '',
                                'phone' => !empty($v->warehouses_vietnam) ? $v->warehouses_vietnam->phone : '',
                                'status' => $status,
                            ];
                        }
                    }
                }
            }
        }
        $result = collect($result);
        return $result;
    }
    public function headings(): array
    {
        return [
            'Ngày 日期',
            'Mã bao 包号',
            'Mã vận đơn 运单号',
            'Mã VN',
            'Cân nặng(kg) 重量',
            'Số lượng',
            'Tên sản phẩm 品名',
            'Tên sản phẩm VN',
            'Giá',
            'Mã khách 客户码',
            'Tên khách hàng 名字',
            'Tên khách VN',
            'Địa chỉ',
            'Số điện thoại',
            'Trạng thái',
        ];
    }
    public function map($row): array
    {
        return $row;
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function (AfterSheet $event) {
                $cellRange = 'A1:O1'; // All headers
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
            'E' => 10,
            'F' => 10,
            'G' => 25,
            'H' => 25,
            'I' => 10,
            'J' => 25,
            'K' => 25,
            'L' => 20,
            'M' => 50,
            'N' => 20,
            'O' => 20,
        ];
    }
}
