<?php

namespace App\Http\Resources\cart;

use App\Models\CustomerCartTmp;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CustomerCartTmpCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'rows' => CustomerCartTmpResource::collection($this->collection),
        ];
    }
}
