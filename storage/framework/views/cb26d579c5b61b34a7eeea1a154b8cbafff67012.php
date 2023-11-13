

<?php $__env->startSection('title'); ?>

<title>Thêm mới đơn giao hàng</title>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb'); ?>

<?php

$array = array(

    [

        "title" => "Danh sách đơn giao hàng",

        "src" => route('deliveries.index'),

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

            Thêm mới đơn giao hàng

        </h1>

    </div>

    <form class="grid grid-cols-12 gap-6 mt-5" role="form" action="<?php echo e(route('deliveries.store')); ?>" method="post" enctype="multipart/form-data">

        <div class=" col-span-12">

            <!-- BEGIN: Form Layout -->

            <div class=" box p-5">

                <?php echo $__env->make('components.alert-error', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                <?php echo csrf_field(); ?>

                <div>

                    <label class="form-label text-base font-semibold"><?php echo e(trans('admin.select_customer')); ?></label>

                    <?php echo Form::select('customer_id', $customers, old('customer_id'), ['class' => 'form-control w-full tom-select tom-select-custom', 'placeholder' => '']); ?>

                </div>

                <div class="mt-3">

                    <label class="form-label text-base font-semibold"><?php echo e(trans('admin.code_deliveries')); ?></label>

                    <?php echo Form::text('code', !empty(old('code')) ? old('code') : $code, ['class' => 'form-control w-full', 'placeholder' => '']); ?>

                </div>

                <div class="mt-3 overflow-x-auto">

                    <table class="table">

                        <thead class="table-dark">

                            <tr>

                                <th class="whitespace-nowrap"><?php echo e(trans('admin.code_bill')); ?></th>

                                <th class="whitespace-nowrap"><?php echo e(trans('admin.weight')); ?></th>

                                <th class="whitespace-nowrap"><?php echo e(trans('admin.note')); ?></th>

                                <th class="whitespace-nowrap">#</th>

                            </tr>

                        </thead>

                        <tbody id="list">

                            <?php $products = old('products');

                            if (isset($products) && is_array($products) && count($products)) { ?>

                                <?php foreach ($products['code'] as $key => $val) { ?>

                                    <tr>

                                        <td>

                                            <?php echo Form::text('products[code][]', $products['code'][$key], ['class' => 'form-control w-full codeP', 'placeholder' => '', 'required','data-stt' => $key]); ?>

                                        </td>

                                        <td>

                                            <?php echo Form::text('products[weight][]',!empty( $products['weight'][$key])? $products['weight'][$key]:'', ['class' => 'form-control w-full weight weight-'.$key.'', 'placeholder' => '', '']); ?>

                                        </td>

                                        <td>

                                            <?php echo Form::text('products[note][]', !empty($products['note'][$key]) ? $products['note'][$key] : '', ['class' => 'form-control w-full note', 'placeholder' => '']); ?>

                                        </td>

                                        <td>

                                            <a href="javascript:void(0)" class="js_removeColumn text-danger font-bold">Xóa</a>

                                        </td>

                                    </tr>

                                <?php } ?>

                            <?php } else { ?>

                                <tr>

                                    <td>

                                        <?php echo Form::text('products[code][]', '', ['class' => 'form-control w-full codeP', 'placeholder' => '', 'required' ,'data-stt' => "0"]); ?>

                                    </td>

                                    <td>

                                        <?php echo Form::text('products[weight][]', '', ['class' => 'form-control w-full weight weight-0', 'placeholder' => '', '']); ?>

                                    </td>

                                    <td>

                                        <?php echo Form::text('products[note][]', '', ['class' => 'form-control w-full note', 'placeholder' => '']); ?>

                                    </td>

                                    <td>

                                        <a href="javascript:void(0)" class="js_removeColumn text-danger font-bold">Xóa</a>

                                    </td>

                                </tr>

                            <?php } ?>

                        </tbody>

                        <tfoot>

                            <tr>

                                <td class="text-right font-bold">

                                    <?php echo e(trans('admin.total_weight')); ?>


                                </td>

                                <td class="text-danger font-bold" colspan="3" id="tongsocan">

                                    0

                                </td>

                            </tr>
                            <tr>

                                <td class="text-right font-bold">

                                    Đơn giá

                                </td>

                                <td colspan="3">

                                    <?php echo Form::text('price', !empty(old('price')) ? old('price') : 0, ['class' => 'form-control w-full ', 'placeholder' => '']); ?>

                                </td>

                            </tr>
                            <tr>

                                <td class="text-right font-bold">

                                    Thành tiền

                                </td>

                                <td colspan="3" class="text-danger font-bold" id="tongsotien">

                                    0

                                </td>

                            </tr>
                            <tr class="">

                                <td colspan="4">

                                    <a href="javascript:void(0)" class="add-new btn btn-danger rounded-full h-10 w-10 flex justify-end float-right">

                                        <i data-lucide="plus" class="w-6 h-6 text-white"></i>

                                    </a>

                                </td>

                            </tr>

                        </tfoot>

                    </table>



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

<?php echo $__env->make('delivery.backend.script', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->stopPush(); ?>

<?php echo $__env->make('dashboard.layout.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\vanchuyenhangtrung.local\resources\views/delivery/backend/create.blade.php ENDPATH**/ ?>