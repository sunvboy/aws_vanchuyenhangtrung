<?php

namespace App\Http\Resources\order;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerOrderDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $mavandon = !empty($this->mavandon) ? explode(',', $this->mavandon) : [];
        $images = json_decode($this->images, TRUE);
        $images_return = json_decode($this->images_return, TRUE);
        $jsonImages = [];
        $jsonImageReturns = [];
        if (!empty($images)) {
            foreach ($images as $item) {
                $jsonImages[] = asset($item);
            }
        }
        if (!empty($images_return)) {
            foreach ($images_return as $item) {
                $jsonImageReturns[] = asset($item);
            }
        }
        return [
            'id' => $this->id,
            'created_at' => date('d-m-Y H:i:s', strtotime($this->created_at)), //ngày tạo đơn
            'code' => $this->code, //mã đơn hàng
            'title' => $this->title, //tên sản phẩm
            'quantity' => $this->quantity, //số lượng
            'price' => $this->price, //giá
            'total' => $this->total, //tổng tiền CNY khách mua
            'total_price_old' => $this->total_price_old, //tổng tiền VNĐ khách mua
            'total_price_cny_final' => $this->total_price_cny_final, //tổng tiền CNY cuối cùng
            'total_price_vnd_final' => $this->total_price_vnd_final, //tổng tiền VNĐ cuối cùng
            'fee' => $this->fee, //phí phát sinh
            'note' => $this->note, //ghi chú đơn hàng
            'status' => $this->status, //trạng thái đơn hàng
            'cny' => $this->cny, //tỉ giá tệ
            //hoàn trả
            'status_return' => $this->status_return, //trạng thái hoàn trả
            'message_return' => $this->message_return, //ghi chú hoàn trả
            'price_return' => $this->price_return, //số tiền yêu cầu hoàn trả
            'price_return_success' => $this->price_return_success, // số tiền được duyệt
            'date_return' => date('d-m-Y H:i:s', strtotime($this->date_return)), // ngày gửi yêu cầu hoàn trả

            'mavandon' => $mavandon, //danh sách mã vận đơn
//            'links' => json_decode($this->links), //danh sách link mua hàng
//            'links_return' => !empty($this->links_return) ? json_decode($this->links_return) : [], // danh sách link mua hàng hoàn trả
            'links' => [], //danh sách link mua hàng
            'links_return' =>  [], // danh sách link mua hàng hoàn trả
            'images' => !empty($jsonImages) ? $jsonImages : [], //danh sách hình ảnh mua hàng
            'images_return' => !empty($jsonImageReturns) ? $jsonImageReturns : [], //danh sách hình ảnh mua hàng hoàn trả
            'histories' => $this->customer_status_histories // lịch sử đơn hàng
        ];
    }
}
