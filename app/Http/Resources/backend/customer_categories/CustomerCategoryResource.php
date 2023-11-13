<?php

namespace App\Http\Resources\backend\customer_categories;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerCategoryResource extends JsonResource
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
            'count' => $this->customers->count(),
            'title' => $this->title,
            'code' => $this->slug,
            'created_at' => date('d-m-Y H:i:s', strtotime($this->created_at)),//NGÀY 日期
        ];
    }
}
