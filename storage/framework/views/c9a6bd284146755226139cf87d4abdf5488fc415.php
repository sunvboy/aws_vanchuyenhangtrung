<?php $__env->startSection('title'); ?>
<title> <?php echo e(trans('admin.create_customer')); ?></title>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
<?php
$array = array(
    [
        "title" => "Danh sách thành viên",
        "src" => route('customers.index'),
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
            <?php echo e(trans('admin.create_customer')); ?>

        </h1>
    </div>
    <form class="grid grid-cols-12 gap-6 mt-5" role="form" action="<?php echo e(route('customers.store')); ?>" method="post" enctype="multipart/form-data">
        <div class=" col-span-12">
            <!-- BEGIN: Form Layout -->
            <div class=" box p-5">
                <?php echo $__env->make('components.alert-error', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php echo csrf_field(); ?>
                <div>
                    <label class="form-label text-base font-semibold"> <?php echo e(trans('admin.category_customer')); ?></label>
                    <?php echo Form::select('catalogue_id', $category, old('catalogue_id'), ['class' => 'form-control w-full tom-select tom-select-custom js_category', 'placeholder' => '']); ?>
                </div>
                <?php /*<div class="mt-3">
                    <label class="form-label text-base font-semibold">Email</label>
                    <?php echo Form::text('email', '', ['class' => 'form-control w-full', 'placeholder' => 'Email']); ?>
                </div>
                */ ?>
                <div class="mt-3">
                    <label class="form-label text-base font-semibold"> <?php echo e(trans('admin.code_customer')); ?></label>
                    <div class="input-group ">
                        <div class="input-group-text">TVA</div>
                        <?php echo Form::text('code', '', ['class' => 'form-control w-full', 'placeholder' => '']); ?>
                    </div>
                </div>

                <div class="mt-3">
                    <label class="form-label text-base font-semibold"><?php echo e(trans('admin.fullname')); ?></label>
                    <?php echo Form::text('name', '', ['class' => 'form-control w-full', 'placeholder' => '']); ?>
                </div>
                <div class="mt-3">
                    <label class="form-label text-base font-semibold"><?php echo e(trans('admin.phone')); ?></label>
                    <?php echo Form::text('phone', '', ['class' => 'form-control w-full', 'placeholder' => '']); ?>
                </div>
                <div class="mt-3">
                    <label class="form-label text-base font-semibold"><?php echo e(trans('admin.address')); ?></label>
                    <?php echo Form::text('address', '', ['class' => 'form-control w-full', 'placeholder' => '']); ?>
                </div>
                <div class="mt-3">
                    <label class="form-label text-base font-semibold">Mật khẩu 密碼 *</label>
                    <?php echo Form::text('password', !empty(old('password')) ? old('password') : '123456', ['class' => 'form-control w-full', 'placeholder' => '']); ?>
                </div>
                <div class="mt-3">
                    <?php echo $__env->make('user.backend.user.image',['action' => 'create'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </div>
                <div class="text-right mt-5">
                    <button type="submit" class="btn btn-primary"><?php echo e(trans('admin.create')); ?></button>
                </div>
            </div>
        </div>
    </form>

</div>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('javascript'); ?>
<script>
    $(document).on('change', '.js_category', function(e) {
        var id = $(this).val()
        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            url: "<?php echo route('customer_categories.show') ?>",
            type: "POST",
            dataType: "JSON",
            data: {
                id: id
            },
            success: function(data) {
                $('.input-group-text').html(data.code + '- ')
            },
        });
    })
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('dashboard.layout.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/quyenitc/vanchuyenhangtrung.com/resources/views/customer/backend/customer/create.blade.php ENDPATH**/ ?>