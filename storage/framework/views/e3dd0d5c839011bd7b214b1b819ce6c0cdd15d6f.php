
<?php $__env->startSection('title'); ?>
<title>Danh sách đơn hàng</title>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
<?php
$array = array(
    [
        "title" => "Danh sách đơn hàng",
        "src" => 'javascript:void(0)',
    ]
);
echo breadcrumb_backend($array);
?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="content ">
    <div class="flex justify-between mt-5">
        <h1 class=" text-lg font-medium">
            Danh sách đơn hàng
        </h1>
        <div class="flex items-center space-x-2">
            <a href="<?php echo e(route('customer_orders.export',[
            'type' => request()->get('type'),
            'customer_id' => request()->get('customer_id'),
            'date_start'=>request()->get('date_start'),
            'date_end'=>request()->get('date_end'),
            ])); ?>" class="btn btn-success shadow-md text-white btn-excel">Xuất excel</a>
        </div>

    </div>
    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="col-span-12 mt-2">
            <form action="" class="grid grid-cols-12 gap-2" id="search" style="margin-bottom: 0px;">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('customer_orders_destroy')): ?>
                <div class="col-span-2">
                    <select class="form-control ajax-delete-all-customer-orders h-10" data-title="Lưu ý: Khi bạn xóa danh mục nội dung tĩnh, toàn bộ nội dung tĩnh trong nhóm này sẽ bị xóa. Hãy chắc chắn rằng bạn muốn thực hiện chức năng này!" data-module="<?php echo e($module); ?>">
                        <option>Hành động</option>
                        <option value="delete">Xóa</option>
                    </select>
                </div>
                <?php endif; ?>
                <div class="col-span-2">
                    <?php echo Form::select('customer_id', $customers, request()->get('customer_id'), ['class' => 'form-control tom-select tom-select-custom tomselected', 'data-placeholder' => "Select your favorite actors"]); ?>
                </div>
                <div class="col-span-2">
                    <?php echo Form::select('status', config('cart')['status'], request()->get('status'), ['class' => 'form-control tom-select tom-select-custom tomselected', 'data-placeholder' => "Select your favorite actors"]); ?>
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
                        <th style="width:40px;">
                            <input type="checkbox" id="checkbox-all-quyen">
                        </th>
                        <th>STT</th>
                        <th>CODE 订单号</th>
                        <th>Mã khách 客户码</th>
                        <th>Tiền hàng CNY 单价</th>
                        <th>Tổng tiền VNĐ</th>
                        <th>Trạng thái 状态</th>
                        <th>Hoàn tiền</th>
                        <th>Phí nội địa CNY 运费</th>
                        <th>Tiền hàng đặt CNY</th>
                        <th>Tổng tiền VNĐ</th>
                        <th>Ghi chú 备注</th>
                        <th>Ngày tạo</th>
                        <th class="text-center">#</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="odd" id="post-<?php echo $v->id; ?>">
                        <td>
                            <input type="checkbox" name="checkbox[]" value="<?php echo $v->id; ?>" class="checkbox-item-quyen">
                        </td>
                        <td> <?php echo e($data->firstItem()+$loop->index); ?></td>
                        <td class="whitespace-nowrap font-bold">
                            <a class="js_open_windown" href="<?php echo e(route('customer_orders.show',['id'=>$v->id])); ?>" target="_blank"> <?php echo e($v->code); ?></a>
                        </td>
                        <td class="whitespace-nowrap">
                            <?php echo e(!empty($v->customer)?$v->customer->code:''); ?>

                        </td>
                        <td class="whitespace-nowrap ">
                            <?php echo e($v->total); ?>

                        </td>
                        <td class="whitespace-nowrap text-success font-bold">
                            <?php echo e(number_format($v->total*$v->cny,'0',',','.')); ?>

                        </td>
                        <td class="whitespace-nowrap">
                            <div class="flex flex-col space-y-1">
                                <span class="<?php if ($v->status == 'wait' || $v->status == 'returns' || $v->status == 'canceled') { ?>text-white<?php } else { ?>text-black<?php } ?> px-2 py-1 rounded text-xs" style="background-color: <?php echo e(config('cart')['class'][$v->status]); ?>;">
                                    <?php echo e(config('cart')['status'][$v->status]); ?>

                                </span>

                            </div>
                        </td>
                        <td class="whitespace-nowrap">
                            <?php if(!empty($v->status_return)): ?>
                            <span style="background-color: <?php echo e(config('cart')['class'][$v->status_return]); ?>;" class="text-white px-2 py-1 rounded text-xs">#<?php echo e(config('cart')['status_return'][$v->status_return]); ?></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php echo e($v->fee); ?>

                        </td>
                        <td>
                            <?php echo e($v->total_price_cny_final); ?>

                        </td>
                        <td class="text-success font-bold">
                            <?php echo e(!empty($v->total_price_vnd_final) ? number_format($v->total_price_vnd_final,'0',',','.') : ''); ?>

                        </td>
                        <td class="font-bold">
                            <?php echo e($v->message); ?>

                        </td>
                        <td class="whitespace-nowrap">
                            <?php echo e($v->created_at); ?>

                        </td>
                        <td class="table-report__action w-56">
                            <div class="flex justify-center items-center">
                                <a class="flex items-center mr-3" href="<?php echo e(route('customer_orders.note',['id'=>$v->id])); ?>">
                                    <i data-lucide="eye" class="w-4 h-4 mr-1"></i>
                                    View
                                </a>
                                <a class="flex items-center mr-3 js_open_windown" href="<?php echo e(route('customer_orders.show',['id'=>$v->id])); ?>" type="button" target="_blank">
                                    <i data-lucide="check-square" class="w-4 h-4 mr-1"></i>
                                    Edit
                                </a>

                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('customer_orders_destroy')): ?>
                                <a class="flex items-center text-danger ajax-delete" href="javascript:;" data-id="<?php echo $v->id ?>" data-module="<?php echo $module ?>" data-child="0" data-title="Lưu ý: Khi bạn xóa thương hiệu, thương hiệu sẽ bị xóa vĩnh viễn. Hãy chắc chắn rằng bạn muốn thực hiện hành động này!">
                                    <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i> Delete
                                </a>
                                <?php endif; ?>
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
<?php $__env->stopSection(); ?>
<?php $__env->startPush('javascript'); ?>

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
    $(document).on("click", "#checkbox-all-quyen", function() {
        let _this = $(this);
        checkall(_this);
        change_background();
        loadIDS()
    });

    $(document).on("click", ".checkbox-item-quyen", function() {
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
        $(".checkbox-item-quyen").each(function() {
            if ($(this).is(":checked")) {
                $(this).parents("tr").addClass("bg-active");
            } else {
                $(this).parents("tr").removeClass("bg-active");
            }
        });
    }

    function loadIDS() {
        var ids = '';
        $(".checkbox-item-quyen").each(function() {
            if ($(this).is(":checked")) {
                ids += $(this).val() + ','
            }
        });
        $('.btn-excel').attr('href', '<?php echo route('customer_orders.export') ?>?ids=' + ids)
    }
    /*START: XÓA tất cả bản ghi */
    $(document).on("change", ".ajax-delete-all-customer-payments", function() {
        let _this = $(this);
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
            function(isConfirm) {
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
    });
</script>
<script>
    /* $('.js_open_windown').click(function() {
        let h = screen.availHeight;
        let w = screen.availWidth;
        window.open(this.href, 'chorme', 'top=' + h * 10 / 100 + ', left=' + w * 10 / 100 + ', width=' + w * 80 / 100 + ',height=' + h * 80 / 100);
        return false;
    });*/
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('dashboard.layout.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\vanchuyenhangtrung.local\resources\views/customer/backend/customer_orders/index.blade.php ENDPATH**/ ?>