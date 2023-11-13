@extends('homepage.layout.home')
@section('content')
{!!htmlBreadcrumb($seo['meta_title'])!!}
<main class="pb-[250px] pt-8">
    <div class="container px-4 mx-auto">
        <div class="mt-4 flex flex-col md:flex-row items-start lg:space-x-8">
            @include('customer/frontend/auth/common/sidebar')
            <div class="flex-1 overflow-x-hidden shadowC rounded-xl w-full md:w-auto order-1 md:order-2">
                <div class="p-6 bg-white space-y-3 pb-[150px]">
                    <div class="flex md:items-center flex-col md:flex-row justify-between">
                        <div class="w-full lg:w-2/3 flex flex-col">
                            <h1 class=" text-black font-bold text-xl">Chi tiết đơn hàng #{{$detail->code}}</h1>

                        </div>
                        <div class="w-full lg:w-1/3 flex flex-col justify-end items-end space-y-1">
                            <div class="flex space-x-1 justify-end">
                                <span class="text-white rounded-md text-center px-2" style="background-color: {{config('cart')['class'][$detail->status]}};"> {{config('cart')['status'][$detail->status]}}</span>
                                @if(!empty($detail->status_return))
                                <span style="background-color: {{config('cart')['class'][$detail->status_return]}};" class="text-center text-white px-2 py-1 rounded text-xs">#{{config('cart')['status_return'][$detail->status_return]}}</span>
                                @endif
                            </div>
                            <i>{{$detail->created_at}}</i>
                        </div>
                    </div>
                    <div class="space-y-5">
                        <div class="space-y-3">
                            @if(!empty($detail->mavandon))
                            <div class="grid grid-cols-3 md:grid-cols-5 border-b pb-2 space-x-3">
                                <label class="font-semibold">Mã vận đơn</label>
                                <div class="col-span-2 md:col-span-4 outline-none focus:outline-none hover:outline-none">
                                    <div class="flex flex-col">
                                        <?php
                                        $mavandonShow = explode(',', $detail->mavandon);
                                        ?>
                                        @if(!empty($mavandonShow))
                                        @foreach($mavandonShow as $val)
                                        <span class="underline ml-1 font-bold text-red-600">{{$val}}</span>
                                        @endforeach
                                        @endif
                                    </div>

                                </div>
                            </div>
                            @endif
                            <div class="grid grid-cols-3 md:grid-cols-5 border-b pb-2 space-x-3">
                                <label class="font-semibold">Tên sản phẩm</label>
                                <span class="col-span-2 md:col-span-4 outline-none focus:outline-none hover:outline-none">
                                    {{$detail->title}}
                                </span>
                            </div>
                            <div class="grid grid-cols-3 md:grid-cols-5 border-b pb-2 space-x-3">
                                <label class="font-semibold">Kích thước tiêu chuẩn</label>
                                <span class="col-span-2 md:col-span-4 outline-none focus:outline-none hover:outline-none">
                                    {{$detail->weight}}
                                </span>
                            </div>
                            <div class="grid grid-cols-3 md:grid-cols-5 border-b pb-2 space-x-3">
                                <label class="font-semibold">Số lượng</label>
                                <span class="col-span-2 md:col-span-4 outline-none focus:outline-none hover:outline-none">
                                    {{$detail->quantity}}
                                </span>
                            </div>
                            <div class="grid grid-cols-3 md:grid-cols-5 border-b pb-2 space-x-3">
                                <label class="font-semibold">Đơn giá (¥)</label>
                                <span class="col-span-2 md:col-span-4 outline-none focus:outline-none hover:outline-none">
                                    {{$detail->price}}
                                </span>
                            </div>
                            <div class="grid grid-cols-3 md:grid-cols-5 border-b pb-2 space-x-3">
                                <label class="font-semibold">Tổng tiền hàng (¥)</label>
                                <span class="col-span-2 md:col-span-4 outline-none focus:outline-none hover:outline-none">
                                    {{$detail->total}}
                                </span>
                            </div>
                            <div class="grid rid-cols-3 md:grid-cols-5 border-b pb-2 space-x-3">
                                <label class="font-semibold">Ghi chú</label>
                                <span class="col-span-2 md:col-span-4 outline-none focus:outline-none hover:outline-none">
                                    {{$detail->note}}
                                </span>
                            </div>


                        </div>
                        <?php
                        $links = json_decode($detail->links, true);
                        $images = json_decode($detail->images, true);
                        $links_return = json_decode($detail->links_return, true);
                        $images_return = json_decode($detail->images_return, true);
                        ?>
                        @if(!empty($links))
                        <div class="space-y-3 p-5 border rounded-md shadow">
                            <div class="space-y-3" id="formData">
                                @foreach($links as $link)
                                @if(!empty($link))
                                <div class="space-y-3 ">
                                    <div class="grid grid-cols-5 border-b pb-2 space-x-3">
                                        <label class="font-semibold">Liên kết sản phẩm</label>
                                        <a href="{{$link}}" class="col-span-4 outline-none focus:outline-none hover:outline-none underline overflow-hidden" target="_blank">
                                            {{$link}}
                                        </a>
                                    </div>
                                </div>
                                @endif
                                @endforeach
                            </div>
                        </div>
                        @endif
                        @if(!empty($images))
                        <div class="grid grid-cols-1 space-y-3">
                            <label class="font-semibold">Hình ảnh</label>
                            @if(!empty($images))
                            <div class="grid grid-cols-2 md:grid-cols-6 flex-wrap gap-3">
                                @foreach($images as $img)
                                @if(!empty($img))
                                <a href="{{asset($img)}}" data-fancybox="images" rel='group{{$detail->id}}'>
                                    <img src="{{asset($img)}}" class="h-[70px] object-contain w-full" />
                                </a>
                                @endif
                                @endforeach
                            </div>
                            @endif
                        </div>
                        @endif


                        <!-- KHiếu nại/hoàn tiền -->
                        @if(!empty($detail->status_return))
                        <div class="flex md:items-center flex-col md:flex-row justify-between">
                            <h2 class="text-black font-bold text-xl">Khiếu nại/hoàn tiền</h2>
                        </div>
                        @if(!empty($links_return))
                        <div class="space-y-3 p-5 border rounded-md shadow">
                            <div class="space-y-3" id="formData">
                                @foreach($links_return as $link)
                                @if(!empty($link))
                                <div class="space-y-3 ">
                                    <div class="grid grid-cols-5 border-b pb-2 space-x-3">
                                        <label class="font-semibold">Liên kết sản phẩm</label>
                                        <a href="{{$link}}" class="col-span-4 outline-none focus:outline-none hover:outline-none underline overflow-hidden" target="_blank">
                                            {{$link}}
                                        </a>
                                    </div>
                                </div>
                                @endif
                                @endforeach
                            </div>
                        </div>
                        @endif
                        @if(!empty($images_return))
                        <div class="grid grid-cols-1 space-y-3">
                            <label class="font-semibold">Hình ảnh</label>
                            @if(!empty($images_return))
                            <div class="grid grid-cols-2 md:grid-cols-6 flex-wrap gap-3">
                                @foreach($images as $img)
                                @if(!empty($img))
                                <a href="{{asset($img)}}" data-fancybox="images" rel='groupQ'>
                                    <img src="{{asset($img)}}" class="h-[70px] object-contain w-full" />
                                </a>
                                @endif
                                @endforeach
                            </div>
                            @endif
                        </div>
                        @endif
                        @if(!empty($detail->message_return) && $detail->status == 'returns')
                        <div class="grid grid-cols-5 border-b pb-2 space-x-3">
                            <label class="font-semibold">Ghi chú (khiếu nại/hoàn tiền)</label>
                            <span class="col-span-4 outline-none focus:outline-none hover:outline-none">
                                {{$detail->message_return}}
                            </span>
                        </div>
                        @endif
                        @endif
                        <!--END: KHiếu nại/hoàn tiền -->

                        <!-- Lịch sử đơn hàng -->
                        <div class="flex md:items-center flex-col md:flex-row justify-between">
                            <h2 class="text-black font-bold text-xl">Trạng thái</h2>
                        </div>
                        <ol class="relative border-l border-gray-200 dark:border-gray-700">
                            <li class="mb-3 ml-4">
                                <div class="absolute w-3 h-3 bg-gray-200 rounded-full mt-1.5 -left-1.5 border border-white dark:border-gray-900 dark:bg-gray-700"></div>
                                <time class="mb-1 text-sm font-normal leading-none text-gray-400 dark:text-gray-500">{{$detail->created_at}}</time>
                                <p class="text-base font-normal">Tạo đơn hàng</p>
                            </li>
                            @if(!empty($detail->customer_status_histories) && count($detail->customer_status_histories) > 0)
                            @foreach($detail->customer_status_histories as $item)
                            <li class="mb-3 ml-4">
                                <div class="absolute w-3 h-3 bg-gray-200 rounded-full mt-1.5 -left-1.5 border border-white dark:border-gray-900 dark:bg-gray-700"></div>
                                <time class="mb-1 text-sm font-normal leading-none text-gray-400 dark:text-gray-500">{{$item->created_at}}</time>
                                <p class="text-base font-normal">{{$item->message}}</p>
                            </li>
                            @endforeach
                            @endif
                        </ol>
                        <!-- END: lịch sử đơn hàng -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="fixed bottom-0 lg:bottom-0 left-0 w-full z-[999]">
        <div class="container mx-auto md:px-4">
            <div class="flex flex-col lg:flex-row items-start lg:space-x-8">
                <div class="hidden md:block w-full md:w-full lg:w-[376px] order-2 md:order-1 mt-10 md:mt-0">
                </div>
                <div class="flex-1 flex justify-between items-center overflow-x-hidden w-full md:w-full order-1 md:order-2 py-5 text-white bg_gradient p-4 md:rounded-lg">
                    <div class="w-1/2 flex flex-col space-y-1">
                        <div>Giá tệ (VNĐ): <span class="font-bold">{{number_format($detail->cny,'0',',','.')}}</span></div>
                        <div>Tổng tiền khách mua (¥): <span class="font-bold"> {{$detail->total}}</span></div>
                        <div>Tổng tiền khách mua (VNĐ): <span class="font-bold">{{number_format($detail->total*$detail->cny,'0',',','.')}}</span></div>
                        @if(!empty($detail->total_price_vnd_final))
                        <div>
                            <div>Phí nội địa(¥): <span class="font-bold">{{$detail->fee}}</span></div>
                            <div>Tổng tiền hàng(¥): <span class="font-bold total_price_cny">{{$detail->total_price_cny_final}}</span>
                            </div>
                            <div>Tổng tiền thanh toán(VNĐ): <span class="font-bold total_price_vnd">{{number_format($detail->total_price_vnd_final,'0',',','.')}}</span>
                            </div>
                            @if($detail->status_return == 'wait')
                            <div>Tổng tiền chờ hoàn(VNĐ): <span class="font-bold total_price_vnd">{{number_format($detail->price_return,'0',',','.')}}</span>
                            </div>
                            @elseif($detail->status_return == 'completed')
                            <div>Tổng tiền yêu cầu hoàn(VNĐ): <span class="font-bold total_price_vnd">{{number_format($detail->price_return,'0',',','.')}}</span>
                            </div>
                            <div>Tổng tiền hoàn(VNĐ): <span class="font-bold total_price_vnd">{{number_format($detail->price_return_success,'0',',','.')}}</span>
                            </div>
                            @endif
                        </div>
                        @endif
                    </div>
                    <div class="w-1/2 lg:space-x-1 space-y-1 lg:space-y-0 justify-end flex flex-col md:flex-row">
                        <!-- hiển thị nút hủy -->
                        @if($detail->status === 'wait' || $detail->status === 'pending_payment')
                        <a href="javascript:void(0)" data-status="canceled" style="background-color: {{config('cart')['class']['canceled']}};" class="js_handle_status text-center text-white outline-none focus:outline-none hover:outline-none font-medium rounded-lg text-sm px-4 lg:px-5 py-2 lg:py-2.5">
                            Hủy đơn hàng
                        </a>
                        @endif
                        <!--END: hiển thị nút hủy -->
                        <!-- hiển thị nút thanh toán -->
                        @if($detail->status === 'pending_payment')
                        <a href="javascript:void(0)" class="ajax-order-store text-white text-center bg-red-600 outline-none focus:outline-none hover:outline-none font-medium rounded-lg text-sm px-4 lg:px-5 py-2 lg:py-2.5">
                            Thanh toán
                        </a>
                        @endif
                        <!--END: hiển thị nút thanh toán -->
                        @if($detail->status == 'completed_order' || $detail->status == 'pending')
                        <a href="javascript:void(0)" data-status="completed" style="background-color: {{config('cart')['class']['completed']}};" class="js_handle_status text-center text-white outline-none focus:outline-none hover:outline-none font-medium rounded-lg text-sm px-4 lg:px-5 py-2 lg:py-2.5" data-status="completed">Đã nhận hàng</a>

                        @endif

                        @if(empty($detail->status_return))
                        @if($detail->status == 'pending_order' || $detail->status == 'completed_order' || $detail->status == 'pending' || $detail->status == 'completed')
                        <a href="{{route('ordersF.return',['id' => $detail->id])}}" style="background-color: {{config('cart')['class']['returns']}};" class="js_handle_returns text-center text-white outline-none focus:outline-none hover:outline-none font-medium rounded-lg text-sm px-4 lg:px-5 py-2 lg:py-2.5">Hoàn
                            tiền</a>
                        @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
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
    $(".menu_item_auth:eq(2)").addClass('active');
