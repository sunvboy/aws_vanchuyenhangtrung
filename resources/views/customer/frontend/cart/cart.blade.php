@extends('homepage.layout.home')
@section('content')
{!!htmlBreadcrumb($seo['meta_title'])!!}
<main class="py-8">
    <div class="container px-4 mx-auto">
        <div class="mt-4 flex flex-col md:flex-row items-start lg:space-x-8">
            @include('customer/frontend/auth/common/sidebar')
            <div class="flex-1 overflow-x-hidden w-full md:w-auto order-1 md:order-2">
                <div class=" bg-white space-y-3 pb-[150px]">
                    <div class="flex items-center justify-between">
                        <h1 class="text-black font-bold text-xl">Giỏ hàng (<span class="cart_total_item">{{$data->count()}}</span>)</h1>
                        <a href="{{route('cartF.create')}}" class="text-white bg_gradient font-medium rounded-lg text-sm px-4 lg:px-5 py-2 lg:py-2.5 dark:bg-primary-600 outline-none focus:outline-none hover:outline-none">Thêm giỏ hàng</a>
                    </div>
                    <div class="space-y-3" id="table">
                        @if(!$data->isEmpty())
                        @foreach($data as $key=>$item)
                        <div class="flex rounded-lg bg-gray-100 p-5 space-x-4 col_{{$item->id}}">
                            <div class="w-5">
                                <label for="checkbox-item-quyen-{{$key}}" class="flex items-center cursor-pointer relative mt-1">
                                    <input type="checkbox" value="{{$item->id}}" id="checkbox-item-quyen-{{$key}}" class="w-5 h-5 rounded-full mt-1 cursor-default checkbox-item-quyen checkboxC">
                                    <span class="checkmark border border-gray-300"></span>
                                </label>
                            </div>
                            <div class="flex-1 space-y-2">
                                <div class="flex justify-between">
                                    <div class="flex-1 space-y-1">
                                        <div class="font-bold">{{$item->title}}</div>
                                        <div>{{!empty($item->weight)?$item->weight:''}}</div>
                                        <div>
                                            <span class="price_vnd_<?php echo $item->id ?>">{{number_format($item->total * $cny->content,'0',',','.')}} VNĐ</span>
                                            (<span class="price_cny_<?php echo $item->id ?>">¥{{$item->total}}</span>)
                                        </div>
                                    </div>
                                    <div>
                                        <a href="javascript:void(0)" class="flex items-center space-x-1 text-red-600 font-bold js_delete_cart" data-id="{{$item->id}}">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-red-600">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                            </svg>
                                            <span>Xóa</span>
                                        </a>
                                    </div>
                                </div>
                                <div class="custom-number-input h-10 w-32 flex flex-row rounded-lg relative bg-transparent mt-1">
                                    <button class="card-dec border-r bg-white text-gray-600 hover:text-gray-700 hover:bg-gray-400 h-full w-20 rounded-l cursor-pointer outline-none leading-[50px]">
                                        <span class="m-auto text-2xl font-thin">−</span>
                                    </button>
                                    <input data-id="{{$item->id}}" type="number" class="tp_cardQuantity outline-none focus:outline-none text-center w-full bg-white font-semibold text-md hover:text-black focus:text-black md:text-basecursor-default flex items-center text-gray-700" name="custom-input-number" value="{{$item->quantity}}" />
                                    <button class="card-inc border-l bg-white text-gray-600 hover:text-gray-700 hover:bg-gray-400 h-full w-20 rounded-r cursor-pointer leading-[50px]">
                                        <span class="m-auto text-2xl font-thin">+</span>
                                    </button>
                                </div>
                                <?php
                                $links = json_decode($item->links, true);
                                $images = json_decode($item->images, true);
                                ?>
                                @if(!empty($images))
                                <div class="grid grid-cols-2 md:grid-cols-6 flex-wrap gap-3">
                                    @foreach($images as $img)
                                    @if(!empty($img))
                                    <a href="{{asset($img)}}" data-fancybox="images" rel='group{{$item->id}}'>
                                        <img src="{{asset($img)}}" class="h-[70px] object-cover w-full" />
                                    </a>
                                    @endif
                                    @endforeach
                                </div>
                                @endif
                                @if(!empty($links))
                                <div>
                                    <ul>
                                        @foreach($links as $link)
                                        @if(!empty($link))
                                        <li><a href="{{$link}}" class="text-blue-500 hover:text-red-600" style="word-break: break-all;text-decoration: revert;" target="_blank">{{$link}}</a></li>
                                        @endif
                                        @endforeach
                                    </ul>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                        @else
                        <div class="flex justify-center">
                            <span class="text-lg text-gray-300">Đơn hàng không tồn tại</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if(!$data->isEmpty())
    <div class="fixed bottom-0 lg:bottom-0 left-0 w-full z-[999]">
        <div class="container mx-auto lg:px-4">
            <div class="flex flex-col lg:flex-row items-start lg:space-x-8">
                <div class="hidden lg:block w-full md:w-full lg:w-[376px] mt-10 md:mt-0">
                </div>
                <div class="flex-1 w-full lg:w-auto">
                    <div class="flex justify-between items-center overflow-x-hidden w-full md:w-full order-1 md:order-2 py-5 text-white bg_gradient p-4 md:rounded-lg">
                        <div class="flex flex-col space-y-1">
                            <label for="checkbox-all-quyen" class="flex items-center cursor-pointer relative">
                                <input id="checkbox-all-quyen" type="checkbox" class="w-5 h-5 rounded-full mt-1 border-0 checkboxC">
                                <span class="checkmark"></span>
                                <span class="pl-8">Chọn tất cả</span>
                            </label>
                            <div>
                                <div>Giá tệ: <span class="font-bold">{{number_format($cny->content,'0',',','.')}}</span> VNĐ</div>
                                <div>Tổng tiền(CNY): <span class="font-bold total_price_cny">{{$data->sum('total')}}</span> ¥</div>
                                <div>Tổng tiền(VNĐ): <span class="font-bold total_price_vnd">{{number_format($data->sum('total')*$cny->content,'0',',','.')}}</span> VNĐ</div>
                            </div>
                        </div>
                        <div class="md:space-x-1 space-y-1 md:space-y-0 flex flex-col md:flex-row text-center">
                            <a href="javascript:void(0)" class="ajax-delete-all text-white bg-red-900 outline-none focus:outline-none hover:outline-none font-medium rounded-lg text-sm px-4 lg:px-5 py-2 lg:py-2.5">
                                Xóa
                            </a>
                            <button type="submit" class="ajax-order-store text-white bg-red-600 outline-none focus:outline-none hover:outline-none font-medium rounded-lg text-sm px-2 md:px-4 lg:px-5 py-2 lg:py-2.5">
                                Gửi đơn đặt hàng
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</main>
@endsection
@push('javascript')
<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js" integrity="sha512-uURl+ZXMBrF4AwGaWmEetzrd+J5/8NRkWAvJx5sbPSSuOb0bZLqf+tOzniObO00BjHa/dD7gub9oCGMLPQHtQA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css" integrity="sha512-H9jrZiiopUdsLpg94A333EfumgUBpO9MdbxStdeITo+KEIMaNfHNvwyjjDJb+ERPaRS6DpyRlKbvPUasNItRyw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<script>
    $(document).on("click", "#checkbox-all-quyen", function() {
        let _this = $(this);
        checkall(_this);
    });

    $(document).on("click", ".checkbox-item-quyen", function() {
        let _this = $(this);
        check_item_all(_this);
    });

    function checkall(_this) {
        let table = _this.parents("main");
        if ($("#checkbox-all-quyen").length) {
            if (table.find("#checkbox-all-quyen").prop("checked")) {
                table.find(".checkbox-item-quyen").prop("checked", true);
            } else {
                table.find(".checkbox-item-quyen").prop("checked", false);
            }
        }
    }

    function check_item_all(_this) {
        let table = _this.parents("main");
        if (table.find(".checkbox-item-quyen").length) {
            if (
                table.find(".checkbox-item-quyen:checked").length ==
                table.find(".checkbox-item-quyen").length
            ) {
                table.find("#checkbox-all-quyen").prop("checked", true);
            } else {
                table.find("#checkbox-all-quyen").prop("checked", false);
            }
        }
    }
