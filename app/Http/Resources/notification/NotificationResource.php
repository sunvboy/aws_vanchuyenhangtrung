<?php

namespace App\Http\Resources\notification;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if($this->type == 'delivery'){
            $_redirects = $this->delivery_id;
        }elseif($this->type == 'return'){
            $_redirects = $this->customer_order_id;
        }elseif($this->type == 'payment'){
            $_redirects = $this->customer_order_id;
        }
        $string = str_replace('src="/upload/', 'src="https://vanchuyenhangtrung.com/upload/', $this->content);

        return [
            'id' => $this->id,
            'id_redirects' => !empty($_redirects)?$_redirects:'',
            'title' => $this->title,
            'body' => $this->body,
            'content' => $string,
            'type' => $this->type,
            'created_at' => date('d-m-Y H:i:s', strtotime($this->created_at)),
            'view' => !empty($this->notification_views)?true:false
        ];
    }
}
