<?php

namespace App\Http\Resources\backend\notification;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
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
            'title' => $this->title,
            'type' => $this->type,
            'user' => !empty($this->user)?$this->user->name:"",
            'created_at' => date('d-m-Y H:i:s', strtotime($this->created_at)),//NGÀY 日期
        ];
    }
}
