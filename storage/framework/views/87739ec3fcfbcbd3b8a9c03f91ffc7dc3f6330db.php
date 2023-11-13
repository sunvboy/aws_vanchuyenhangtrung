<?php $__env->startSection('title'); ?>
<title>Thêm mới thành viên</title>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
<?php
    $array = array(
        [
            "title" => "Danh sách thành viên",
            "src" => route('users.index'),
        ],
        [
            "title" => "Thêm mới",
            "src" => 'javascript:void(0)',
        ]
    );
    echo breadcrumb_backend($array);
?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="content">
    <div class=" flex items-center mt-8">
        <h1 class="text-lg font-medium mr-auto">
            Thêm mới
        </h1>
    </div>
    <form class="grid grid-cols-12 gap-6 mt-5" role="form" action="<?php echo e(route('users.store')); ?>" method="post"
        enctype="multipart/form-data">
        <div class=" col-span-12">
            <!-- BEGIN: Form Layout -->
            <div class=" box p-5">
                <?php echo $__env->make('components.alert-error', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php echo csrf_field(); ?>
                <div>
                    <label class="form-label text-base font-semibold">Tên thành viên</label>
                    <?php echo Form::text('name', '', ['class' => 'form-control w-full ']); ?>
                </div>

                <div class="mt-3">
                    <label class="form-label text-base font-semibold">Email</label>
                    <div class="mt-2">
                        <?php echo Form::text('email', '', ['class' => 'form-control w-full ']); ?>
                    </div>
                </div>
                <div class="mt-3">
                    <label class="form-label text-base font-semibold">Số điện thoại</label>
                    <div class="mt-2">
                        <?php echo Form::text('phone', '', ['class' => 'form-control w-full ']); ?>
                    </div>
                </div>
                <div class="mt-3">
                    <label class="form-label text-base font-semibold">Địa chỉ</label>
                    <div class="mt-2">
                        <?php echo Form::text('address', '', ['class' => 'form-control w-full ']); ?>
                    </div>
                </div>
                <div class="mt-3">
                    <label class="form-label text-base font-semibold">Mật khẩu</label>
                    <div class="mt-2">
                        <input type="password" name="password" class="form-control" placeholder="" value="">
                    </div>
                </div>
                <div class="mt-3">
                    <label class="form-label text-base font-semibold">Nhập lại mật khẩu</label>
                    <div class="mt-2">
                        <input type="password" name="confirm_password" class="form-control" placeholder="" value="">
                    </div>
                </div>
                <div class="mt-3">
                    <label class="form-label text-base font-semibold">Chọn nhóm thành viên</label>
                    <div class="mt-2">

                        <select class="tom-select tom-select-custom w-full tomselected" data-placeholder="Search..."
                            name="role_id[]" tabindex="-1">
                            <option value=""></option>
                            <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($v->id); ?>"
                                <?php echo e((collect(old('role_id'))->contains($v->id)) ? 'selected':''); ?>>
                                <?php echo e($v->title); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="mt-3">
                    <?php echo $__env->make('user.backend.user.image',['action' => 'create'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </div>
                <div class="text-right mt-5">
                    <button type="submit" class="btn btn-primary w-24">Lưu</button>
                </div>
            </div>

        </div>
    </form>
</div>



<?php $__env->stopSection(); ?>

<?php echo $__env->make('dashboard.layout.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/quyenitc/vanchuyenhangtrung.com/resources/views/user/backend/user/create.blade.php ENDPATH**/ ?>