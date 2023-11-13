

<?php $__env->startSection('title'); ?>
<title>Chi tiết thông báo</title>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
<?php
$array = array(
    [
        "title" => "Danh sách thông báo",
        "src" => route('notifications.index'),
    ],
    [
        "title" => "Chi tiết",
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
            Chi tiết thông báo
        </h1>
    </div>
    <form class="grid grid-cols-12 gap-6 mt-5" role="form" action="" method="post" enctype="multipart/form-data">
        <div class=" col-span-12 ">
            <!-- BEGIN: Form Layout -->
            <div class=" box p-5">
                <?php echo $__env->make('components.alert-error', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php echo csrf_field(); ?>
                <div class="tab-content">
                    <div id="example-tab-homepage" class="tab-pane leading-relaxed active" role="tabpanel" aria-labelledby="example-homepage-tab">
                        <div>
                            <label class="form-label text-base font-semibold">Tiêu đề</label>
                            <div class="">
                                <?php echo e($detail->title); ?>

                            </div>
                        </div>
                        <?php if(!empty($customers)): ?>
                        <div class="mt-3">
                            <label class="form-label text-base font-semibold">Người nhận</label>
                            <div class="flex gap-2 flex-wrap">
                                <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span class="btn btn-primary btn-sm"><?php echo e($item->code); ?> - <?php echo e($item->name); ?></span>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php if(!empty($detail->customer)): ?>
                        <div class="mt-3">
                            <label class="form-label text-base font-semibold">Người nhận</label>
                            <div class="flex gap-2 flex-wrap">
                                <span class="btn btn-primary btn-sm"><?php echo e($detail->customer->code); ?> - <?php echo e($detail->customer->name); ?></span>
                            </div>
                        </div>
                        <?php endif; ?>
                        <div class="mt-3">
                            <label class="form-label text-base font-semibold">Mô tả</label>
                            <div class="">
                                <?php echo e($detail->body); ?>

                            </div>
                        </div>
                        <?php if($detail->type == 'system'): ?>
                        <div class="mt-3">
                            <label class="form-label text-base font-semibold">Nội dung</label>
                            <div class="">
                                <?php echo $detail->content; ?>

                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <!-- END: Form Layout -->
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('dashboard.layout.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\vanchuyenhangtrung.local\resources\views/notification/show.blade.php ENDPATH**/ ?>