</script>

<script type="text/javascript">
    $('.menu_item_auth:eq(1)').addClass('active')
</script>
@if(session('error') || session('success'))
@if(session('success'))
<script>
    toastr.success('<?php echo session('success') ?>', 'Thông báo!')
</script>
@endif
@if(session('error'))
<script>
    toastr.error('<?php echo session('error') ?>', 'Error!')
</script>
@endif
@endif

<script>
    <?php if (!$data->isEmpty()) {
        foreach ($data as $item) { ?>
            $('a[rel="group<?php echo $item->id ?>"]').fancybox({
                thumbs: {
                    autoStart: true
                },
                buttons: [
                    'zoom',
                    'close'
                ]
            });
        <?php } ?>
    <?php } ?>
</script>
<script>
    /*START: tăng giảm số lượng */
    $(document).on("click", ".card-inc", function() {
        var quantity = parseInt($(this).parent().find(".tp_cardQuantity").val());
        var id = parseInt($(this).parent().find(".tp_cardQuantity").attr('data-id'));
        var max_quantity = parseInt(
            $(this).parent().find(".tp_cardQuantity").attr("max")
        );
        if (quantity >= max_quantity) {
            quantity = max_quantity;
        } else {
            quantity += 1;
        }
        $(this).parent().find(".tp_cardQuantity").val(quantity);
        var id = parseInt($(this).parent().find(".tp_cardQuantity").attr('data-id'));
        ajax_cart_update(id, quantity, 'plus');

    });
    $(document).on("click", ".card-dec", function() {
        var quantity = parseInt($(this).parent().find(".tp_cardQuantity").val());
        var id = parseInt($(this).parent().find(".tp_cardQuantity").attr('data-id'));
        if (quantity <= 1) {
            quantity = 1;
        } else {
            quantity -= 1;
        }
        $(this).parent().find(".tp_cardQuantity").val(quantity);
        ajax_cart_update(id, quantity, 'minus');
    });
    $(document).on("change", ".tp_cardQuantity", function() {
        var quantity = parseInt($(this).val());
        var id = parseInt($(this).attr('data-id'));
        ajax_cart_update(id, quantity, '');
    });
    $(document).on("click", ".js_delete_cart", function() {

    });
    $(document).on("click", ".js_delete_cart", function() {
        var id = parseInt($(this).attr('data-id'));
        let param = {
            id: id,
        };
        swal({
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
                    ajax_cart_update(param.id, 0, 'delete');

                } else {
                    swal({
                            title: "Hủy bỏ",
                            text: "Thao tác bị hủy bỏ",
                            type: "error",
                        },
                        function() {
                            location.reload();
                        }
                    );
                }
            }
        );

    });

    function ajax_cart_update(id, quantity, type = '') {
        $.ajax({
            type: "POST",
            url: "<?php echo route('cartF.update') ?>",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            data: {
                id: id,
                quantity: quantity,
                type: type,
            },
            success: function(data) {
                if (data.error == "") {
                    $('.cart_total_item').html(data.return.count)
                    if (type == 'delete') {
                        $('.col_' + id).remove();
                        swal({
                                title: "Xóa đơn hàng thành công!",
                                text: "Các bản ghi đã được cập nhập.",
                                type: "success",
                            },
                            function() {
                                location.reload();
                            }
                        );
                    } else {
                        $('.price_vnd_' + id).html(data.return.price_item_cart_vnd)
                        $('.price_cny_' + id).html(data.return.price_item_cart_cny)
                        toastr.success(data.message, "Thành công!");
                    }
                    $('.total_price_cny').html(data.return.total_price_cny)
                    $('.total_price_vnd').html(data.return.total_price_vnd)
                } else {
                    toastr.error(data.error, "Error!");
                }
            },
        });
    }
