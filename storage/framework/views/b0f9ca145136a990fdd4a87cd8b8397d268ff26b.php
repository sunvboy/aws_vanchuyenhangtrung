
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
                <div class="col-span-3">
                    <?php echo Form::text('date_start', request()->get('date_start'), ['class' => 'form-control h-10', 'autocomplete' => 'off', 'placeholder' => 'Ngày bắt đầu']); ?>
                </div>
                <div class="col-span-3">
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
        <!-- BEGIN: Data List -->
        <div class=" col-span-12 lg:col-span-12">
            <table class="table table-report">
                <thead class="table-dark">
                    <tr>
                        <td colspan="3" class="text-center font-bold" style="border-radius: 0px;color: #000">Tổng dòng : <?php echo e(!empty($count)?$count : 0); ?> </td>
                        <td class=" text-white" style="background-color: red">Tổng cân 总重量</td>
                        <td class=" text-white font-bold" style="background-color: red;border-radius: 0px;"><?php echo e(number_format((float)$total_weight, 2, '.', '')); ?></td>
                    </tr>
                    <tr>
                        <th style="width:40px;">
                            <input type="checkbox" id="checkbox-all-quyen">
                        </th>
                        <th class="text-center">STT</th>
                        <th class="whitespace-nowrap"><?php echo e(trans('admin.created')); ?></th>
                        <th class="whitespace-nowrap"><?php echo e(trans('admin.code_deliveries')); ?></th>
                        <th class="whitespace-nowrap"><?php echo e(trans('admin.weight')); ?></th>
                        <th class="whitespace-nowrap"><?php echo e(trans('admin.code_customer')); ?></th>
                        <th class="whitespace-nowrap">Đơn giá 單價
                        </th>
                        <th class="whitespace-nowrap">Thành tiền 總金額
                        </th>
                        <th class="whitespace-nowrap">Trạng thái</th>
                        <th class="whitespace-nowrap">Người tạo</th>
                        <th class="whitespace-nowrap text-center">#</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="odd " id="post-<?php echo $v->id; ?>">
                        <td>
                            <input type="checkbox" name="checkbox[]" value="<?php echo $v->id; ?>" class="checkbox-item-quyen">
                        </td>
                        <td>
                            <?php echo e($data->firstItem()+$loop->index); ?>

                        </td>
                        <td>
                            <?php echo e($v->created_at); ?>

                        </td>
                        <td>
                            <?php echo e($v->code); ?>

                        </td>
                        <td>
                            <?php echo e($v->weight); ?>

                        </td>
                        <td>
                            <?php echo e(!empty($v->customer) ? $v->customer->code : ''); ?>

                        </td>
                        <td>
                            <?php echo e(number_format($v->price,'0',',','.')); ?>

                        </td>
                        <td>
                            <?php echo e(number_format($v->price*$v->weight,'0',',','.')); ?>


                        </td>
                        <td>
                            <?php echo !empty($v->status) ? (!empty($v->status == 'completed') ? '<span class="btn btn-success btn-sm" style="color:white">Đã thanh toán</span>' : '<span class="btn btn-danger btn-sm">Chưa thanh toán</span>' ) : ''; ?>

                        </td>
                        <td>
                            <?php echo e(!empty($v->user) ? $v->user->name : ''); ?>

                        </td>

                        <td class="table-report__action w-56">
                            <?php
                            $products = json_decode($v->products, TRUE)
                            ?>
                            <div style="display: none;">
                                <textarea class="textareacopy-<?php echo e($v->id); ?>"><?php if(!empty($products) && count($products) > 0): ?><?php if(!empty($products['code']) && count($products['code']) > 0): ?><?php $__currentLoopData = $products['code']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php echo e($item); ?>&#13;&#10;<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> <?php endif; ?> <?php endif; ?></textarea>
                            </div>
                            <div class="flex justify-center items-center">
                                <a class="flex items-center mr-3" href="javascript:void(0)" onclick="getinfo(<?php echo e($v->id); ?>)">
                                    <i data-lucide="printer" class="w-8 h-8 mr-1"></i>
                                </a>
                                <a class="flex items-center mr-3" href="javascript:void(0)" onclick="copyToClipboard('.textareacopy-<?php echo e($v->id); ?>')">
                                    <i data-lucide="copy" class="w-4 h-4 mr-1"></i> Copy
                                </a>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('deliveries_edit')): ?>
                                <a class="flex items-center mr-3" href="<?php echo e(route('deliveries.edit',['id'=>$v->id])); ?>">
                                    <i data-lucide="check-square" class="w-4 h-4 mr-1"></i>
                                    Edit
                                </a>
                                <?php endif; ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('deliveries_destroy')): ?>
                                <a class="flex items-center text-danger ajax-delete" href="javascript:;" data-id="<?php echo $v->id ?>" data-module="<?php echo $module ?>" data-child="0" data-title="Lưu ý: Khi bạn xóa đơn giao hàng, đơn giao hàng sẽ bị xóa vĩnh viễn. Hãy chắc chắn rằng bạn muốn thực hiện hành động này!">
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
<div id="Print" style="float:left;margin-left:0px;display:none">
    <table style="width:100%;height:950px;border:1px solid #000;font-size:38px !important;line-height: 30px" id="tablePrint">

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
                    PrintBarcode("Print");

                }, 10);
            }
        });
    }

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
<?php $__env->stopPush(); ?>
<?php echo $__env->make('dashboard.layout.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\vanchuyenhangtrung.local\resources\views/delivery/backend/index.blade.php ENDPATH**/ ?>