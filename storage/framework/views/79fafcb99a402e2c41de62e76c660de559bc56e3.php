
<?php $__env->startSection('title'); ?>
<title><?php echo e(trans('admin.index_customer')); ?></title>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
<?php
$array = array(
    [
        "title" => "Danh sách khách hàng",
        "src" => 'javascript:void(0)',
    ]
);
echo breadcrumb_backend($array);
?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="content">
    <h1 class=" text-lg font-medium mt-10">
        <?php echo e(trans('admin.index_customer')); ?>

    </h1>
    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class=" col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2 justify-between">
            <div class="flex space-x-2">
                <select class="form-control ajax-delete-all mr10" style="width: 150px;;height:42px" data-title="Lưu ý: Khi bạn xóa danh mục nội dung tĩnh, toàn bộ nội dung tĩnh trong nhóm này sẽ bị xóa. Hãy chắc chắn rằng bạn muốn thực hiện chức năng này!" data-module="<?php echo e($module); ?>">
                    <option>Hành động</option>
                    <option value="">Xóa</option>
                </select>
                <form action="" class="flex space-x-2" id="search" style="margin-bottom: 0px;">
                    <?php if(isset($category)): ?>
                    <div style="width:250px;" class="mr10">
                        <?php echo Form::select('catalogueid', $category, request()->get('catalogueid'), ['class' => 'form-control tom-select tom-select-custom', 'data-placeholder' => "Select your favorite actors", 'style' => 'height:42px']); ?>
                    </div>
                    <?php endif; ?>
                    <input type="search" name="keyword" class="keyword form-control filter" placeholder="Nhập từ khóa tìm kiếm ..." autocomplete="off" value="<?php echo request()->get('keyword') ?>" style="width: 200px;">
                    <button class="btn btn-primary">
                        <i data-lucide="search"></i>
                    </button>
                </form>
            </div>
            <div class="flex items-center space-x-2">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('customers_create')): ?>
                <a href="<?php echo e(route('customers.create')); ?>" class="btn btn-primary shadow-md"><?php echo e(trans('admin.create')); ?></a>
                <?php endif; ?>
                <a href="<?php echo e(route('customers.export')); ?>" class="btn btn-success shadow-md text-white">Xuất excel</a>
            </div>
        </div>
        <!-- BEGIN: Data List -->
        <div class=" col-span-12 lg:col-span-12">
            <table class="table table-report -mt-2">
                <thead class="table-dark">
                    <tr>
                        <th style="width:40px;">
                            <input type="checkbox" id="checkbox-all">
                        </th>
                        <th><?php echo e(trans('admin.code_customer')); ?></th>
                        <th><?php echo e(trans('admin.fullname')); ?></th>
                        <th><?php echo e(trans('admin.phone')); ?></th>
                        <th><?php echo e(trans('admin.address')); ?></th>
                        <th>Số dư</th>
                        <th class="text-center">#</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="odd " id="post-<?php echo $v->id; ?>">
                        <td>
                            <input type="checkbox" name="checkbox[]" value="<?php echo $v->id; ?>" class="checkbox-item">
                        </td>
                        <td class="whitespace-nowrap">
                            <?php echo e($v->code); ?>

                        </td>
                        <td class="whitespace-nowrap">
                            <div class="">
                                <?php echo e($v->name); ?>

                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('customers_edit')): ?>
                                <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5">
                                    <a data-url="<?php echo e(route('users.reset-password',['id'=>$v->id])); ?>" href="javascript:void(0)" class="p-reset text-warning" data-userid="<?php echo e($v->id); ?>">RESET mật khẩu</a>
                                </div>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td class="whitespace-nowrap">
                            <?php echo e($v->phone); ?>

                        </td>
                        <td>
                            <?php echo e($v->address); ?>

                        </td>
                        <td>
                            <?php echo e(number_format($v->price,'0',',','.')); ?>

                        </td>
                        <td class="table-report__action w-56">
                            <div class="flex justify-center items-center">
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('customers_edit')): ?>
                                <a class="flex items-center mr-3" href="<?php echo e(route('customers.edit',['id'=>$v->id])); ?>">
                                    <i data-lucide="check-square" class="w-4 h-4 mr-1"></i>
                                    Edit
                                </a>
                                <?php endif; ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('customers_destroy')): ?>
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
<?php $__env->stopSection(); ?>
<?php $__env->startPush('javascript'); ?>
<script>
    /* CLICK VÀO THÀNH VIÊN*/
    $(document).on('click', '.choose', function() {
        let _this = $(this);
        $('.choose').removeClass('bg-choose'); //remove all trong các thẻ có class = choose
        _this.toggleClass('bg-choose');
        let data = _this.attr('data-info');
        data = window.atob(data); //decode base64
        let json = JSON.parse(data);
        setTimeout(function() {
            $('.fullname').html('').html(json.name);
            $('#image').attr('src', json.image);
            $('.phone').html('').html(json.phone);
            $('.email').html('').html(json.email);
            $('.address').html('').html(json.address);
            $('.updated').html('').html(json.created_at);
        }, 100); //sau 100ms thì mới thực hiện
    });
    $(document).on('click', '.p-reset', function(event) {
        event.preventDefault();
        let _this = $(this);
        let userID = _this.attr('data-userid');
        if (userID == 0) {
            sweet_error_alert('Có vấn đề xảy ra', 'Bạn phải chọn thành viên để thực hiện thao tác này');
        } else {
            swal({
                    title: "Hãy chắc chắn rằng bạn muốn thực hiện thao tác này?",
                    text: "Mật khẩu sẽ được cài về giá trị mặc định là : admin2023 sau thao tác này",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Thực hiện!",
                    cancelButtonText: "Hủy bỏ!",
                    closeOnConfirm: false,
                    closeOnCancel: false
                },
                function(isConfirm) {
                    if (isConfirm) {
                        $.ajax({
                            type: "POST",
                            url: "<?php echo route('customers.reset_password_ajax') ?>",
                            headers: {
                                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                            },
                            data: {
                                userID: userID
                            },
                            success: function(data) {
                                if (data.code == 200) {
                                    swal("Cập nhật thành công!", "Reset mật khẩu thành công.", "success");

                                } else {
                                    sweet_error_alert('Có vấn đề xảy ra', json.message);
                                }
                            }
                        });

                    } else {
                        swal("Hủy bỏ", "Thao tác bị hủy bỏ", "error");
                    }
                });
        }
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('dashboard.layout.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/quyenitc/api/resources/views/customer/backend/customer/index.blade.php ENDPATH**/ ?>