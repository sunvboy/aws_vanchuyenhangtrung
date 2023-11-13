<?php

namespace App\Http\Resources\packaging;

use Illuminate\Http\Resources\Json\JsonResource;

class PackagingRelationshipsResource extends JsonResource
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
            'warehouses_china' => !empty($this->warehouses_vietnam) ? $this->warehouses_vietnam->code_cn : (!empty($this->warehouses_china) ? $this->warehouses_china->code_cn : ''),//Mã trung 中国单号
            'warehouses_vietnam' => !empty($this->warehouses_vietnam) ? $this->warehouses_vietnam->code_vn : (!empty($this->warehouses_china) ? $this->warehouses_china->code_vn : ''),//Mã việt 越南单号
            'weight' => $this->weight,//CÂN NẶNG(KG) 重量
        ];
    }
}
