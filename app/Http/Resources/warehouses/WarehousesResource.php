<?php

namespace App\Http\Resources\warehouses;

use Illuminate\Http\Resources\Json\JsonResource;

class WarehousesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if(!empty($this->packaging_relationships) && !empty($this->packaging_relationships->packagings)){
            $code_packaging = $this->packaging_relationships->packagings->code;
        }
        $status = 'Nhập kho TQ';
        $status_color = '#dc2626';
        $history = [
            [
                'color' => '#dc2626',
                'title' => 'Nhập kho Trung Quốc',
                'time' => date('d-m-Y H:i:s', strtotime($this->created_at))
            ]
        ];
        if(!empty($this->status_packaging_truck)){
            $status = 'Đang vận chuyển về VN';
            $status_color = '#2563eb';
            $history[] = [
                'color' => '#000',
                'title' => 'Đang vận chuyển về Việt Nam',
                'time' => date('d-m-Y H:i:s', strtotime($this->date_packaging_truck))
            ];
        }
        if(!empty($this->packaging_relationships) && !empty($this->packaging_relationships->packagings) && !empty($this->packaging_relationships->packagings->packaging_v_n_s)){
            $status = 'Nhập kho VN';
            $status_color = '#db2777';
            $history[] = [
                'color' => '#000',
                'title' => 'Nhập kho Việt Nam',
                'time' => date('d-m-Y H:i:s', strtotime($this->packaging_relationships->packagings->packaging_v_n_s->created_at))
            ];
        }
        if(!empty($this->delivery_relationships)){
            $status = 'Giao hàng';
            $status_color = '#65a30d';
            $history[] = [
                'color' => '#000',
                'title' => 'Giao hàng',
                'code' => !empty($this->delivery_relationships) ? $this->delivery_relationships->deliveries->code : '',
                'time' => date('d-m-Y H:i:s', strtotime($this->delivery_relationships->deliveries->created_at))
            ];
        }
        return [
            'id' => $this->id,
            'code_cn' => $this->code_cn,//MÃ VẬN ĐƠN 运单号
            'customer_packaging' => !empty($this->customer)?$this->customer->code.'-'.$this->customer->name:'',//MÃ BAO 包号
            'customer_delivery' => !empty($this->delivery_relationships) ? $this->delivery_relationships->deliveries->customer->code.'-'.$this->delivery_relationships->deliveries->customer->name : '',//mã giao hàng
            'code_vn' => $this->code_vn,//MÃ VN
            'code_packaging' => !empty($code_packaging)?$code_packaging:'',//MÃ BAO 包号
            'name_cn' => $this->name_cn,//TÊN SẢN PHẨM 品名
            'name_vn' => $this->name_vn,//TÊN SẢN PHẨM VN
            'quantity' => $this->quantity,//Số lượng sản phẩm 数量产品
            'weight' => $this->weight,//CÂN NẶNG(KG) 重量
            'quantity_kien' => $this->quantity_kien,//Số lượng kiện 数量件
            'price' => $this->price,//giá
            'status' => $status,//Trạng thái
            'status_color' => $status_color,//Trạng thái
            'code_delivery' => !empty($this->delivery_relationships) ? $this->delivery_relationships->deliveries->code : '',//mã giao hàng
            'created_at' => date('d-m-Y H:i:s', strtotime($this->date)),//NGÀY 日期
            'history' =>$history
        ];
    }
}
