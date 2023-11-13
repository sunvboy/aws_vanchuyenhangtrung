@extends('dashboard.layout.dashboard')
@section('title')
<title>Tìm kiếm mã vận đơn</title>
@endsection
@section('breadcrumb')
<?php
$array = array(
    [
        "title" => "Tìm kiếm mã vận đơn",
        "src" => 'javascript:void(0)',
    ]
);
echo breadcrumb_backend($array);
?>
@endsection
@section('content')
<div class="content ">
    <div class="flex justify-between items-center mt-5">
        <h1 class=" text-lg font-medium">
            Tìm kiếm mã vận đơn
        </h1>
    </div>
    <div class="grid grid-cols-12 gap-6 mt-2">
        <div class="col-span-12 mt-2">
            <form action="" class="grid grid-cols-12 gap-2" id="search" style="margin-bottom: 0px;">
                <div class="col-span-5">
                    <input type="search" name="keyword" class="keyword form-control filter w-full h-10" placeholder="Nhập từ khóa tìm kiếm ..." autocomplete="off" value="<?php echo request()->get('keyword') ?>">
                </div>
                <div class="col-span-1 flex items-center space-x-2 justify-end">
                    <button class="btn btn-primary btn-sm">
                        <i data-lucide="search"></i>
                    </button>
                </div>
            </form>

        </div>
        <!-- BEGIN: Data List -->
        <div class=" col-span-12 lg:col-span-12">
            <table class="table table-report">

                <thead class="table-dark">
                    <tr>
                        <td colspan="2" class="text-center font-bold" style="    border-radius: 0px;color: #000">Tổng dòng : {{!empty($count)?$count : 0}} </td>
                        <td class=" text-white" style="background-color: red">Tổng cân </td>
                        <td class=" text-white font-bold" style="background-color: red;border-radius: 0px;">{{$total}}</td>
                    </tr>
                    <tr>
                        <th class="">NGÀY 日期</th>
                        <th class="whitespace-nowrap">MÃ VẬN ĐƠN 运单号</th>
                        <th class="whitespace-nowrap">MÃ VN</th>
                        <th class="">CÂN NẶNG(KG) 重量 </th>
                        <th class="">Số lượng kiện 数量件</th>
                        <th class="">Số lượng sản phẩm 数量产品</th>
                        <th class="">TÊN SẢN PHẨM 品名 </th>
                        <th class="">TÊN SẢN PHẨM VN </th>
                        <th class="">GIÁ</th>
                        <th class="">MÃ KHÁCH 客户码 </th>
                        <th class="">MÃ BAO 包号</th>
                        <th class="">Trạng thái</th>
                        <th class="">Người tạo</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $v)
                    <tr class="odd " id="post-<?php echo $v->id; ?>">
                        <td>
                            {{$v->date}}
                        </td>
                        <td>
                            <a href="{{route('warehouses.show',['id'=>$v->id])}}" class="text-danger font-bold" style="text-decoration: underline;">{{$v->code_cn}}</a>
                        </td>
                        <td>
                            {{$v->code_vn}}
                        </td>
                        <td>
                            {{$v->weight}}
                        </td>
                        <td>
                            {{$v->quantity_kien}}
                        </td>
                        <td>
                            {{$v->quantity}}
                        </td>
                        <td>
                            {{$v->name_cn}}
                        </td>
                        <td>
                            {{$v->name_vn}}
                        </td>
                        <td>
                            {{$v->price}}
                        </td>
                        <td>
                            {{!empty($v->customer)?$v->customer->code:''}}
                        </td>


                        <td>
                            @if(!empty($v->packaging_relationships) && !empty($v->packaging_relationships->packagings))
                            <a target="_blank" href="{{route('packagings.show',['id' =>  $v->packaging_relationships->packaging_id])}}" class="text-primary font-bold" style="text-decoration: underline;">
                                {{$v->packaging_relationships->packagings->code}}
                            </a>
                            @endif
                        </td>
                        <td>
                            @if(!empty($v->packaging_relationships) && !empty($v->packaging_relationships->packagings) && !empty($v->packaging_relationships->packagings->packaging_v_n_s))
                            <span class="btn btn-success text-white btn-sm">Đã về VN</span>
                            @else
                            <span class="btn btn-primary btn-sm p-2">Nhập kho TQ</span>
                            @endif
                        </td>
                        <td>
                            {{!empty($v->user)?$v->user->name:''}}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- END: Data List -->
        <!-- BEGIN: Pagination -->
        <div class=" col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center justify-center">
            {{!empty($data)?$data->links():''}}
        </div>
        <!-- END: Pagination -->
    </div>
</div>

@endsection