<?php

namespace App\Exports;

use App\Models\Customer;
use App\Models\CustomerOrder;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\AfterSheet;

class CustomerOrderExport implements FromCollection, WithHeadings, WithMapping, WithEvents, WithColumnWidths
{

    public function collection()
    {
        $data = CustomerOrder::where('deleted_at', null)->orderBy('id', 'desc');
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
            if (!empty($_GET['customer_id'])) {
                $data =  $data->where('customer_id', $_GET['customer_id']);
            }
            if (!empty($_GET['status'])) {
                $data =  $data->where('status', $_GET['status']);
            }
            if (!empty($_GET['keyword'])) {
                $keyword = $_GET['keyword'];
                $data =  $data->where(function ($query) use ($keyword) {
                    $query->where('code', 'like', '%' . $keyword . '%')->orWhere('mavandon', 'like', '%' . $keyword . '%');
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
        $data = $data->get();
        return $data;
    }
    public function headings(): array
    {
        return [
            'CODE 订单号',
            'Mã khách 客户码',
            'Tiền hàng CNY 单价',
            'Tổng tiền VNĐ',
            'Trạng thái 状态',
            'Hoàn tiền',
            'Phí nội địa CNY 运费',
            'Tiền hàng đặt CNY',
            'Ghi chú 备注',
            'Ngày tạo',
        ];
    }
    public function map($row): array
    {
        return [
            $row->code,
            !empty($row->customer) ? $row->customer->code : '',
            $row->total,
            number_format($row->total * $row->cny, '0', ',', '.'),
            config('cart')['status'][$row->status],
            !empty($row->status_return) ? config('cart')['status_return'][$row->status_return] : '',
            $row->fee,
            $row->total_price_cny_final,
            !empty($row->total_price_vnd_final) ? number_format($row->total_price_vnd_final, '0', ',', '.') : '',
            $row->message,
            $row->created_at,
        ];
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function (AfterSheet $event) {
                $cellRange = 'A1:K1'; // All headers
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
        ];
    }
}
