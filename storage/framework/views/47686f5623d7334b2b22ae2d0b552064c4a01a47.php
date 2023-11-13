<?php $__env->startSection('title'); ?>
<title>Danh sách đơn giao hàng</title>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
<?php
$array = array(
    [
        "title" => "Danh sách đơn giao hàng",
        "src" => 'javascript:void(0)',
    ]
);
echo breadcrumb_backend($array);
?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="content ">
    <div class="flex justify-between items-center mt-5">
        <h1 class=" text-lg font-medium">
            Danh sách đơn giao hàng
        </h1>
        <div class="flex">
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('deliveries_create')): ?>
            <a href="<?php echo e(route('deliveries.create')); ?>" class="btn btn-primary shadow-md mr-2"><?php echo e(trans('admin.create')); ?></a>
            <?php endif; ?>
            <a href="<?php echo e(route('deliveries.export',[
            'code' => request()->get('code'),
            'status' => request()->get('status'),
            'customer_id' => request()->get('customer_id'),
            'date_start'=>request()->get('date_start'),
            'date_end'=>request()->get('date_end'),
            'keyword'=>request()->get('keyword')
            ])); ?>" class="btn btn-primary shadow-md text-white btn-excel">Export excel</a>
        </div>
    </div>

    <div class="grid grid-cols-12 gap-6 mt-2">
        <div class="col-span-12 mt-2">
            <form action="" class="grid grid-cols-12 gap-2" id="search" style="margin-bottom: 0px;">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('deliveries_destroy')): ?>
                <div class="col-span-2">
                    <select class="form-control ajax-delete-all-deliveries h-10" data-title="Lưu ý: Khi bạn xóa danh mục nội dung tĩnh, toàn bộ nội dung tĩnh trong nhóm này sẽ bị xóa. Hãy chắc chắn rằng bạn muốn thực hiện chức năng này!" data-module="<?php echo e($module); ?>">
                        <option>Hành động</option>
                        <option value="delete">Xóa</option>
                        <option value="completed">Đã thanh toán</option>
                        <option value="wait">Chưa thanh toán</option>
                    </select>
                </div>
                <?php endif; ?>
                <div class="col-span-2">
                    <?php echo Form::select('customer_id', $customers, request()->get('customer_id'), ['class' => 'form-control tom-select tom-select-custom tomselected', 'data-placeholder' => "Select your favorite actors"]); ?>
                </div>
                <div class="col-span-2">
                    <?php echo Form::select('status', ['0' => 'Trạng thái', 'wait' => 'Chưa thanh toán', 'completed' => 'Đã thanh toán'], request()->get('status'), ['class' => 'form-control tom-select tom-select-custom tomselected', 'data-placeholder' => "Select your favorite actors"]); ?>
                </div>
                    <div class="col-span-2">
                        <?php echo Form::select('payment', ['0' => 'Kiểu thanh toán', 'QR' => 'QR CODE', 'banking' => 'Chuyển khoản', 'COD' => 'Tiền mặt'], request()->get('payment'), ['class' => 'form-control tom-select tom-select-custom tomselected', 'data-placeholder' => "Select your favorite actors"]); ?>
                    </div>
                <div class="col-span-2">
                    <?php echo Form::text('date_start', request()->get('date_start'), ['class' => 'form-control h-10', 'autocomplete' => 'off', 'placeholder' => 'Ngày bắt đầu']); ?>
                </div>
                <div class="col-span-2">
                    <?php echo Form::text('date_end', request()->get('date_end'), ['class' => 'form-control h-10', 'autocomplete' => 'off', 'placeholder' => 'Ngày kết thúc']); ?>
                </div>
                <div class="col-span-4">
                    <?php echo Form::text('code', request()->get('code'), ['class' => 'form-control h-10', 'autocomplete' => 'off', "placeholder" => "Mã vận đơn"]); ?>
                </div>
                <div class="col-span-4">
                    <input type="search" name="keyword" class="keyword form-control filter w-full h-10" placeholder="Nhập từ khóa tìm kiếm ..." autocomplete="off" value="<?php echo request()->get('keyword') ?>">
                </div>
                <div class="col-span-1 flex items-center space-x-2 justify-end">
                    <button class="btn btn-primary btn-sm">
                        <i data-lucide="search"></i>
                    </button>
                </div>
            </form>

        </div>
        <?php if(Auth::user()->id == 18 || Auth::user()->id == 1): ?>
        <!-- BEGIN: Data List -->
        <div class="col-span-12 overflow-auto space-y-2">
            <div class="font-bold" style="border-radius: 0px;color: #000"><span class="text-danger">Tổng dòng</span> : <?php echo e(!empty($count)?$count : 0); ?> </div>
            <div class="font-bold" style="border-radius: 0px;color: #000"><span class="text-danger">Tổng cân 总重量</span>: <?php echo e(number_format((float)$total_weight, 2, '.', '')); ?></div>
            <div class="font-bold" style="border-radius: 0px;color: #000"><span class="text-danger">Tổng tiền</span>: <?php echo e(number_format((float)$prices, 0, ',', '.')); ?> VNĐ</div>
            <button id="ajax-qr-payment" class="btn btn-success text-white" disabled>Tạo mã QR thanh toán gộp</button>
        </div>
        <?php endif; ?>

        <div class="col-span-12 overflow-auto">

            <table class="table table-report">
                <thead class="table-dark">

                    <tr>
                        <th style="width:40px;">
                            <input type="checkbox" id="checkbox-all-quyen">
                        </th>
                        <th class="text-center">STT</th>
                        <th><?php echo e(trans('admin.created')); ?></th>
                        <th><?php echo e(trans('admin.code_deliveries')); ?></th>
                        <th><?php echo e(trans('admin.code_customer')); ?></th>
                        <th><?php echo e(trans('admin.weight')); ?></th>
                        <th>Biểu phí运费</th>
                        <th>Phụ phí</th>
                        <th>Thành tiền 總金額</th>
                        <th>Trạng thái</th>
                        <th class="whitespace-nowrap text-center">#</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="odd odd-<?php echo e($v->id); ?> odd-export-warehouse-<?php echo e($v->id); ?>" data-status="<?php echo e($v->status); ?>" id="post-<?php echo $v->id; ?>">
                        <td>
                            <input type="checkbox" name="checkbox[]" value="<?php echo $v->id; ?>" class="checkbox-item-quyen">
                        </td>
                        <td>
                            <?php echo e($data->firstItem()+$loop->index); ?>

                        </td>
                        <td>
                            <?php echo e($v->created_at); ?><br><span class="font-bold">(<?php echo e(!empty($v->user) ? $v->user->name : ''); ?>)</span>
                        </td>
                        <td>
                            <a href="javascript:void(0)" style="text-decoration: underline;" data-status="<?php echo e($v->status); ?>" data-image="" data-code="<?php echo e($v->code); ?>" data-id="<?php echo e($v->id); ?>" data-price="<?php echo e(floor($v->price)); ?>" class="ja_modalPayment  ja_modalPayment_<?php echo $v->id ?> font-bold text-success"><?php echo e($v->code); ?></a>
                        </td>

                        <td>
                            <?php echo e(!empty($v->customer) ? $v->customer->code : ''); ?>

                        </td>
                        <td>
                            <div class="flex">
                                <input type="text" style="width: 100px" class="form-control flex-1 form-weight form-weight-<?php echo e($v->id); ?>" data-id="<?php echo e($v->id); ?>" value="<?php echo e($v->weight); ?>" <?php if($v->status != 'wait'): ?> disabled <?php endif; ?>>
                            </div>
                        </td>
                        <td>
                            <div class="flex">
                                <input type="text" style="width: 100px" class="form-control flex-1 form-fee form-fee-<?php echo e($v->id); ?> int" data-id="<?php echo e($v->id); ?>" value="<?php echo e(number_format($v->fee,'0',',','.')); ?>" <?php if($v->status != 'wait'): ?> disabled <?php endif; ?>>
                            </div>
                        </td>
                        <td>
                            <div class="flex">
                                <input type="text" style="width: 100px" class="form-control flex-1 form-shipping form-shipping-<?php echo e($v->id); ?> int" data-id="<?php echo e($v->id); ?>" value="<?php echo e(number_format($v->shipping,'0',',','.')); ?>" <?php if($v->status != 'wait'): ?> disabled <?php endif; ?>>
                                <?php if($v->status == 'wait'): ?>
                                <button type="button" class="w-10 js_updateTotal btn btn-success" data-id="<?php echo e($v->id); ?>" style="height: 42px;padding: 0">
                                    <i data-lucide="save" class="w-5 h-5 text-white"></i>
                                </button>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td>
                            <span class="form-total-<?php echo e($v->id); ?> text-danger font-bold">
                                <?php echo e(number_format($v->price,'0',',','.')); ?>

                            </span>
                        </td>

                        <td>
                            <?php
                            $paymnet = '';

                            if (!empty($v->payment)) {
                                if ($v->payment == 'banking') {
                                    $paymnet = 'Chuyển khoản';
                                } else if ($v->payment == 'COD') {
                                    $paymnet = 'Thu tiền mặt';
                                } else if ($v->payment == 'QR') {
                                    $paymnet = 'QR';
                                }
                            }
                            ?>

                            <?php echo !empty($v->status) ? (!empty($v->status == 'completed') ? '<span class="btn btn-success btn-sm" style="color:white">Đã thanh toán - ' . $paymnet . '</span>' : '<span class="btn btn-danger btn-sm">Chưa thanh toán</span>') : '' ?><br>
                            <?php if(!empty($v->code_merge)): ?>
                            <button class="btn btn-primary btn-sm mt-1">#<?php echo e($v->code_merge); ?></button>
                            <?php endif; ?>
                        </td>


                        <td >
                            <?php
                            $products = json_decode($v->products, TRUE)
                            ?>
                            <div style="display: none;">
                                <?php if(!empty($v->advanced)): ?>
                                    <textarea class="textareacopy-<?php echo e($v->id); ?>"><?php if(!empty($products) && count($products) > 0): ?> <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php echo e($item); ?>&#13;&#10;<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> <?php endif; ?></textarea>

                                <?php else: ?>
                                    <textarea class="textareacopy-<?php echo e($v->id); ?>"><?php if(!empty($products) && count($products) > 0): ?><?php if(!empty($products['code']) && count($products['code']) > 0): ?><?php $__currentLoopData = $products['code']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php echo e($item); ?>&#13;&#10;<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> <?php endif; ?> <?php endif; ?></textarea>

                                <?php endif; ?>
                            </div>
                            <div class="space-y-5">
                                <div class="flex">
                                    <a class="flex items-center mr-3 text-base" href="javascript:void(0)" onclick="getinfo(<?php echo e($v->id); ?>)">
                                        <i data-lucide="printer" class="w-4 h-4 mr-1"></i> Print
                                    </a>
                                    <a class="flex items-center mr-3 text-base" href="javascript:void(0)" onclick="copyToClipboard('.textareacopy-<?php echo e($v->id); ?>')">
                                        <i data-lucide="copy" class="w-4 h-4 mr-1"></i> Copy
                                    </a>
                                </div>
                                <div class="flex">
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('deliveries_edit')): ?>
                                        <?php if(!empty($v->advanced)): ?>
                                            <a class="flex items-center mr-3 text-base" href="<?php echo e(route('deliveries.advanced',['id'=>$v->id])); ?>">
                                                <i data-lucide="check-square" class="w-4 h-4 mr-1"></i>
                                                Edit
                                            </a>
                                        <?php else: ?>

                                            <a class="flex items-center mr-3 text-base" href="<?php echo e(route('deliveries.edit',['id'=>$v->id])); ?>">
                                                <i data-lucide="check-square" class="w-4 h-4 mr-1"></i>
                                                Edit
                                            </a>
                                        <?php endif; ?>

                                    <?php endif; ?>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('deliveries_destroy')): ?>
                                        <a class="flex items-center text-danger ajax-delete text-base" href="javascript:;" data-id="<?php echo $v->id ?>" data-module="<?php echo $module ?>" data-child="0" data-title="Lưu ý: Khi bạn xóa đơn giao hàng, đơn giao hàng sẽ bị xóa vĩnh viễn. Hãy chắc chắn rằng bạn muốn thực hiện hành động này!">
                                            <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i> Delete
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
        <!-- END: Data List -->
        <!-- BEGIN: Pagination -->
        <div class=" col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center justify-center">
            <?php echo e($data->links()); ?>

        </div>
        <!-- END: Pagination -->
    </div>
