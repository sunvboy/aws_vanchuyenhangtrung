<?php

namespace App\Http\Resources\packaging;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PackagingRelationshipsCollection extends ResourceCollection
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
            'rows' => PackagingRelationshipsResource::collection($this->collection),
        ];
    }
}
