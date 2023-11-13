<?php

namespace App\Http\Resources\delivery;

use Illuminate\Http\Resources\Json\ResourceCollection;

class DeliveryCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    protected $total_weight;
    public function total_weight($total_weight){
        $this->total_weight = $total_weight;
        return $this;
    }
    public function toArray($request)
    {
        return [
            'rows' => DeliveryResource::collection($this->collection),
            'total' => $this->total(),
            'count' => $this->count(),
            'per_page' => $this->perPage(),
            'current_page' => $this->currentPage(),
            'total_pages' => $this->lastPage(),
            'total_weight' => $this->total_weight,
        ];
    }
}
