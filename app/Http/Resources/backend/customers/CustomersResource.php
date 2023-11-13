<?php

namespace App\Http\Resources\backend\customers;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomersResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        return [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'price' => $this->price,
            'catalogue_id' => $this->catalogue_id,
            'created_at' => date('d-m-Y H:i:s', strtotime($this->created_at)),//NGÀY 日期
        ];
    }
}
