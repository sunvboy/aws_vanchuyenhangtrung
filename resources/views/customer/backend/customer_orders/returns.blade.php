@extends('dashboard.layout.dashboard')
@section('title')
    <title>Danh sách khiếu nại/hoàn tiền</title>
@endsection
@section('breadcrumb')
    <?php
    $array = array(
        [
            "title" => "Danh sách khiếu nại/hoàn tiền",
            "src" => 'javascript:void(0)',
        ]
    );
    echo breadcrumb_backend($array);
    ?>
@endsection
@section('content')
    <div class="content ">
        <div class="flex justify-between mt-5">
            <h1 class=" text-lg font-medium">
                Danh sách khiếu nại/hoàn tiền
            </h1>
        </div>
        <div class="grid grid-cols-12 gap-6 mt-5">
            <div class="col-span-12 mt-2">
                <form action="" class="grid grid-cols-12 gap-2" id="search" style="margin-bottom: 0px;">
                    @can('customer_orders_destroy')
                        <div class="col-span-2 hidden">
                            <select class="form-control ajax-delete-all-customer-orders h-10"
                                    data-title="Lưu ý: Khi bạn xóa danh mục nội dung tĩnh, toàn bộ nội dung tĩnh trong nhóm này sẽ bị xóa. Hãy chắc chắn rằng bạn muốn thực hiện chức năng này!"
                                    data-module="{{$module}}">
                                <option>Hành động</option>
                                <option value="delete">Xóa</option>
                            </select>
                        </div>
                    @endcan
                    <div class="col-span-2">
                        <?php echo Form::select('customer_id', $customers, request()->get('customer_id'), ['class' => 'form-control tom-select tom-select-custom tomselected', 'data-placeholder' => "Select your favorite actors"]); ?>
                    </div>
                    <div class="col-span-2">
                        <?php echo Form::select('status_return', config('cart')['status_return'], request()->get('status_return'), ['class' => 'form-control tom-select tom-select-custom tomselected', 'data-placeholder' => "Select your favorite actors"]); ?>
                    </div>
                    <div class="col-span-2">
                        <?php echo Form::text('date_start', request()->get('date_start'), ['class' => 'form-control h-10', 'autocomplete' => 'off', 'placeholder' => 'Ngày bắt đầu']); ?>
                    </div>
                    <div class="col-span-2">
                        <?php echo Form::text('date_end', request()->get('date_end'), ['class' => 'form-control h-10', 'autocomplete' => 'off', 'placeholder' => 'Ngày kết thúc']); ?>
                    </div>
                    <div class="col-span-3">
                        <?php echo Form::text('keyword', request()->get('keyword'), ['class' => 'form-control h-10', 'autocomplete' => 'off', 'placeholder' => 'Tìm kiếm']); ?>
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
                        <th style="width:40px;" class="hidden">
                            <input type="checkbox" id="checkbox-all-quyen">
                        </th>
                        <th>STT</th>
                        <th>CODE</th>
                        <th>Mã khách hàng</th>
                        <th>Trạng thái</th>
                        <th>Tổng tiền VNĐ</th>
                        <th>Tổng tiền hoàn VNĐ</th>
                        <th>Tổng tiền được hoàn trả VNĐ</th>
                        <th>Ngày tạo</th>
                        <th>Người xác nhận</th>
                        <th class="text-center">#</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($data as $v)
                        <tr class="odd" id="post-<?php echo $v->id; ?>">
                            <td class="hidden">
                                <input type="checkbox" name="checkbox[]" value="<?php echo $v->id; ?>"
                                       class="checkbox-item-quyen">
                            </td>
                            <td> {{$data->firstItem()+$loop->index}}</td>
                            <td class="whitespace-nowrap font-bold">
                                <a target="_blank"
                                   href="{{ route('customer_orders.show',['id'=>$v->id]) }}"> {{$v->code}}</a>
                            </td>
                            <td class="whitespace-nowrap">
                                {{!empty($v->customer)?$v->customer->code:''}}
                            </td>

                            <td class="whitespace-nowrap">
                            <span class="text-white px-2 py-1 rounded text-xs"
                                  style="background-color: {{config('cart')['class'][$v->status_return]}};">
                                #{{config('cart')['status_return'][$v->status_return]}}
                            </span>
                            </td>
                            <td>
                                {{number_format($v->total_price_vnd_final,'0',',','.')}}
                            </td>
                            <td class="font-bold text-danger">
                                {{number_format($v->price_return,'0',',','.')}}
                            </td>
                            <td class="font-bold text-success">
                                {{number_format($v->price_return_success,'0',',','.')}}
                            </td>
                            <td class="whitespace-nowrap">
                                {{$v->date_return}}
                            </td>
                            <td class="whitespace-nowrap">
                                {{!empty($v->user_return)?$v->user_return->name:''}}
                            </td>
                            <td class="table-report__action w-56">
                                <div class="flex justify-center items-center">
                                    @if($v->status_return == 'wait')
                                        @can('customer_orders_edit')
                                            <a class="flex items-center mr-3 text-success font-bold js_handleSuccess"
                                               href="javascript:void(0)" data-id="{{$v->id}}" data-price="{{number_format($v->price_return,'0',',','.')}}">
                                                <i data-lucide="check-square" class="w-4 h-4 mr-1"></i>
                                                Xác nhận
                                            </a>
                                            <a class="flex items-center mr-3 text-danger font-bold js_handleCanceled"
                                               href="javascript:void(0)" data-id="{{$v->id}}">
                                                <i data-lucide="x-circle" class="w-4 h-4 mr-1"></i>
                                                Từ chối
                                            </a>
                                        @endcan
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <!-- END: Data List -->
            <!-- BEGIN: Pagination -->
            <div class=" col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center justify-center">
                {{$data->links()}}
            </div>
            <!-- END: Pagination -->
        </div>
    </div>
    <style>
        .btn-success td {
            background: #dff0d8 !important;
            color: #3c763d !important;
        }

        .btn-danger td {
            color: #a94442 !important;
            background-color: #f2dede !important;
            border-color: #ebccd1 !important;
        }
    </style>
    <div id="header-footer-modal-preview" class="modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- BEGIN: Modal Header -->
                <div class="modal-header">
                    <h2 class="font-medium text-base mr-auto">Xác nhận hoàn tiền</h2>
                </div> <!-- END: Modal Header -->
                <!-- BEGIN: Modal Body -->
                <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                    <div class="col-span-12 sm:col-span-12">
                        <label for="modal-form-1" class="form-label">Số tiền hoàn trả</label>
                        <input id="modal-form-1" type="text" class="form-control int" placeholder="">
                        <input id="modal-form-2" type="text" class="form-control hidden" placeholder="">
                    </div>
                </div> <!-- END: Modal Body -->
                <!-- BEGIN: Modal Footer -->
                <div class="modal-footer">
                    <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-20 mr-1">Hủy</button>
                    <button type="button" class="btn btn-primary js_handleSuccessSubmit">Xác nhận</button>
                </div> <!-- END: Modal Footer -->
            </div>
        </div>
    </div> <!-- END: Modal Content -->
