<?php

namespace App\Exports;

use App\Models\Customer;
use App\Models\Delivery;
use App\Models\Warehouse;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\AfterSheet;
use Auth;

class WarehouseFrontendExport implements FromCollection, WithHeadings, WithMapping, WithEvents, WithColumnWidths
{

    public function collection()
    {
        $data = Warehouse::orderBy('id', 'desc')
            ->where('customer_id', Auth::guard('customer')->user()->id)
            ->with(['packaging_relationships' => function ($query) {
                // $query->select('packaging_relationships.packaging_id', 'packaging_relationships.warehouse_id', 'packagings.id', 'packagings.code')
                //     ->join('packagings', 'packagings.id', '=', 'packaging_relationships.packaging_id')
                //     ->join('packaging_v_n_s', 'packagings.code', '=', 'packaging_v_n_s.packaging_code')
                //     ->where('packaging_relationships.deleted_at', null);
                $query->with(['packagings' => function ($q) {
                    $q->with('packaging_v_n_s');
                }]);
            }])->with(['delivery_relationships' => function ($query) {
                $query->select('deliveries.code as deliveries_code', 'delivery_relationships.*')->join('deliveries', 'deliveries.id', '=', 'delivery_relationships.delivery_id');
            }]);
        if (!empty($_GET['keyword'])) {
            $keyword = $_GET['keyword'];
            $data =  $data->where(function ($query) use ($keyword) {
                $query->where('fullname', 'like', '%' . $keyword . '%')
                    ->orWhere('phone', 'like', '%' . $keyword . '%')
                    ->orWhere('name_cn', 'like', '%' . $keyword . '%')
                    ->orWhere('name_vn', 'like', '%' . $keyword . '%')
                    ->orWhere('code_cn', 'like', '%' . $keyword . '%')
                    ->orWhere('code_vn', 'like', '%' . $keyword . '%');
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
        $data = $data->get();
        return $data;
    }
    public function headings(): array
    {
        return [
            'NGÀY 日期',
            'MÃ VẬN ĐƠN 运单号',
            'MÃ VN',
            'CÂN NẶNG(KG) 重量',
            'Số lượng kiện 数量件',
            'Số lượng sản phẩm 数量产品	',
            'TÊN SẢN PHẨM 品名	',
            'TÊN SẢN PHẨM VN',
            'GIÁ',
            'MÃ BAO 包号',
            'Trạng thái',
            'MÃ giao hàng',
        ];
    }
    public function map($row): array
    {
        if (!empty($row->packaging_relationships) && !empty($row->packaging_relationships->packagings)) {
            $mabao  = $row->packaging_relationships->packagings->code;
        }
        if (!empty($row->packaging_relationships) && !empty($row->packaging_relationships->packagings) && !empty($row->packaging_relationships->packagings->packaging_v_n_s)) {
            $status  = 'Đã về VN';
        } else {
            $status  = 'Nhập kho TQ';
        }
        if (!empty($row->delivery_relationships)) {
            $deliveries_code  = $row->delivery_relationships->deliveries_code;
        }
        return [
            $row->date,
            $row->code_cn,
            $row->code_vn,
            $row->weight,
            $row->quantity_kien,
            $row->quantity,
            $row->name_cn,
            $row->name_vn,
            $row->price,
            !empty($mabao) ? $mabao : '',
            !empty($status) ? $status : '',
            !empty($deliveries_code) ? $deliveries_code : '',
        ];
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function (AfterSheet $event) {
                $cellRange = 'A1:L1'; // All headers
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
            'I' => 20,
            'J' => 20,
            'K' => 20,
            'L' => 20,
        ];
    }
}
