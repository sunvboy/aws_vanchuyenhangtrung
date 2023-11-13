
<?php $__env->startSection('title'); ?>
<title><?php echo e(trans('admin.update_customer')); ?></title>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
<?php
$array = array(
    [
        "title" => "Danh sách thành viên",
        "src" => route('customers.index'),
    ],
    [
        "title" => "Cập nhập",
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
            <?php echo e(trans('admin.update_customer')); ?>

        </h1>
    </div>
    <form class="grid grid-cols-12 gap-6 mt-5" role="form" action="<?php echo e(route('customers.update',['id' => $detail->id])); ?>" method="post" enctype="multipart/form-data">
        <div class=" col-span-12">
            <!-- BEGIN: Form Layout -->
            <div class=" box p-5">
                <?php echo $__env->make('components.alert-error', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php echo csrf_field(); ?>
                <div>
                    <label class="form-label text-base font-semibold"><?php echo e(trans('admin.category_customer')); ?></label>
                    <?php echo Form::select('catalogue_id', $category, $detail->catalogue_id, ['class' => 'form-control w-full', 'placeholder' => '', 'disabled']); ?>
                </div>
                <?php /*<div class="mt-3">
                    <label class="form-label text-base font-semibold">Email</label>
                    <?php echo Form::text('email', $detail->email, ['class' => 'form-control w-full', 'disabled']); ?>
                </div>*/ ?>
                <div class="mt-3">
                    <label class="form-label text-base font-semibold"><?php echo e(trans('admin.code_customer')); ?></label>
                    <?php echo Form::text('code', $detail->code, ['class' => 'form-control w-full', 'disabled']); ?>
                </div>
                <div class="mt-3">
                    <label class="form-label text-base font-semibold"><?php echo e(trans('admin.fullname')); ?></label>
                    <?php echo Form::text('name', $detail->name, ['class' => 'form-control w-full', 'placeholder' => '']); ?>
                </div>
                <div class="mt-3">
                    <label class="form-label text-base font-semibold"><?php echo e(trans('admin.phone')); ?></label>
                    <?php echo Form::text('phone', $detail->phone, ['class' => 'form-control w-full', 'placeholder' => '']); ?>
                </div>
                <div class="mt-3">
                    <label class="form-label text-base font-semibold"><?php echo e(trans('admin.address')); ?></label>
                    <?php echo Form::text('address', $detail->address, ['class' => 'form-control w-full', 'placeholder' => '']); ?>
                </div>
                <div class="mt-3">
                    <?php echo $__env->make('user.backend.user.image',['action' => 'update'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </div>
                <div class="text-right mt-5">
                    <button type="submit" class="btn btn-primary"><?php echo e(trans('admin.update')); ?></button>
                </div>
            </div>
        </div>
    </form>

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('dashboard.layout.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/quyenitc/api/resources/views/customer/backend/customer/edit.blade.php ENDPATH**/ ?>