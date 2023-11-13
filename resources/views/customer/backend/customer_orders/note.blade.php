@extends('dashboard.layout.dashboard')
@section('title')
<title>Lịch sử ghi chú đơn hàng #{{$detail->code}}</title>
@endsection
@section('breadcrumb')
<?php
$array = array(
    [
        "title" => "Danh sách đơn hàng",
        "src" => route('customer_orders.index'),
    ],
    [
        "title" => "Lịch sử ghi chú đơn hàng #" . $detail->code,
        "src" => 'javascript:void(0)',
    ]
);
echo breadcrumb_backend($array);
?>
@endsection
@section('content')
<?php
$links = json_decode($detail->links, true);
$images = json_decode($detail->images, true);
$links_return = json_decode($detail->links_return, true);
$images_return = json_decode($detail->images_return, true);
?>
<div class="content">
    <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
        <h2 class="flex items-center text-lg font-medium mr-auto">
            Lịch sử ghi chú đơn hàng
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="arrow-right" class="lucide lucide-arrow-right w-4 h-4 mx-2 !stroke-2" data-lucide="arrow-right">
                <line x1="5" y1="12" x2="19" y2="12"></line>
                <polyline points="12 5 19 12 12 19"></polyline>
            </svg>
            #{{$detail->code}}
        </h2>

    </div>
    @if(!empty($detail->customer_order_messages) && count($detail->customer_order_messages) > 0)
    <div class="grid grid-cols-12 gap-5 mt-5">
        <!-- BEGIN: Order Detail Side Menu -->
        <div class="col-span-12 lg:col-span-12">
            <div class="box intro-y p-5 mb-5">
                <div class="flex items-center border-b border-slate-200/60 dark:border-darkmode-400 pb-5 mb-5">
                    <div class="font-medium text-base truncate">Logs</div>
                </div>
                <ol class="relative border-l border-gray-200 dark:border-gray-700">

                    @foreach($detail->customer_order_messages as $item)
                    <li class="mb-3 ml-4">
                        <div class="absolute w-3 h-3 bg-danger rounded-full mt-1.5 -left-1.5 border border-white dark:border-gray-900 dark:bg-gray-700"></div>
                        <time class="mb-1 text-sm font-normal leading-none text-gray-400 dark:text-gray-500">{{$item->created_at}}</time>
                        <p class="text-base font-normal">{{$item->message}}</p>
                    </li>
                    @endforeach
                </ol>

            </div>


        </div>
        <!-- END: Order Detail Side Menu -->
    </div>
    @endif
</div>

@endsection
@push('javascript')

<style>
    .mt-1\.5 {
        margin-top: 0.375rem;
    }

    .-left-1\.5 {
        left: -0.375rem;
    }
</style>
@endpush