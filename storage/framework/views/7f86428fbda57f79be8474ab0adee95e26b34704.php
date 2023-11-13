<?php $__env->startSection('title'); ?>
<title>Thêm mới</title>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
<?php
$array = array(
    [
        "title" => "Danh sách",
        "src" => route('shippings.index'),
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
    <form class="grid grid-cols-12 gap-6 mt-5" role="form" action="<?php echo e(route('shippings.store')); ?>" method="post" enctype="multipart/form-data">
        <div class="col-span-12">
            <!-- BEGIN: Form Layout -->
            <div class=" box p-5">
                <?php echo $__env->make('components.alert-error', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php echo csrf_field(); ?>
                <div class="tab-content">
                    <div id="example-tab-homepage" class="tab-pane leading-relaxed active" role="tabpanel" aria-labelledby="example-homepage-tab">
                        <div>
                            <label class="form-label text-base font-semibold">Tiêu đề</label>
                            <?php echo Form::text('title', '', ['class' => 'form-control w-full']); ?>
                        </div>
                        <div class="mt-3 grid grid-cols-2 gap-6">
                            <div class="">
                                <label class="form-label text-base font-semibold">Cân nặng min</label>
                                <?php echo Form::text('weight_min', '', ['class' => 'form-control w-full']); ?>
                            </div>
                            <div class="">
                                <label class="form-label text-base font-semibold">Cân nặng max</label>
                                <?php echo Form::text('weight_max', '', ['class' => 'form-control w-full']); ?>
                            </div>
                        </div>
                        <div class="mt-3">
                            <label class="form-label text-base font-semibold">Giá tiền</label>
                            <?php echo Form::text('price', '', ['class' => 'form-control w-full int']); ?>
                        </div>
                        <div class="text-right mt-5">
                            <button type="submit" class="btn btn-primary">Lưu</button>
                        </div>
                    </div>

                </div>

            </div>
            <!-- END: Form Layout -->
        </div>

    </form>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('javascript'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('library/coloris/coloris.min.css')); ?>">
<script type="text/javascript" src="<?php echo e(asset('library/coloris/coloris.min.js')); ?>"></script>
<script>
    Coloris({
        el: '.coloris',
        swatches: [
            '#264653',
            '#2a9d8f',
            '#e9c46a',
            '#f4a261',
            '#e76f51',
            '#d62828',
            '#023e8a',
            '#0077b6',
            '#0096c7',
            '#00b4d8',
            '#48cae4'
        ]
    });
    Coloris.setInstance('.instance1', {
        theme: 'pill',
        themeMode: 'dark',
        formatToggle: true,
        closeButton: true,
        clearButton: true,
        swatches: [
            '#067bc2',
            '#84bcda',
            '#80e377',
            '#ecc30b',
            '#f37748',
            '#d56062'
        ]
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('dashboard.layout.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/quyenitc/vanchuyenhangtrung.com/resources/views/shippings/backend/create.blade.php ENDPATH**/ ?>