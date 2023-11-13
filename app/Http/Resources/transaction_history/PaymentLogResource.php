<?php

namespace App\Http\Resources\transaction_history;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentLogResource extends JsonResource
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
            'note' => $this->note,
            'price_old' => $this->price_old,
            'price_final' => $this->price_final,
            'created_at' => date('d-m-Y H:i:s', strtotime($this->created_at)),
        ];
    }
}
