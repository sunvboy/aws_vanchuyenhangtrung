
<?php $__env->startSection('title'); ?>
<title><?php echo e(trans('admin.create_category_customer')); ?></title>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
<?php
$array = array(
    [
        "title" => trans('admin.index_category_customer'),
        "src" => route('customers.index'),
    ],
    [
        "title" => trans('admin.create'),
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
            <?php echo e(trans('admin.create_category_customer')); ?>

        </h1>
    </div>
    <form class="grid grid-cols-12 gap-6 mt-5" role="form" action="<?php echo e(route('customer_categories.store')); ?>" method="post" enctype="multipart/form-data">
        <div class=" col-span-12">
            <!-- BEGIN: Form Layout -->
            <div class=" box p-5">
                <?php echo $__env->make('components.alert-error', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php echo csrf_field(); ?>
                <div>
                    <label class="form-label text-base font-semibold"><?php echo e(trans('admin.title')); ?></label>
                    <?php echo Form::text('title', '', ['class' => 'form-control w-full', 'placeholder' => '']); ?>
                </div>
                <div class="mt-5">
                    <label class="form-label text-base font-semibold"><?php echo e(trans('admin.code_customer')); ?></label>
                    <?php echo Form::text('slug', '', ['class' => 'form-control w-full', 'placeholder' => 'TAV']); ?>
                </div>
                <div class="text-right mt-5">
                    <button type="submit" class="btn btn-primary"><?php echo e(trans('admin.create')); ?></button>
                </div>
            </div>
        </div>
    </form>

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('dashboard.layout.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/quyenitc/api/resources/views/customer/backend/category/create.blade.php ENDPATH**/ ?>