</script>
<script>
    $(document).on('click', '.ajax-order-store', function(e) {
        <?php if (Auth::guard('customer')->user()->price < $detail->total_price_vnd_final) { ?>
            swal({
                    title: "Số dư trong tài khoản của bạn không đủ",
                    text: "",
                    type: "error",
                    confirmButtonText: "Nạp tiền!",
                },
                function() {
                    window.location.replace("<?php echo route('customer_payment.frontend_index', ['modal' => 'show']) ?>");
                }
            );
        <?php } else { ?>
            swal({
                    title: "Hãy chắc chắn rằng bạn muốn thực hiện thao tác này?",
                    text: '',
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
                        let formURL = "<?php echo route('ordersF.store_payment') ?>";
                        $.ajax({
                            headers: {
                                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                            },
                            url: formURL,
                            type: "POST",
                            dataType: "JSON",
                            data: {
                                id: <?php echo $detail->id ?>,
                            },
                            success: function(data) {
                                if (data.code === 200) {
                                    swal({
                                            title: "Thành công!",
                                            text: "Thanh toán đơn hàng thành công.",
                                            type: "success",
                                        },
                                        function() {
                                            location.reload();
                                        }
                                    );
                                } else {
                                    swal({
                                        title: "Có vấn đề xảy ra",
                                        text: "Vui lòng thử lại",
                                        type: "error",
                                    });
                                }
                            },
                            error: function(jqXhr, json, errorThrown) {
                                var errors = jqXhr.responseJSON;
                                var errorsHtml = "";
                                $.each(errors["errors"], function(index, value) {
                                    errorsHtml += value + "/ ";
                                });
                                swal({
                                    title: "ERROR",
                                    text: errorsHtml,
                                    type: "error",
                                });
                            },
                        });
                    } else {
                        swal({
                            title: "Hủy bỏ",
                            text: "Thao tác bị hủy bỏ",
                            type: "error",
                        });
                    }
                }
            );

        <?php } ?>
    })
    $(document).on('click', '.js_handle_status', function(e) {
        var status = $(this).attr('data-status');
        swal({
                title: "Hãy chắc chắn rằng bạn muốn thực hiện thao tác này?",
                text: '',
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
                    let formURL = "<?php echo route('ordersF.update_status') ?>";
                    $.ajax({
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                        },
                        url: formURL,
                        type: "POST",
                        dataType: "JSON",
                        data: {
                            id: <?php echo $detail->id ?>,
                            status: status
                        },
                        success: function(data) {
                            if (data.code === 200) {
                                swal({
                                        title: "Thành công!",
                                        text: "Thanh toán đơn hàng thành công.",
                                        type: "success",
                                    },
                                    function() {
                                        location.reload();
                                    }
                                );
                            } else {
                                swal({
                                    title: "Có vấn đề xảy ra",
                                    text: "Vui lòng thử lại",
                                    type: "error",
                                });
                            }
                        },
                        error: function(jqXhr, json, errorThrown) {
                            var errors = jqXhr.responseJSON;
                            var errorsHtml = "";
                            $.each(errors["errors"], function(index, value) {
                                errorsHtml += value + "/ ";
                            });
                            swal({
                                title: "ERROR",
                                text: errorsHtml,
                                type: "error",
                            });
                        },
                    });
                } else {
                    swal({
                        title: "Hủy bỏ",
                        text: "Thao tác bị hủy bỏ",
                        type: "error",
                    });
                }
            }
        );
    })
</script>
@endpush