<?php

namespace App\Http\Resources\order;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CustomerOrderCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    protected $status_response;
    function  _status($status_response)
    {
        $this->status_response = $status_response;
        return $this;
    }
    public function toArray($request)
    {
        return [
            'rows' => CustomerOrderResource::collection($this->collection),
            'total' => $this->total(),
            'count' => $this->count(),
            'per_page' => $this->perPage(),
            'current_page' => $this->currentPage(),
            'total_pages' => $this->lastPage(),
            '_status' => $this->status_response
        ];
    }
}