@endsection
@push('javascript')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.js"
            integrity="sha512-+UiyfI4KyV1uypmEqz9cOIJNwye+u+S58/hSwKEAeUMViTTqM9/L4lqu8UxJzhmzGpms8PzFJDzEqXL9niHyjA=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.css"
          integrity="sha512-bYPO5jmStZ9WI2602V2zaivdAnbAhtfzmxnEGh9RwtlI00I9s8ulGe4oBa5XxiC6tCITJH/QG70jswBhbLkxPw=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <script type="text/javascript">
        $(function () {
            $('input[name="date_start"]').datetimepicker({
                format: 'Y-m-d',
            });
            $('input[name="date_end"]').datetimepicker({
                format: 'Y-m-d',
            });
        });
    </script>
    <script>
        $(document).on("click", ".js_handleSuccess", function () {
            var id = $(this).attr('data-id')
            var price = $(this).attr('data-price')
            $('#modal-form-1').val(price)
            $('#modal-form-2').val(id)
            const myModal = tailwind.Modal.getInstance(document.querySelector("#header-footer-modal-preview"));
            myModal.show();
        });
        $(document).on("click", "#checkbox-all-quyen", function () {
            let _this = $(this);
            checkall(_this);
            change_background();
            loadIDS()
        });

        $(document).on("click", ".checkbox-item-quyen", function () {
            let _this = $(this);
            change_background(_this);
            check_item_all(_this);
            loadIDS()
        });

        function checkall(_this) {
            let table = _this.parents("table");
            if ($("#checkbox-all-quyen").length) {
                if (table.find("#checkbox-all-quyen").prop("checked")) {
                    table.find(".checkbox-item-quyen").prop("checked", true);
                } else {
                    table.find(".checkbox-item-quyen").prop("checked", false);
                }
            }
        }

        function check_item_all(_this) {
            let table = _this.parents("table");
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

        function change_background() {
            $(".checkbox-item-quyen").each(function () {
                if ($(this).is(":checked")) {
                    $(this).parents("tr").addClass("bg-active");
                } else {
                    $(this).parents("tr").removeClass("bg-active");
                }
            });
        }

        function loadIDS() {
            var ids = '';
            $(".checkbox-item-quyen").each(function () {
                if ($(this).is(":checked")) {
                    ids += $(this).val() + ','
                }
            });
            $('.btn-excel').attr('href', '<?php echo route('customer_payments.export') ?>?ids=' + ids)
        }

        /*START: XÓA tất cả bản ghi */
        $(document).on("change", ".ajax-delete-all-customer-payments", function () {
            let _this = $(this);
            let id_checked = []; /*Lấy id bản ghi */
            $(".checkbox-item-quyen:checked").each(function () {
                id_checked.push($(this).val());
            });
            if (id_checked.length <= 0) {
                swal({
                        title: "Có vấn đề xảy ra",
                        text: "Bạn phải chọn ít nhất 1 bản ghi để thực hiện chức năng này",
                        type: "error",
                    },
                    function () {
                        location.reload();
                    }
                );
                return false;
            }
            let param = {
                title: _this.attr("data-title"),
                module: _this.attr("data-module"),
                router: _this.attr("data-router"),
                child: _this.attr("data-child"),
                list: id_checked,
            };
            let parent = _this.attr("data-parent"); /*Đây là khối mà sẽ ẩn sau khi xóa */
            swal({
                    title: "Hãy chắc chắn rằng bạn muốn thực hiện thao tác này?",
                    text: param.title,
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Thực hiện!",
                    cancelButtonText: "Hủy bỏ!",
                    closeOnConfirm: false,
                    closeOnCancel: false,
                },
                function (isConfirm) {
                    if (isConfirm) {
                        $.ajax({
                            type: "POST",
                            url: BASE_URL_AJAX + "ajax/ajax-delete-all",
                            headers: {
                                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                            },
                            data: {
                                param: param
                            },
                            success: function (data) {
                                if (data.code == 200) {
                                    setTimeout(function () {
                                        location.reload();
                                    }, 1500);
                                    for (let i = 0; i < id_checked.length; i++) {
                                        $("#post-" + id_checked[i])
                                            .hide()
                                            .remove();
                                    }
                                    swal({
                                            title: "Xóa thành công!",
                                            text: "Các bản ghi đã được xóa khỏi danh sách.",
                                            type: "success",
                                        },
                                        function () {
                                            location.reload();
                                        }
                                    );
                                } else {
                                    swal({
                                            title: "Có vấn đề xảy ra",
                                            text: "Vui lòng thử lại",
                                            type: "error",
                                        },
                                        function () {
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
                            function () {
                                location.reload();
                            }
                        );
                    }
                }
            );
        });
    </script>
    <script>
        $(document).on('click', '.js_handleSuccessSubmit', function (e) {
            var id = $('#modal-form-2').val();
            var price = replace($('#modal-form-1').val());
            const myModal = tailwind.Modal.getInstance(document.querySelector("#header-footer-modal-preview"));
            myModal.hide();
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
                function (isConfirm) {
                    if (isConfirm) {
                        $.ajax({
                            type: "POST",
                            url: "<?php echo route('customer_orders.store_returns'); ?>",
                            headers: {
                                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                            },
                            data: {
                                id: id,
                                price: price,
                                status: 'completed'
                            },
                            success: function (data) {
                                if (data.code == 200) {
                                    /*  if(data.tokenIos){
                                        var formdata = new FormData();
                                        formdata.append("to",data.tokenIos);
                                        formdata.append("title",data.title);
                                        formdata.append("body", data.body);
                                        formdata.append("subtitle", "");
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
                                            title: "Cập nhập thành công!",
                                            text: "",
                                            type: "success",
                                        },
                                        function () {
                                            location.reload();
                                        }
                                    );
                                } else {
                                    swal({
                                        title: "Có vấn đề xảy ra",
                                        text: data.error,
                                        type: "error",
                                    });
                                }
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

        $(document).on('click', '.js_handleCanceled', function (e) {
            var id = $(this).attr('data-id');
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
                function (isConfirm) {
                    if (isConfirm) {
                        $.ajax({
                            type: "POST",
                            url: "<?php echo route('customer_orders.store_returns'); ?>",
                            headers: {
                                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                            },
                            data: {
                                id: id,
                                status: 'canceled'
                            },
                            success: function (data) {
                                if (data.code == 200) {
                                    swal({
                                            title: "Từ chối hoàn tiền thành công!",
                                            text: "",
                                            type: "success",
                                        },
                                        function () {
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
