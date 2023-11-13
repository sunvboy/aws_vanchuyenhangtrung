@extends('dashboard.layout.dashboard')
@section('title')
<title>Chi tiết đơn hàng</title>
@endsection
@section('breadcrumb')
<?php
$array = array(
    [
        "title" => "Danh sách đơn hàng",
        "src" => route('customer_orders.index'),
    ],
    [
        "title" => "Chi tiết đơn hàng",
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
            Đơn hàng
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="arrow-right" class="lucide lucide-arrow-right w-4 h-4 mx-2 !stroke-2" data-lucide="arrow-right">
                <line x1="5" y1="12" x2="19" y2="12"></line>
                <polyline points="12 5 19 12 12 19"></polyline>
            </svg>
            #{{$detail->code}}
        </h2>

    </div>
    <div class="grid grid-cols-12 gap-5 mt-5">

        <!-- BEGIN: Order Detail Side Menu -->
        <div class="col-span-12 lg:col-span-4">
            @if(!empty($detail->customer_status_histories) && count($detail->customer_status_histories) > 0)
            <div class="box intro-y p-5 mb-5">
                <div class="flex items-center border-b border-slate-200/60 dark:border-darkmode-400 pb-5 mb-5">
                    <div class="font-medium text-base truncate">Logs</div>
                </div>
                <ol class="relative border-l border-gray-200 dark:border-gray-700">
                    <li class="mb-3 ml-4">
                        <div class="absolute w-3 h-3 bg-danger rounded-full mt-1.5 -left-1.5 border border-white dark:border-gray-900 dark:bg-gray-700"></div>
                        <time class="mb-1 text-sm font-normal leading-none text-gray-400 dark:text-gray-500">{{$detail->created_at}}</time>
                        <p class="text-base font-normal">Tạo đơn hàng</p>
                    </li>
                    @foreach($detail->customer_status_histories as $item)
                    <li class="mb-3 ml-4">
                        <div class="absolute w-3 h-3 bg-danger rounded-full mt-1.5 -left-1.5 border border-white dark:border-gray-900 dark:bg-gray-700"></div>
                        <time class="mb-1 text-sm font-normal leading-none text-gray-400 dark:text-gray-500">{{$item->created_at}}</time>
                        <p class="text-base font-normal">{{$item->message}}</p>
                    </li>
                    @endforeach
                </ol>

            </div>
            @endif

            @if(!empty($detail->status_return))
            <div class="box intro-y p-5 mb-5">
                <div class="flex items-center border-b border-slate-200/60 dark:border-darkmode-400 pb-5 mb-5">
                    <div class="font-medium text-base truncate">Thông tin Khiếu nại/hoàn hàng</div>
                </div>
                @if(!empty($detail->message_return))
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="clipboard" data-lucide="clipboard" class="lucide lucide-clipboard w-4 h-4 text-slate-500 mr-2">
                        <path d="M16 4h2a2 2 0 012 2v14a2 2 0 01-2 2H6a2 2 0 01-2-2V6a2 2 0 012-2h2"></path>
                        <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                    </svg>
                    Ghi chú:
                    <a href="javascript:void(0)" class="underline decoration-dotted ml-1">{{$detail->message_return}}</a>
                </div>
                @endif

                <div class="flex items-center mt-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="calendar" data-lucide="calendar" class="lucide lucide-calendar w-4 h-4 text-slate-500 mr-2">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                    </svg> Ngày tạo: {{$detail->date_return}}
                </div>
                <div class="flex items-center mt-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="calendar" data-lucide="calendar" class="lucide lucide-calendar w-4 h-4 text-slate-500 mr-2">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                    </svg> Số tiền hoàn trả(VNĐ): <span class="font-bold text-danger">&nbsp;{{number_format($detail->price_return,'0',',','.')}} </span>
                </div>
                @if(!empty($detail->price_return_success))
                <div class="flex items-center mt-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="calendar" data-lucide="calendar" class="lucide lucide-calendar w-4 h-4 text-slate-500 mr-2">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                    </svg> Số tiền được hoàn trả(VNĐ): <span class="font-bold text-success">&nbsp;{{number_format($detail->price_return_success,'0',',','.')}} </span>
                </div>
                @endif

                <div class="flex flex-col mt-3">
                    <div class="font-bold">
                        Liên kết:
                    </div>
                    <div>
                        @if(!empty($links_return))
                        <div class="space-y-1" id="formData">
                            @foreach($links_return as $link)
                            @if(!empty($link))
                            <div class="space-y-1">
                                <div class="grid grid-cols-1">
                                    <a href="{{$link}}" class="col-span-1 outline-none focus:outline-none hover:outline-none underline" style="overflow: hidden;">
                                        {{$link}}
                                    </a>
                                </div>
                            </div>
                            @endif
                            @endforeach
                        </div>
                        @endif
                    </div>

                </div>
                <div class="flex flex-col mt-3">
                    <div class="font-bold">
                        Hình ảnh:
                    </div>
                    <div>
                        @if(!empty($images_return))
                        <div class="grid grid-cols-4 flex-wrap gap-3">
                            @foreach($images_return as $img)
                            @if(!empty($img))
                            <a href="{{asset($img)}}" data-fancybox="images" rel='groupQ'>
                                <img src="{{asset($img)}}" class="object-cover w-full" style="height: 100px;" />
                            </a>
                            @endif
                            @endforeach
                        </div>
                        @endif
                    </div>

                </div>

                <div class="flex items-center mt-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="clock" data-lucide="clock" class="lucide lucide-clock w-4 h-4 text-slate-500 mr-2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                    Trạng thái Khiếu nại/hoàn hàng:
                    <span class="text-xs text-white rounded-md px-1.5 py-0.5 ml-1" style="background-color: {{config('cart')['class'][$detail->status_return]}};">
                        #{{config('cart')['status_return'][$detail->status_return]}}
                    </span>
                </div>
            </div>
            @endif

            <div class="box intro-y p-5">
                <div class="flex items-center border-b border-slate-200/60 dark:border-darkmode-400 pb-5 mb-5">
                    <div class="font-medium text-base truncate">Thông tin đơn hàng</div>
                </div>
                @if(!empty($detail->mavandon))
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="clipboard" data-lucide="clipboard" class="lucide lucide-clipboard w-4 h-4 text-slate-500 mr-2">
                        <path d="M16 4h2a2 2 0 012 2v14a2 2 0 01-2 2H6a2 2 0 01-2-2V6a2 2 0 012-2h2"></path>
                        <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                    </svg>
                    MÃ VẬN ĐƠN 运单号:
                </div>
                <div class="flex flex-col">
                    <?php
                    $mavandonShow = explode(',', $detail->mavandon);
                    ?>
                    @if(!empty($mavandonShow))
                    @foreach($mavandonShow as $val)
                    <a href="javascript:void(0)" class="underline decoration-dotted ml-1 font-bold text-danger">{{$val}}</a>
                    @endforeach
                    @endif
                </div>

                @endif

                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="clipboard" data-lucide="clipboard" class="lucide lucide-clipboard w-4 h-4 text-slate-500 mr-2">
                        <path d="M16 4h2a2 2 0 012 2v14a2 2 0 01-2 2H6a2 2 0 01-2-2V6a2 2 0 012-2h2"></path>
                        <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                    </svg>
                    Mã đơn hàng 订单号:
                    <a href="javascript:void(0)" class="underline decoration-dotted ml-1">#{{$detail->code}}</a>
                </div>
                <div class="flex items-center mt-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="calendar" data-lucide="calendar" class="lucide lucide-calendar w-4 h-4 text-slate-500 mr-2">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                    </svg> Ngày tạo: {{$detail->created_at}}
                </div>
                <div class="flex items-center mt-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="clock" data-lucide="clock" class="lucide lucide-clock w-4 h-4 text-slate-500 mr-2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                    Trạng thái:
                    <span class="text-xs text-white rounded-md px-1.5 py-0.5 ml-1" style="background-color: {{config('cart')['class'][$detail->status]}};">{{config('cart')['status'][$detail->status]}}</span>
                </div>
                <div class="flex items-center mt-3 space-x-2">
                    @if($detail->status == 'pending_order')
                    <div class="flex flex-col space-y-2">
                        <a href="javascript:void(0)" class="js_handle_completed_order btn btn-warning text-white">Đã mua hàng 已付款</a>
                    </div>
                    @endif
                    @if($detail->status == 'completed_order')
                    <div class="flex flex-col space-y-2 w-full">
                        <i class="text-danger">Lưu ý: Nhấn ENTER để xuống dòng</i>
                        <div id="list" class="space-y-2">
                            <div class="flex items-center space-x-1">
                                <?php echo Form::text('mavandon[]', '', ['class' => 'form-control w-full mavandon', 'placeholder' => 'MÃ VẬN ĐƠN 运单号']); ?>
                                <a href="javascript:void(0)" class="js_removeColumn text-danger font-bold">Xóa</a>
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            <a href="javascript:void(0)" class="js_handle_completed w-1/2 btn btn-warning text-white" data-status="pending">Chờ nhận hàng 等收货</a>
                            <a href="javascript:void(0)" class="js_handle_completed w-1/2 btn btn-danger" data-status="canceled">Hủy đơn hàng 取消订单</a>
                        </div>
                    </div>
                    @endif
                    @if($detail->status == 'pending')
                    <a href="javascript:void(0)" class="js_handle_completed btn btn-primary text-white" data-status="completed">Đã nhận hàng 已收货</a>
                    @endif

                    @if($detail->status == 'wait' || $detail->status == 'pending_payment' || $detail->status == 'pending_order' || $detail->status == 'pending' || $detail->status == 'completed')
                    <a href="javascript:void(0)" class="js_handle_completed btn btn-danger" data-status="canceled">Hủy đơn hàng 取消订单</a>
                    @endif

                    <?php /* @if($detail->status == 'pending_order' || $detail->status == 'completed_order' || $detail->status == 'pending' || $detail->status == 'completed')
                    <a href="javascript:void(0)" class="js_handle_returns btn btn-success text-white">Hoàn tiền</a>
                    @endif*/ ?>
                </div>
            </div>
            <div class="box intro-y p-5 mt-5">
                <div class="flex items-center border-b border-slate-200/60 dark:border-darkmode-400 pb-5 mb-5">
                    <div class="font-medium text-base truncate">Khách hàng</div>
                </div>
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="clipboard" data-lucide="clipboard" class="lucide lucide-clipboard w-4 h-4 text-slate-500 mr-2">
                        <path d="M16 4h2a2 2 0 012 2v14a2 2 0 01-2 2H6a2 2 0 01-2-2V6a2 2 0 012-2h2"></path>
                        <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                    </svg> Mã khách hàng 客户码: {{!empty($detail->customer)?$detail->customer->code:''}}
                </div>
                <div class="flex items-center mt-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="calendar" data-lucide="calendar" class="lucide lucide-calendar w-4 h-4 text-slate-500 mr-2">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                    </svg>
                    Tên khách hàng: {{!empty($detail->customer)?$detail->customer->name:''}}

                </div>
                <div class="flex items-center mt-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="map-pin" data-lucide="map-pin" class="lucide lucide-map-pin w-4 h-4 text-slate-500 mr-2">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"></path>
                        <circle cx="12" cy="10" r="3"></circle>
                    </svg>
                    Số điện thoại: {{!empty($detail->customer)?$detail->customer->phone:''}}
                </div>
            </div>
            <div class="box intro-y p-5 mt-5">
                <div class="flex items-center border-b border-slate-200/60 dark:border-darkmode-400 pb-5 mb-5">
                    <div class="font-medium text-base truncate">Chi tiết đơn hàng</div>
                </div>
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="credit-card" data-lucide="credit-card" class="lucide lucide-credit-card w-4 h-4 text-slate-500 mr-2">
                        <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                        <line x1="1" y1="10" x2="23" y2="10"></line>
                    </svg>
                    Tên sản phẩm 品名:
                    <div class="ml-auto">{{$detail->title}}</div>
                </div>
                <div class="flex items-center mt-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="credit-card" data-lucide="credit-card" class="lucide lucide-credit-card w-4 h-4 text-slate-500 mr-2">
                        <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                        <line x1="1" y1="10" x2="23" y2="10"></line>
                    </svg>
                    Kích thước tiêu chuẩn 规格:
                    <div class="ml-auto">{{$detail->weight}}</div>
                </div>
                <div class="flex items-center mt-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="credit-card" data-lucide="credit-card" class="lucide lucide-credit-card w-4 h-4 text-slate-500 mr-2">
                        <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                        <line x1="1" y1="10" x2="23" y2="10"></line>
                    </svg> Số lượng 数量:
                    <div class="ml-auto">{{$detail->quantity}}</div>
                </div>
                <div class="flex items-center mt-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="credit-card" data-lucide="credit-card" class="lucide lucide-credit-card w-4 h-4 text-slate-500 mr-2">
                        <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                        <line x1="1" y1="10" x2="23" y2="10"></line>
                    </svg>Đơn giá(¥) 单价:
                    <div class="ml-auto">{{$detail->price}}</div>
                </div>
                @if(!empty($detail->note))
                <div class="flex items-center mt-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="credit-card" data-lucide="credit-card" class="lucide lucide-credit-card w-4 h-4 text-slate-500 mr-2">
                        <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                        <line x1="1" y1="10" x2="23" y2="10"></line>
                    </svg> Ghi chú 备注:
                    <div class="ml-auto">{{$detail->note}}</div>
                </div>
                @endif

                <div class="flex items-center border-t border-slate-200/60 dark:border-darkmode-400 pt-5 mt-5 font-medium">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="credit-card" data-lucide="credit-card" class="lucide lucide-credit-card w-4 h-4 text-slate-500 mr-2">
                        <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                        <line x1="1" y1="10" x2="23" y2="10"></line>
                    </svg> Tỉ giá(VNĐ):
                    <div class="ml-auto">{{number_format($detail->cny,'0',',','.')}} </div>
                </div>
                <div class="flex items-center border-t border-slate-200/60 dark:border-darkmode-400 pt-5 mt-5 font-medium">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="credit-card" data-lucide="credit-card" class="lucide lucide-credit-card w-4 h-4 text-slate-500 mr-2">
                        <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                        <line x1="1" y1="10" x2="23" y2="10"></line>
                    </svg> Tổng tiền hàng(¥):
                    <div class="ml-auto">{{$detail->total}} ¥</div>
                </div>
                <div class="flex items-center border-t border-slate-200/60 dark:border-darkmode-400 pt-5 mt-5 font-medium">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="credit-card" data-lucide="credit-card" class="lucide lucide-credit-card w-4 h-4 text-slate-500 mr-2">
                        <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                        <line x1="1" y1="10" x2="23" y2="10"></line>
                    </svg> Tổng tiền khách mua(VNĐ):
                    <div class="ml-auto text-danger font-bold">{{number_format($detail->cny*$detail->total,'0',',','.')}}</div>
                </div>
            </div>
            @if(!empty($detail->total_price_vnd_final))
            <div class="box intro-y p-5 mt-5">
                <div class="flex items-center border-b border-slate-200/60 dark:border-darkmode-400 pb-5 mb-5">
                    <div class="font-medium text-base truncate">Kết đơn hàng</div>
                </div>
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="credit-card" data-lucide="credit-card" class="lucide lucide-credit-card w-4 h-4 text-slate-500 mr-2">
                        <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                        <line x1="1" y1="10" x2="23" y2="10"></line>
                    </svg>
                    Phí nội địa(¥) 运费:
                    <div class="ml-auto">{{$detail->fee}}</div>
                </div>
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="credit-card" data-lucide="credit-card" class="lucide lucide-credit-card w-4 h-4 text-slate-500 mr-2">
                        <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                        <line x1="1" y1="10" x2="23" y2="10"></line>
                    </svg>
                    Tổng tiền hàng(¥) 货款:
                    <div class="ml-auto">{{$detail->total_price_cny_final}}</div>
                </div>
                <div class="flex items-center border-t border-slate-200/60 dark:border-darkmode-400 pt-5 mt-5 font-medium">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="credit-card" data-lucide="credit-card" class="lucide lucide-credit-card w-4 h-4 text-slate-500 mr-2">
                        <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                        <line x1="1" y1="10" x2="23" y2="10"></line>
                    </svg> Tổng tiền(VNĐ):
                    <div class="ml-auto text-success font-bold">{{number_format($detail->total_price_vnd_final,'0',',','.')}}</div>
                </div>
            </div>
            @endif

        </div>
        <!-- END: Order Detail Side Menu -->
        <!-- BEGIN: Order Detail Content -->
        <div class="col-span-12 lg:col-span-8">
            <div class="box intro-y p-5">
                <div class="flex items-center border-b border-slate-200/60 dark:border-darkmode-400 pb-5 mb-5">
                    <div class="font-medium text-base truncate">Liên kết sản phẩm 链接</div>
                </div>
                <div>
                    @if(!empty($links))
                    <div class="space-y-3" id="formData">
                        @foreach($links as $link)
                        @if(!empty($link))
                        <div class="space-y-3 ">
                            <div class="grid grid-cols-1 ">
                                <a target="_blank" href="{{$link}}" class="col-span-1 outline-none focus:outline-none hover:outline-none underline" style="overflow: hidden;">
                                    {{$link}}
                                </a>
                            </div>
                        </div>
                        @endif
                        @endforeach
                    </div>
                    @endif

                </div>
            </div>
            @if(!empty($links))
            <div class="box intro-y p-5 mt-3">
                <div class="flex items-center border-b border-slate-200/60 dark:border-darkmode-400 pb-5 mb-5">
                    <div class="font-medium text-base truncate">Cập nhập liên kết sản phẩm 链接</div>
                </div>
                <div>

                        <div class="space-y-3" id="formData">
                            @foreach($links as $link)
                                @if(!empty($link))
                                    <div class="space-y-3 ">
                                        <div class="grid grid-cols-1 ">
                                            <input type="text" value="{{$link}}" name="links[]">
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>

                        <div class="text-right mt-5">
                            <a href="javascript:void(0)" class="btn btn-primary js_update_link">Cập nhập</a>
                        </div>
                </div>
            </div>
            @endif
            <div class="box intro-y p-5 mt-3">
                <div class="flex items-center border-b border-slate-200/60 dark:border-darkmode-400 pb-5 mb-5">
                    <div class="font-medium text-base truncate">Hình ảnh 图片</div>
                </div>
                <div>
                    @if(!empty($images))
                    <div class="grid grid-cols-6 flex-wrap gap-3">
                        @foreach($images as $img)
                        @if(!empty($img))
                        <a href="{{asset($img)}}" data-fancybox="images" rel='group{{$detail->id}}'>
                            <img src="{{asset($img)}}" class="object-cover w-full" style="height: 100px;" />
                        </a>
                        @endif
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
            <!-- Chỉnh sửa giá -->
            @can('customer_orders_edit_price')
            @if($detail->status == 'wait' || $detail->status == 'pending_payment')
            <div class="box intro-y p-5 mt-3">
                <div class="flex items-center border-b border-slate-200/60 dark:border-darkmode-400 pb-5 mb-5">
                    <div class="font-medium text-base truncate">Chỉnh sửa giá</div>
                </div>
                <div>
                    <div class="mt-3">
                        <label class="form-label text-base font-semibold">Phí nội địa 运费(¥)</label>
                        <?php echo Form::text('fee', $detail->fee, ['class' => 'form-control w-full', 'placeholder' => '']); ?>
                    </div>
                    <div class="mt-3">
                        <label class="form-label text-base font-semibold">Tiền hàng 货款(¥)</label>
                        <?php echo Form::text('total_price_cny_final', !empty($detail->total_price_cny_final) ? $detail->total_price_cny_final : $detail->total, ['class' => 'form-control w-full ', 'placeholder' => '']); ?>
                    </div>
                    <div class="mt-3">
                        <label class="form-label text-base font-semibold">Tổng tiền khách mua(VNĐ)</label>
                        <?php echo Form::text('total_price_vnd_final', !empty($detail->total_price_vnd_final) ? number_format($detail->total_price_vnd_final, '0', ',', '.') : number_format($detail->total_price_old, '0', ',', '.'), ['class' => 'form-control w-full int', 'placeholder' => '', 'disabled']); ?>
                    </div>
                    <div class="text-right mt-5">
                        <a href="javascript:void(0)" class="btn btn-primary js_update_price">Cập nhập giá</a>
                    </div>
                </div>

            </div>
            @endif
            @endcan
            <!--END: Chỉnh sửa giá -->
            <!-- Ghi chú -->
            @can('customer_orders_edit')
            <div class="box intro-y p-5 mt-3">
                <div class="flex items-center border-b border-slate-200/60 dark:border-darkmode-400 pb-5 mb-5">
                    <div class="font-medium text-base truncate">Ghi chú nội bộ 内部备注</div>
                </div>
                <div>
                    <div class="mt-3">
                        <?php echo Form::textarea('message', $detail->message, ['class' => 'form-control w-full', 'placeholder' => '']); ?>
                    </div>
                    <div class="text-right mt-5">
                        <a href="javascript:void(0)" class="btn btn-primary js_update_message">Cập nhập ghi chú</a>
                    </div>
                </div>
            </div>
            @endcan
            <!--END Ghi chú -->
        </div>
        <!-- END: Order Detail Content -->
    </div>
</div>

@endsection
@push('javascript')
<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js" integrity="sha512-uURl+ZXMBrF4AwGaWmEetzrd+J5/8NRkWAvJx5sbPSSuOb0bZLqf+tOzniObO00BjHa/dD7gub9oCGMLPQHtQA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css" integrity="sha512-H9jrZiiopUdsLpg94A333EfumgUBpO9MdbxStdeITo+KEIMaNfHNvwyjjDJb+ERPaRS6DpyRlKbvPUasNItRyw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<script>
    $('a[rel="group<?php echo $detail->id ?>"]').fancybox({
        thumbs: {
            autoStart: true
        },
        buttons: [
            'zoom',
            'close'
        ]
    });
    $('a[rel="groupQ"]').fancybox({
        thumbs: {
            autoStart: true
        },
        buttons: [
            'zoom',
            'close'
        ]
    });
</script>
<script>
    $(document).on('change keyup', 'input[name="fee"]', function(e) {
        loadPrice()
    })
    $(document).on('change keyup', 'input[name="total_price_cny_final"]', function(e) {
        loadPrice()
    })

    function loadPrice() {
        var fee = $('input[name="fee"]').val()
        var total_price_cny_final = $('input[name="total_price_cny_final"]').val()
        var total_price_vnd_final = 0;
        total_price_vnd_final = (parseFloat(total_price_cny_final) + parseFloat(fee)) * parseFloat(<?php echo $detail->cny ?>)
        $('input[name="total_price_vnd_final"]').val(numberWithCommas(total_price_vnd_final))
    }
    $(document).on('click', '.js_update_message', function(e) {
        e.preventDefault();
        var message = $('textarea[name="message"]').val();
        let param = {
            message: message,
        };
        /* swal({
                title: "Hãy chắc chắn rằng bạn muốn thực hiện thao tác này?",
                text: "",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Thực hiện!",
                cancelButtonText: "Hủy bỏ!",
                closeOnConfirm: false,
                closeOnCancel: false,
            },
            function(isConfirm) {
                if (isConfirm) {


                } else {
                    swal({
                        title: "Hủy bỏ",
                        text: "Thao tác bị hủy bỏ",
                        type: "error",
                    });
                }
            }
        );*/
        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                    "content"
                ),
            },
            url: "<?php echo route('customer_orders.update') ?>",
            type: "POST",
            dataType: "JSON",
            data: {
                id: <?php echo $detail->id ?>,
                message: param.message
            },
            success: function(data) {
                if (data.error) {
                    swal({
                        title: "Lỗi",
                        text: data.error,
                        type: "error",
                    });
                } else {
                    swal({
                            title: "Cập nhập ghi chú!",
                            text: "",
                            type: "success",
                        },
                        function() {
                            location.reload();
                        });
                }
            },
        });
    })
    $(document).on('click', '.js_update_price', function(e) {
        e.preventDefault();
        var fee = $('input[name="fee"]').val();
        var total_price_cny_final = $('input[name="total_price_cny_final"]').val();
        let param = {
            fee: fee,
            total_price_cny_final: total_price_cny_final,
        };
        /*swal({
                title: "Hãy chắc chắn rằng bạn muốn thực hiện thao tác này?",
                text: "",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Thực hiện!",
                cancelButtonText: "Hủy bỏ!",
                closeOnConfirm: false,
                closeOnCancel: false,
            },
            function(isConfirm) {
                if (isConfirm) {

                } else {
                    swal({
                        title: "Hủy bỏ",
                        text: "Thao tác bị hủy bỏ",
                        type: "error",
                    });
                }
            }
        );*/
        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                    "content"
                ),
            },
            url: "<?php echo route('customer_orders.update_price') ?>",
            type: "POST",
            dataType: "JSON",
            data: {
                id: <?php echo $detail->id ?>,
                fee: param.fee,
                total_price_cny_final: param.total_price_cny_final,
            },
            success: function(data) {
                if (data.error) {
                    swal({
                        title: "Error",
                        text: data.error,
                        type: "error",
                    });
                } else {
                   /* if(data.tokenIos){
                        var formdata = new FormData();
                        formdata.append("to",data.tokenIos);
                        formdata.append("title", "Đơn hàng #<?php echo $detail->code?>");
                        formdata.append("body", "Số tiền "+data.priceFinal+ ' VND');
                        formdata.append("subtitle", "Đơn hàng đang chờ thanh toán");
                        formdata.append("sound", "default");
                        var requestOptions = {
                            method: 'POST',
                            body: formdata,
                            redirect: 'follow'
                        };
                        fetch("https://exp.host/--/api/v2/push/send", requestOptions)
                            .then(response => response.text())
                    .then(result => console.log(result))
                    .catch(error => console.log('error', error));
                    }*/

                    swal({
                            title: "Cập nhập giá ghi chú!",
                            text: "",
                            type: "success",
                        },
                        function() {
                            location.reload();
                        });
                }
            },
        });

    })
    $(document).on('click', '.js_handle_completed', function(e) {
        var status = $(this).attr('data-status')
        let mavandon = []; /*Lấy id bản ghi */
        $('input[name="mavandon[]"]').each(function() {
            mavandon.push($(this).val());
        });
        /*swal({
                title: "Chuyển trạng thái thành Đã mua hàng",
                text: "",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Thực hiện!",
                cancelButtonText: "Hủy bỏ!",
                closeOnConfirm: false,
                closeOnCancel: false,
            },
            function(isConfirm) {
                if (isConfirm) {


                } else {
                    swal({
                        title: "Hủy bỏ",
                        text: "Thao tác bị hủy bỏ",
                        type: "error",
                    });
                }
            }
        );*/
        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                    "content"
                ),
            },
            url: "<?php echo route('customer_orders.update_status_completed') ?>",
            type: "POST",
            dataType: "JSON",
            data: {
                id: <?php echo $detail->id ?>,
                status: status,
                mavandon: mavandon,
            },
            success: function(data) {
                if (data.error) {
                    swal({
                        title: "Error",
                        text: data.error,
                        type: "error",
                    });
                } else {
                    swal({
                            title: "Cập nhập trạng thái thành công!",
                            text: "",
                            type: "success",
                        },
                        function() {
                            location.reload();
                        });
                }
            },
        });
    })
    $(document).on('click', '.js_handle_completed_order', function(e) {
        /*swal({
                title: "Chuyển trạng thái thành Đã mua hàng",
                text: "",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Thực hiện!",
                cancelButtonText: "Hủy bỏ!",
                closeOnConfirm: false,
                closeOnCancel: false,
            },
            function(isConfirm) {
                if (isConfirm) {


                } else {
                    swal({
                        title: "Hủy bỏ",
                        text: "Thao tác bị hủy bỏ",
                        type: "error",
                    });
                }
            }
        );*/
        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                    "content"
                ),
            },
            url: "<?php echo route('customer_orders.update_status') ?>",
            type: "POST",
            dataType: "JSON",
            data: {
                id: <?php echo $detail->id ?>,
                status: "completed_order",
            },
            success: function(data) {
                if (data.error) {
                    swal({
                        title: "Error",
                        text: data.error,
                        type: "error",
                    });
                } else {
                    swal({
                            title: "Cập nhập trạng thái Đã mua hàng thành công!",
                            text: "",
                            type: "success",
                        },
                        function() {
                            location.reload();
                        });
                }
            },
        });
    })
    $(document).on('click', '.js_update_link', function(e) {
        let links = []; /*Lấy id bản ghi */
        $('input[name="links[]"]').each(function() {
            links.push($(this).val());
        });
        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                    "content"
                ),
            },
            url: "<?php echo route('customer_orders.update_links') ?>",
            type: "POST",
            dataType: "JSON",
            data: {
                links: links,
                id: <?php echo $detail->id?>,
            },
            success: function(data) {
                if (data.error) {
                    swal({
                        title: "Error",
                        text: data.error,
                        type: "error",
                    });
                } else {
                    swal({
                            title: "Cập nhập link mua hàng thành công!",
                            text: "",
                            type: "success",
                        },
                        function() {
                            location.reload();
                        });
                }
            },
        });
    })
</script>
<script>
    var stt = 0;

    function loadHTML() {
        stt++;
        var html = '<div class="flex items-center space-x-1">';
        html += '<input value="" class="form-control w-full mavandon mavandon-' + stt + ' " placeholder="MÃ VẬN ĐƠN 运单号"  data-stt="' + stt + '" name="mavandon[]" type="text" value="">';
        html += '<a href="javascript:void(0)" class="js_removeColumn text-danger font-bold">Xóa</a>';
        html += '</div>'
        return html
    }
    $(document).on('keypress', '.mavandon', function(e) {
        var key = e.which;
        if (key == 13) {
            e.preventDefault()
            $('#list').append(loadHTML());
            $('.mavandon').focus()
        }
    })
    $(document).on('click', '.js_removeColumn', function(e) {
        e.preventDefault()
        $(this).parent().remove();
    })

</script>
<style>
    .mt-1\.5 {
        margin-top: 0.375rem;
    }

    .-left-1\.5 {
        left: -0.375rem;
    }
</style>

@endpush
