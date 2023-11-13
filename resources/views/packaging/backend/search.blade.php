@extends('dashboard.layout.dashboard')
@section('title')
<title>TÌM KIẾM MÃ VẬN ĐƠN</title>
@endsection
@section('breadcrumb')
<?php
$array = array(
    [
        "title" => "TÌM KIẾM MÃ VẬN ĐƠN",
        "src" => 'javascript:void(0)',
    ]
);
echo breadcrumb_backend($array);
?>
@endsection
@section('content')
<div class="content">
    <div class="flex justify-between items-center mt-5">
        <h1 class=" text-lg font-medium">
            TÌM KIẾM MÃ VẬN ĐƠN
        </h1>
    </div>

    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="col-span-12 mt-2">
            <form action="" class="grid grid-cols-12 gap-2" id="search" style="margin-bottom: 0px;">
                <div class="col-span-6">
                    <input type="search" name="keyword" class="keyword form-control filter w-full h-10" placeholder="Nhập mã bao" autocomplete="off" value="<?php echo request()->get('keyword') ?>">
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
            <table class="table table-report -mt-2">
                <thead class="table-dark">
                    <tr>
                        <td colspan="3" class="text-center font-bold" style="border-radius: 0px;color: #000">Tổng dòng : {{!empty($count)?$count : 0}} </td>
                        <td class=" text-white" style="background-color: red">Tổng cân 总重量</td>
                        <td class=" text-white font-bold" style="background-color: red;border-radius: 0px;">{{number_format((float)$total_weight, 2, '.', '')}}</td>
                        <td class=" text-black" style="background-color: yellow">Tổng số cân thực tế 包袋重量</td>
                        <td class=" text-black font-bold" style="background-color: yellow;border-radius: 0px;">{{number_format((float)$total_value_weight, 2, '.', '')}}</td>
                    </tr>
                    <tr>

                        <th class="whitespace-nowrap text-center">NGÀY 日期 </th>
                        <th class="whitespace-nowrap">MÃ BAO 包号 </th>
                        <th class="whitespace-nowrap">MÃ VẬN ĐƠN 运单号 </th>
                        <th class="whitespace-nowrap">Số lượng kiện hàng 数量件</th>
                        <th class="whitespace-nowrap">CÂN NẶNG(KG) 重量 </th>
                        <th class="whitespace-nowrap">MÃ KHÁCH 客户码 </th>
                        <th class="whitespace-nowrap">TÊN KHÁCH HÀNG名字</th>
                        <th class="whitespace-nowrap">Người tạo</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $v)
                    <tr class="odd " id="post-<?php echo $v->id; ?>">

                        <td>{{$v->created_at}}</td>
                        <td>
                            <a href="{{route('packagings.show',['id' => $v->id])}}" class="text-danger font-bold">{{$v->code}}</a>
                        </td>
                        <td>
                            <a href="{{route('packagings.show',['id' => $v->id])}}" class="text-primary font-bold" style="text-decoration: underline;">Mã vận đơn</a>
                        </td>
                        <td>
                            {{count($v->packaging_relationships)}}
                        </td>
                        <td>
                            {{$v->value_weight}}
                        </td>

                        <td>
                            {{!empty($v->customer) ?$v->customer->code:''}}
                        </td>
                        <td>
                            {{!empty($v->customer) ?$v->customer->name:''}}
                        </td>
                        <td>
                            {{!empty($v->user) ?$v->user->name:''}}
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
