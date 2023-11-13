<?php

namespace App\Http\Resources\backend\customer_categories;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CustomerCategoryCollection extends ResourceCollection
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
            'rows' => CustomerCategoryResource::collection($this->collection),
        ];
    }
}
