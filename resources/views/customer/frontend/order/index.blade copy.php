@extends('homepage.layout.home')
@section('content')
<main class="">
    <div class="container px-4 mx-auto">
        <div class="mt-4 flex flex-col md:flex-row items-start md:space-x-4">
            @include('customer/frontend/auth/common/sidebar')
            <div class="flex-1 w-full md:w-auto order-1 md:order-1">
                <div class="overflow-x-hidden  rounded-xl md:p-6 space-y-4">
                    <div class=" bg-white">
                        <h1 class="text-black font-bold text-xl">Danh sách đơn hàng</h1>
                        <!-- Slider main container -->
                        @if($data)
                        <div class="mt-5 space-y-2">
                            <?php /*<div class="flex flex-wrap md:flex-nowrap hidden">
                                <a href="{{route('ordersF.index')}}" class="menu_order flex-auto text-center font-medium hover:text-red-500 mb-5 mr-5 md:mb-0 md:mr-0">{{trans('index.All')}}</a>
                                @foreach(config('cart.status') as $key=>$val)
                                <a href="{{route('ordersF.index',['status' => $key])}}" class="menu_order flex-auto text-center font-medium hover:text-red-500 mb-5 mr-5 md:mb-0 md:mr-0 @if(request()->get('status') == $key) active @endif">
                                    <?php echo $_status[$key] ?>
                                </a>
                                @endforeach
                            </div>*/ ?>

                            <div class="flex flex-wrap" style="margin: 0px -5px">
                                <?php $i = 0; ?>
                                @foreach(config('cart')['status'] as $key=>$item)
                                <?php $i++; ?>
                                @if($i > 1)
                                <div class="borderA border px-2 py-2 <?php if ($key == request()->get('status')) { ?>active<?php } ?>" style="margin: 0px 5px;float: left;margin-bottom: 10px">
                                    <a href="{{route('ordersF.index',['status' => $key])}}">{{$item}} <span class="font-bold">({{$dataCount[$key]}})</span></a>
                                </div>
                                @endif
                                @endforeach
                            </div>

                            <div class="">
                                <form class="w-full flex flex-col md:flex-row justify-between space-y-4 md:space-y-0">
                                    <div class="flex-1 grid grid-cols-1 md:grid-cols-4 gap-4">
                                        <input type="text" name="keyword" placeholder="Nhập từ khóa tìm kiếm" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="{{request()->get('keyword')}}" autocomplete="off">
                                        <input type="text" name="date_start" placeholder="Ngày bắt đầu" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="{{request()->get('date_start')}}" autocomplete="off">
                                        <input type="text" name="date_end" placeholder="Ngày kết thúc" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="{{request()->get('date_end')}}" autocomplete="off">
                                        <button type="submit" class="bg_gradient rounded-lg w-10 h-[42px] flex justify-center items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-white">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                                            </svg>
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <div class="listItem">

                                <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                                    <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8">
                                        <div class="overflow-hidden space-y-5">
                                            <table class="min-w-full text-center text-sm font-light">
                                                <thead class="border-b bg-neutral-800 font-medium text-white dark:border-neutral-500 dark:bg-neutral-900">
                                                    <tr>
                                                        <th scope="col" class="px-2 text-left py-4">STT</th>
                                                        <th scope="col" class="px-2 text-left py-4">Mã ĐH</th>
                                                        <th scope="col" class="px-2 text-left py-4">Tên sản phẩm
                                                        </th>
                                                        <th scope="col" class="px-2 text-left py-4">GIÁ</th>
                                                        <th scope="col" class="px-2 text-left py-4">Trạng thái</th>
                                                        <th scope="col" class="px-2 text-left py-4">Hoàn tiền</th>
                                                        <th scope="col" class="px-2 text-left py-4">NGÀY 日期</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($data as $item)
                                                    <tr class="border-b dark:border-neutral-500">
                                                        <td> {{$data->firstItem()+$loop->index}}</td>
                                                        <td class="whitespace-nowrap  py-4 px-2 text-left">
                                                            <a href="{{route('ordersF.show',['id' => $item->id])}}" class="font-bold text-red-600 underline">
                                                                {{$item->code}}
                                                            </a>
                                                        </td>
                                                        <td class="whitespace-nowrap  py-4 px-2 text-left "> {{$item->title}}</td>
                                                        <td class="whitespace-nowrap  py-4 px-2 text-left">
                                                            <div class="flex flex-col">
                                                                @if(!empty($item->total_price_vnd_final))
                                                                <span class="font-bold">{{!empty($item->total_price_vnd_final) ? number_format($item->total_price_vnd_final,'0',',','.') : number_format($item->total_price_old,'0',',','.')}} VNĐ</span>
                                                                <span style="text-decoration: line-through;">{{number_format($item->total_price_old,'0',',','.')}} VNĐ</span>
                                                                @else
                                                                <span class="font-bold">{{number_format($item->total_price_old,'0',',','.')}} VNĐ</span>
                                                                @endif
                                                            </div>
                                                        </td>
                                                        <td class="whitespace-nowrap  py-4 px-2 text-left">
                                                            <div class="flex flex-col space-y-1">
                                                                <span class="text-white px-2 py-1 rounded text-xs" style="background-color: {{config('cart')['class'][$item->status]}};">
                                                                    {{config('cart')['status'][$item->status]}}
                                                                </span>
                                                            </div>
                                                        </td>
                                                        <td class="whitespace-nowrap  py-4 px-2 text-left">
                                                            <div class="flex flex-col space-y-1">
                                                                @if($item->status == 'returns')
                                                                <span style="background-color: {{config('cart')['class'][$item->status_return]}};" class="text-white px-2 py-1 rounded text-xs">#{{config('cart')['status_return'][$item->status_return]}}</span>
                                                                @endif
                                                            </div>
                                                        </td>
                                                        <td class="whitespace-nowrap  py-4 px-2 text-left"> {{$item->created_at}}</td>
                                                    </tr>
                                                    @endforeach

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex justify-center">
                                    <?php echo $data->links() ?>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="flex flex-col items-center ml-4  bg-white  rounded-xl mt-4 space-y-3">
                            <div class="bg-gray-100 rounded-full flex items-center justify-center w-[50px] h-[50px]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-global" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <strong class="font-bold mb-2">{{trans('index.NoOrdersYet')}}</strong>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
</main>
@endsection
@push('javascript')
<style>
    .borderA.active {
        background: red;
        border-color: red;
    }

    .borderA.active a {
        color: white;
    }
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.js" integrity="sha512-+UiyfI4KyV1uypmEqz9cOIJNwye+u+S58/hSwKEAeUMViTTqM9/L4lqu8UxJzhmzGpms8PzFJDzEqXL9niHyjA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.css" integrity="sha512-bYPO5jmStZ9WI2602V2zaivdAnbAhtfzmxnEGh9RwtlI00I9s8ulGe4oBa5XxiC6tCITJH/QG70jswBhbLkxPw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<script type="text/javascript">
    $(function() {
        $('input[name="date_start"]').datetimepicker({
            format: 'Y-m-d',
        });
        $('input[name="date_end"]').datetimepicker({
            format: 'Y-m-d',
        });
    });
</script>
<script>
    $(".menu_item_auth:eq(2)").addClass('active');
</script>
@endpush