</div>
<div id="Print" style="float:left;margin-left:0px;display:none">
    <table style="width:100%;height:950px;border:1px solid #000;font-size:36px !important;line-height: 30px" id="tablePrint">

    </table>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('javascript'); ?>
<script src="<?php echo e(asset('library/toastr/toastr.min.js')); ?>"></script>
<link href="<?php echo e(asset('library/toastr/toastr.min.css')); ?>" rel="stylesheet">
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
    function PrintBarcode(divId) {
        var printContents = document.getElementById(divId).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print({
            numberOfCopies: 5
        });
        document.body.innerHTML = originalContents;
    }

    function getinfo(id) {
        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                    "content"
                ),
            },
            type: 'GET',
            url: "<?php echo route('deliveries.printer') ?>",
            data: {
                id: id
            },
            contentType: "application/json;charset=utf-8",
            dataType: "json",
            success: function(result) {
                $('#tablePrint').html(result.html)
                setTimeout(function() {
                    var img = document.getElementById("imgbarcode");
                    img.src = result.src;
                    img.onload = function() {
                        PrintBarcode("Print");
                    }
                }, 10);
            }
        });
    }

    $(document).on("click", "#checkbox-all-quyen", function() {
        let _this = $(this);
        checkall(_this);
        change_background();
        loadIDS()
        show_button_remove_all();
    });

    $(document).on("click", ".checkbox-item-quyen", function() {
        let _this = $(this);
        change_background(_this);
        check_item_all(_this);
        loadIDS()
        show_button_remove_all();

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
        $(".checkbox-item-quyen").each(function() {
            if ($(this).is(":checked")) {
                $(this).parents("tr").addClass("bg-active");
            } else {
                $(this).parents("tr").removeClass("bg-active");
            }
        });
    }

    function show_button_remove_all() {
        if ($(".checkbox-item-quyen:checked").length) {
            $("#ajax-qr-payment").attr("disabled", false);
        } else {
            $("#ajax-qr-payment").attr("disabled", true);
        }
    }

    function loadIDS() {
        var ids = '';
        $(".checkbox-item-quyen").each(function() {
            if ($(this).is(":checked")) {
                ids += $(this).val() + ','
            }
        });
        $('.btn-excel').attr('href', '<?php echo route("deliveries.export") ?>?ids=' + ids)

    }
</script>
<script>
    function copyToClipboard(element) {
        var $temp = $("<textarea>");
        $("body").append($temp);
        $temp.val($(element).val()).select();
        document.execCommand("copy");
        $temp.remove();
        toastr.success('Đã sao chép vào bộ nhớ tạm', 'Thông báo!')
    }
</script>
<script>
    $(document).on("change", ".ajax-delete-all-deliveries", function() {
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
            title: _this.attr("data-title"),
            module: _this.attr("data-module"),
            router: _this.attr("data-router"),
            child: _this.attr("data-child"),
            list: id_checked,
            value: value,
        };
        let parent = _this.attr("data-parent"); /*Đây là khối mà sẽ ẩn sau khi xóa */
        if (value == 'delete') {
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
                function(isConfirm) {
                    if (isConfirm) {
                        $.ajax({
                            type: "POST",
                            url: BASE_URL_AJAX + "ajax/ajax-delete-all",
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
                                    setTimeout(function() {
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
        } else {
            //cập nhập trạng thái
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
                            url: "<?php echo route('deliveries.update_status') ?>",
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
                                    setTimeout(function() {
                                        location.reload();
                                    }, 1500);
                                    swal({
                                            title: "Cập nhập trạng thái thành công!",
                                            text: "Các bản ghi đã được cập nhập.",
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
        }

    });
</script>
<script>
    $(document).on('keyup', '.form-weight', function(e) {
        var id = $(this).attr('data-id');
        loadTotal(id)
    })
    $(document).on('keyup', '.form-fee', function(e) {
        var id = $(this).attr('data-id');
        loadTotal(id)
    })
    $(document).on('keyup', '.form-shipping', function(e) {
        var id = $(this).attr('data-id');
        loadTotal(id)
    })

    function loadTotal(id) {
        var weight = Number($('.form-weight-' + id).val())
        var fee = $('.form-fee-' + id).val().replace('.', "")
        var shipping = $('.form-shipping-' + id).val().replace('.', "")
        var price = 0;
        if (!weight) {
            weight = 0;
        }
        if (!fee) {
            fee = 0;
        }
        if (!shipping) {
            shipping = 0;
        }
        price = (weight * Number(fee)) + Number(shipping)
        $('.form-total-' + id).html(numberWithCommas(price))
    }
    $(document).on('click', '.js_updateTotal', function(e) {
        e.preventDefault()
        var id = $(this).attr('data-id')
        var shipping = $('.form-shipping-' + id).val()
        var fee = $('.form-fee-' + id).val()
        var weight = $('.form-weight-' + id).val()
        swal({
                title: "Hãy chắc chắn rằng bạn muốn thực hiện thao tác này?",
                text: "Cập nhập phí vận chuyển",
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
                    setTimeout(function() {
                        $.ajax({
                            headers: {
                                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                                    "content"
                                ),
                            },
                            url: "<?php echo route('deliveries.updateTotal') ?>",
                            type: "POST",
                            dataType: "JSON",
                            data: {
                                id: id,
                                weight: weight,
                                fee: fee,
                                shipping: shipping,
                            },
                            success: function(data) {
                                $('.ja_modalPayment_' + id).attr('data-price', data.price)
                                $('.form-total-' + id).html(numberWithCommas(data.price))
                                swal({
                                    title: "Success!",
                                    text: "Cập nhập thành tiền thành công.",
                                    type: "success",
                                });
                            },
                        });
                    }, 10);
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
<!-- BEGIN: Modal Content -->
<div id="warning-modal-preview" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body p-0">
                <div class="p-5 text-center"> <i data-lucide="x-circle" class="w-16 h-16 text-warning mx-auto mt-3"></i>
                    <div class="text-xl mt-5">Thanh toán đơn giao hàng <span class="js_popupCodePayment font-bold"></span></div>
                </div>
                <div>
                    <img class="QRCODE mx-auto" src="">
                    <input type="text" class="js_popupIDPayment hidden">
                </div>
                <div class="px-5 pb-8 text-center">
                    <button type="button" data-tw-dismiss="modal" class="btn w-24 btn-danger">Đóng</button>
                    <button type="button" class="btn btn-success text-white js_popupSubmit hidden" data-payment="banking">Xác nhận đã chuyển khoản</button>
                    <button type="button" class="btn btn-primary text-white js_popupSubmit hidden" data-payment="COD">Thu tiền mặt</button>

                    <button type="button" class="btn btn-success text-white js_popupSubmitMerge hidden" data-payment="banking">Xác nhận đã chuyển khoản</button>
                    <button type="button" class="btn btn-primary text-white js_popupSubmitMerge hidden" data-payment="COD">Thu tiền mặt</button>
                </div>
            </div>
        </div>
    </div>
</div> <!-- END: Modal Content -->
<script>
    const myModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#warning-modal-preview"));
    $(document).on('click', '.ja_modalPayment', function(e) {
        var status = $(this).attr('data-status')
        var price = $(this).attr('data-price')
        var code = $(this).attr('data-code')
        $('.js_popupCodePayment').html('#' + code)
        $('.js_popupIDPayment').val($(this).attr('data-id'))
        var image = `https://api.vietqr.io/image/<?php echo env('QR_CODE_ID')?>?accountName=<?php echo env('QR_CODE_NAME')?>&amount=${price}&addInfo=${code}`
        $('.QRCODE').attr('src', image)
        if (status == 'wait') {
            $('.js_popupSubmit').removeClass('hidden')
        } else {
            $('.js_popupSubmit').addClass('hidden')
        }
        myModal.show();
    })
    $(document).on('click', '.js_popupSubmit', function(e) {
        var id = $('.js_popupIDPayment').val();
        var payment = $(this).attr('data-payment');
        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                    "content"
                ),
            },
            url: "<?php echo route('deliveries.updatePaymentOne') ?>",
            type: "POST",
            dataType: "JSON",
            data: {
                id: id,
                payment: payment,
            },
            success: function(data) {
                swal({
                        title: "Success!",
                        text: "Thanh toán thành công.",
                        type: "success",
                    },
                    function() {
                        location.reload();
                    });
            },
        });
    })
    $(document).on('click', '.js_popupSubmitMerge', function(e) {
        var id = $('.js_popupIDPayment').val();
        var payment = $(this).attr('data-payment');
        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                    "content"
                ),
            },
            url: "<?php echo route('deliveries.updatePaymentMerge') ?>",
            type: "POST",
            dataType: "JSON",
            data: {
                id: id,
                payment: payment,
            },
            success: function(data) {
                swal({
                        title: "Success!",
                        text: "Thanh toán thành công.",
                        type: "success",
                    },
                    function() {
                        location.reload();
                    });
            },
        });
    })
</script>
<script>
    $(document).on("click", "#ajax-qr-payment", function() {
        let _this = $(this);
        let id_checked = []; /*Lấy id bản ghi */
        var check = false;
        $(".checkbox-item-quyen:checked").each(function() {
            if ($(this).parent().parent().attr('data-status') == 'completed') {
                check = true;
                return false;
            }
        });
        if (check) {
            swal({
                title: "Có vấn đề xảy ra",
                text: "Bạn phải chọn những đơn chưa thanh toán",
                type: "error",
            });
            return false;
        }
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
        swal({
                title: "Hãy chắc chắn rằng bạn muốn thực hiện thao tác này?",
                text: "Tạo đơn thanh toán gộp",
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
                        url: "<?php echo route('deliveries.updatePaymentAll') ?>",
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                                "content"
                            ),
                        },
                        data: {
                            ids: id_checked
                        },
                        success: function(data) {
                            swal({
                                    title: "Tạo đơn thanh toán gộp thành công!",
                                    text: "",
                                    type: "success",
                                },
                                function() {
                                    $('.js_popupCodePayment').html('#' + data.create.code)
                                    $('.js_popupIDPayment').val(data.create.id)
                                    var image = `https://api.vietqr.io/image/<?php echo env('QR_CODE_ID')?>?accountName=<?php echo env('QR_CODE_NAME')?>&amount=${data.create.price}&addInfo=${data.create.code}`
                                    $('.QRCODE').attr('src', image)
                                    $('.js_popupSubmitMerge').removeClass('hidden')
                                    myModal.show();
                                }
                            );
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
<?php $__env->stopPush(); ?>

<?php echo $__env->make('dashboard.layout.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/quyenitc/vanchuyenhangtrung.com/resources/views/delivery/backend/index.blade.php ENDPATH**/ ?>