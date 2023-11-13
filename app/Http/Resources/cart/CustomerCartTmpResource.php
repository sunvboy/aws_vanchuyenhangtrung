<?php

namespace App\Http\Resources\cart;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerCartTmpResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $images = json_decode($this->images, TRUE);
        $jsonImages = [];
        if (!empty($images)) {
            foreach ($images as $item) {
                $jsonImages[] = asset($item);
            }
        }
        return [
            'id' => $this->id,
            'created_at' => date('d-m-Y H:i:s', strtotime($this->created_at)),
            'title' => $this->title,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'total' => $this->total,
            'note' => $this->note,
//            'links' => json_decode($this->links, TRUE),
            'links' => [],
            'images' => !empty($jsonImages) ? $jsonImages : [],
        ];
    }
}