</script>
<script>
    $(document).on("click", ".ajax-delete-all", function() {
        let _this = $(this);
        let value = _this.val();
        let id_checked = []; /*Lấy id bản ghi */
        $(".checkbox-item-quyen:checked").each(function() {
            id_checked.push($(this).val());
        });
        if (id_checked.length <= 0) {
            swal({
                    title: "Có vấn đề xảy ra",
                    text: "Bạn phải chọn ít nhất 1 bản ghi để thực hiện chức năng này",
                    type: "error",
                },
                function() {
                    location.reload();
                }
            );
            return false;
        }
        let param = {
            list: id_checked,
        };
        swal({
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
                    $.ajax({
                        type: "POST",
                        url: "<?php echo route('cartF.delete_all') ?>",
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                                "content"
                            ),
                        },
                        data: {
                            param: param
                        },
                        success: function(data) {
                            if (data.code == 200) {
                                for (let i = 0; i < id_checked.length; i++) {
                                    $(".col_" + id_checked[i]).remove();
                                }
                                swal({
                                        title: "Xóa thành công!",
                                        text: "Các bản ghi đã được xóa khỏi danh sách.",
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
                                    },
                                    function() {
                                        location.reload();
                                    }
                                );
                            }
                        },
                    });
                } else {
                    swal({
                            title: "Hủy bỏ",
                            text: "Thao tác bị hủy bỏ",
                            type: "error",
                        },
                        function() {
                            location.reload();
                        }
                    );
                }
            }
        );
    });
    $(document).on("click", ".ajax-order-store", function() {
        let _this = $(this);
        let value = _this.val();
        let id_checked = []; /*Lấy id bản ghi */
        $(".checkbox-item-quyen:checked").each(function() {
            id_checked.push($(this).val());
        });
        if (id_checked.length <= 0) {
            swal({
                    title: "Có vấn đề xảy ra",
                    text: "Bạn phải chọn ít nhất 1 bản ghi để thực hiện chức năng này",
                    type: "error",
                },
                function() {
                    location.reload();
                }
            );
            return false;
        }
        let param = {
            list: id_checked,
        };
        swal({
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
                    $.ajax({
                        type: "POST",
                        url: "<?php echo route('ordersF.store') ?>",
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                                "content"
                            ),
                        },
                        data: {
                            param: param
                        },
                        success: function(data) {
                            if (data.code == 200) {
                                for (let i = 0; i < id_checked.length; i++) {
                                    $(".col_" + id_checked[i]).remove();
                                }
                                swal({
                                        title: "Thành công",
                                        text: "Tạo đơn hàng thành công",
                                        type: "success",
                                    },
                                    function() {
                                        window.location.href = "<?php echo route('ordersF.index') ?>";
                                    }
                                );
                            } else {
                                swal({
                                        title: "Có vấn đề xảy ra",
                                        text: "Vui lòng thử lại",
                                        type: "error",
                                    },
                                    function() {
                                        location.reload();
                                    }
                                );
                            }
                        },
                    });
                } else {
                    swal({
                            title: "Hủy bỏ",
                            text: "Thao tác bị hủy bỏ",
                            type: "error",
                        },
                        function() {
                            location.reload();
                        }
                    );
                }
            }
        );
    });
</script>
@endpush