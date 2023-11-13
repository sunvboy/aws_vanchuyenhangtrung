<?php

namespace App\Http\Resources\order;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerOrderResource extends JsonResource
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
            'title' => $this->title,
            'total_price_vnd_final' => $this->total_price_vnd_final,
            'total_price_old' => $this->total_price_old,
            'status' => $this->status,
            'status_return' => $this->status_return,
            'created_at' => date('d-m-Y H:i:s', strtotime($this->created_at)),
        ];
    }
}
