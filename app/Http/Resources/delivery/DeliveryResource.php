<?php

namespace App\Http\Resources\delivery;

use Illuminate\Http\Resources\Json\JsonResource;

class DeliveryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $warehouses = [];
        if(!empty($this->advanced)){
            $products = $this->delivery_relationships;
            if (!empty($products) && count($products) > 0) {
                foreach ($products as $key => $val) {
                    $warehouses[] = [
                        'code' => $val->code,
                        'weight' => $val->weight,
                        'note' => $val->note,
                    ];
                }
            }
        }else{
            $products = json_decode($this->products, TRUE);
            if (isset($products) && is_array($products) && count($products)) {
                foreach ($products['code'] as $key => $val) {
                    $warehouses[] = [
                        'code' => $val,
                        'weight' => (float)!empty($products['weight'][$key])?$products['weight'][$key]:0,
                        'note' => !empty($products['note'][$key])?$products['note'][$key]:'',
                    ];
                }
            }
        }

        $QR_CODE_ID = env('QR_CODE_ID');
        $QR_CODE_NAME = env('QR_CODE_NAME');
        $price = (int)$this->price;
        return [
            'id' => $this->id,
            'code' => $this->code,//CODE 交貨單
            'created_at' => date('d-m-Y H:i:s', strtotime($this->created_at)),//NGÀY 日期
            'weight' => (float)$this->weight,//CÂN NẶNG(KG) 重量
            'fee' => (int)$this->fee,//CÂN NẶNG(KG) 重量
            'shipping' => (int)$this->shipping,//CÂN NẶNG(KG) 重量
            'customer_code' => !empty($this->customer) ? $this->customer->code : '',//Mã KH 客户码
            'price' => $price,//Đơn giá 單價
            'status' => $this->status,//Đơn giá 單價
            'payment' => $this->payment,//Đơn giá 單價
            'image' => "https://api.vietqr.io/image/$QR_CODE_ID?accountName=$QR_CODE_NAME&amount=$price&addInfo=$this->code",//Đơn giá 單價
            'warehouses' => $warehouses,//Đơn giá 單價
        ];
    }
}
