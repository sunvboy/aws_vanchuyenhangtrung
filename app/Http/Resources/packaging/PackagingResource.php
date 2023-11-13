<?php

namespace App\Http\Resources\packaging;

use Illuminate\Http\Resources\Json\JsonResource;

class PackagingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if(!empty($this->packaging_v_n_s)){
            $status_color = '#dc2626';
            $status = 'Đã về VN';
        }elseif(!empty($this->packaging_trucks)){
            $status_color = '#2563eb';
            $status = 'Đang vận chuyển về VN';
        }else{
            $status_color = '#db2777';
            $status = 'Nhập kho TQ';
        }
        return [
            'id' => $this->id,
            'code' => $this->code,//MÃ BAO 包号
            'value_weight' => $this->value_weight,//CÂN NẶNG(KG) 重量
            'quantity_kien' => count($this->packaging_relationships),//Số lượng kiện 数量件
            'customer_code' => !empty($this->customer) ? $this->customer->code:'',//Số lượng kiện 数量件
            'customer_name' => !empty($this->customer) ? $this->customer->name:'',//MÃ KHÁCH 客户码
            'status' => $status,//Trạng thái
            'status_color' => $status_color,//Trạng thái
            'created_at' => date('d-m-Y H:i:s', strtotime($this->created_at)),//TÊN KHÁCH HÀNG名字
            'packaging_relationships' => PackagingRelationshipsResource::collection($this->packaging_relationships)
        ];
    }
}
