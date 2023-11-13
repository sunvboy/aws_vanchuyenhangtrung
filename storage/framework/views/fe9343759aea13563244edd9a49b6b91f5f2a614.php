<!--Start: title SEO -->
<?php $__env->startSection('title'); ?>
<title>Quản trị website</title>
<?php $__env->stopSection(); ?>
<!--END: title SEO -->
<?php $__env->startSection('breadcrumb'); ?>
<?php
$array = array(
    [
        "title" => "Dashboard",
        "src" => route('admin.dashboard'),
    ]
);
echo breadcrumb_backend($array);
?>
<?php $__env->stopSection(); ?>
<!--Start: add javascript only index.html -->
<?php $__env->startSection('css-dashboard'); ?>
<?php $__env->stopSection(); ?>
<!--END: add javascript only index.html -->
<!-- START: main -->
<?php $__env->startSection('content'); ?>
    <?php if(Auth::user()->id == 18 || Auth::user()->id == 1): ?>
<div class="content">
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 2xl:col-span-12">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                <!-- BEGIN: General Report -->

                <div class="col-span-12 mt-6">
                    <div id="progressbar-label" class="">
                        <div class="preview" style="display: block;">
                            <div class="progress h-4">
                                <div class="progress-bar w-1/2" style="width: <?php echo e($megabytes_used); ?>%;" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"><?php echo e($megabytes_used); ?>%</div>
                            </div>

                        </div>
                    </div>
                    <div class="report-box mt-24">
                        <div class=" sm:mt-4 box py-0 xl:py-5 grid grid-cols-1 md:grid-cols-4 gap-0 divide-y xl:divide-y-0 divide-x divide-dashed divide-slate-200 dark:divide-white/5">
                            <div class="report-box__item py-5 xl:py-0 px-5">
                                <a href="javascript:void(0)" class="report-box__content">
                                    <div class="flex">
                                        <div class="report-box__item__icon text-success bg-success/20 border border-success/20 flex items-center justify-center rounded-full">
                                            <i data-lucide="hard-drive"></i>
                                        </div>
                                    </div>
                                    <div class="text-2xl font-medium leading-7 mt-6"><?php echo e($megabytes_used); ?> GB</div>
                                    <div class="text-slate-500 mt-1">Dung lượng</div>
                                </a>
                            </div>
                            <div class="report-box__item py-5 xl:py-0 px-5 ">
                                <a href="javascript:void(0)" class="report-box__content">
                                    <div class="flex">
                                        <div class="report-box__item__icon text-warning bg-warning/20 border border-warning/20 flex items-center justify-center rounded-full">
                                            <i data-lucide="shopping-bag"></i>
                                        </div>
                                    </div>
                                    <div class="text-2xl font-medium leading-7 mt-6"><?php echo e($deliveries); ?></div>
                                    <div class="text-slate-500 mt-1">Giao hàng</div>
                                </a>
                            </div>
                            <div class="report-box__item py-5 xl:py-0 px-5 ">
                                <a href="javascript:void(0)" class="report-box__content">
                                    <div class="flex">
                                        <div class="report-box__item__icon text-warning bg-warning/20 border border-warning/20 flex items-center justify-center rounded-full">
                                            <i data-lucide="shopping-bag"></i>
                                        </div>
                                    </div>
                                    <div class="text-2xl font-medium leading-7 mt-6"><?php echo e($warehouses); ?></div>
                                    <div class="text-slate-500 mt-1">
                                        Nhập kho 入库
                                    </div>
                                </a>
                            </div>
                            <div class="report-box__item py-5 xl:py-0 px-5 sm:!border-t-0">
                                <a href="javascript:void(0)" class="report-box__content">
                                    <div class="flex">
                                        <div class="report-box__item__icon text-pending bg-pending/20 border border-pending/20 flex items-center justify-center rounded-full">
                                            <i data-lucide="credit-card"></i>
                                        </div>
                                    </div>
                                    <div class="text-2xl font-medium leading-7 mt-6"><?php echo e($packagings); ?></div>
                                    <div class="text-slate-500 mt-1">
                                        Đóng bao 打包
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="report-box mt-24">

                        <div class=" sm:mt-4 box py-0 xl:py-5 grid grid-cols-1 md:grid-cols-4 gap-0 divide-y xl:divide-y-0 divide-x divide-dashed divide-slate-200 dark:divide-white/5">

                            <div class="report-box__item py-5 xl:py-0 px-5 sm:!border-t-0">
                                <a href="javascript:void(0)" class="report-box__content">
                                    <div class="flex">
                                        <div class="report-box__item__icon text-pending bg-pending/20 border border-pending/20 flex items-center justify-center rounded-full">
                                            <i data-lucide="dollar-sign"></i>
                                        </div>
                                    </div>
                                    <div class="text-2xl font-medium leading-7 mt-6"><?php echo e(number_format($customer_order,'0',',','.')); ?> VNĐ</div>
                                    <div class="text-slate-500 mt-1">
                                        Tổng tiền khách mua
                                    </div>
                                </a>
                            </div>
                            <div class="report-box__item py-5 xl:py-0 px-5 sm:!border-t-0">
                                <a href="javascript:void(0)" class="report-box__content">
                                    <div class="flex">
                                        <div class="report-box__item__icon text-pending bg-pending/20 border border-pending/20 flex items-center justify-center rounded-full">
                                            <i data-lucide="dollar-sign"></i>
                                        </div>
                                    </div>
                                    <div class="text-2xl font-medium leading-7 mt-6"><?php echo e(number_format($pending_payment,'0',',','.')); ?> VNĐ</div>
                                    <div class="text-slate-500 mt-1">
                                        Tổng tiền đang chờ thanh toán

                                    </div>
                                </a>
                            </div>

                            <div class="report-box__item py-5 xl:py-0 px-5 sm:!border-t-0">
                                <a href="javascript:void(0)" class="report-box__content">
                                    <div class="flex">
                                        <div class="report-box__item__icon text-pending bg-pending/20 border border-pending/20 flex items-center justify-center rounded-full">
                                            <i data-lucide="dollar-sign"></i>
                                        </div>
                                    </div>
                                    <div class="text-2xl font-medium leading-7 mt-6"><?php echo e(number_format($price_returns,'0',',','.')); ?> VNĐ</div>
                                    <div class="text-slate-500 mt-1">
                                        Tổng tiền hoàn trả khách

                                    </div>
                                </a>
                            </div>
                            <div class="report-box__item py-5 xl:py-0 px-5 sm:!border-t-0">
                                <a href="javascript:void(0)" class="report-box__content">
                                    <div class="flex">
                                        <div class="report-box__item__icon text-pending bg-pending/20 border border-pending/20 flex items-center justify-center rounded-full">
                                            <i data-lucide="user-check"></i>
                                        </div>
                                    </div>
                                    <div class="text-2xl font-medium leading-7 mt-6"><?php echo e(number_format($customer,'0',',','.')); ?> VNĐ</div>
                                    <div class="text-slate-500 mt-1">
                                        Tổng số dư còn lại của khách
                                    </div>
                                </a>
                            </div>

                        </div>

                    </div>
                </div>
                <!-- END: General Report -->

            </div>
        </div>

    </div>
</div>
    <?php endif; ?>
<?php $__env->stopSection(); ?>
<!-- END: main -->

<?php echo $__env->make('dashboard.layout.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/quyenitc/vanchuyenhangtrung.com/resources/views/dashboard/home/index.blade.php ENDPATH**/ ?>