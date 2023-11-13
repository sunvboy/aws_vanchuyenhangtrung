<?php $__env->startSection('title'); ?>
<title><?php echo e(trans('admin.update')); ?> <?php echo e($detail->code); ?></title>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
<?php
$array = array(
    [
        "title" => "Danh sách bao 包清单",
        "src" => route('packagings.index'),
    ],
    [
        "title" => trans('admin.update'),
        "src" => 'javascript:void(0)',
    ]
);
echo breadcrumb_backend($array);
?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<?php
$customer_id = !empty(old('customer_id')) ? old('customer_id') : (!empty($detail->customer_id) ? $detail->customer_id : '');
?>
<div class="content">
    <div class=" flex items-center mt-8">
        <h1 class="text-lg font-medium mr-auto">
            <?php echo e(trans('admin.update')); ?> <?php echo e($detail->code); ?>

        </h1>
    </div>
    <form class="grid grid-cols-12 gap-6 mt-5" role="form" action="<?php echo e(route('packagings.update',['id' => $detail->id])); ?>" method="post" enctype="multipart/form-data">
        <div class=" col-span-12">
            <!-- BEGIN: Form Layout -->
            <div class=" box p-5">
                <?php echo $__env->make('components.alert-error', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php echo csrf_field(); ?>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label text-base font-semibold">Mã khách hàng 客户码 *</label>
                        <select name="customer_id" class="form-control tom-select tom-select-custom w-full">
                            <option value="">Chọn khách hàng</option>
                            <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option data-name="<?php echo e($v->name); ?>" value="<?php echo e($v->id); ?>" <?php echo e(!empty($customer_id == $v->id) ? 'selected':''); ?>>
                                <?php echo e($v->code); ?> - <?php echo e($v->name); ?>

                            </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div>
                        <label class="form-label text-base font-semibold">Tên khách hàng 名字</label>
                        <?php echo Form::text('fullname', !empty($detail->customer)?$detail->customer->name:'', ['class' => 'form-control w-full', 'placeholder' => '', 'disabled']); ?>
                    </div>

                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-3">
                    <div>
                        <label class="form-label text-base font-semibold">Mã bao 包号 *</label>
                        <?php echo Form::text('code', $detail->code, ['class' => 'form-control w-full', 'placeholder' => '', 'autocomplete' => 'off']); ?>
                    </div>
                    <div>
                        <label class="form-label text-base font-semibold">Ngày 日期*</label>
                        <?php echo Form::text('date', $detail->created_at, ['class' => 'form-control w-full', 'placeholder' => '', 'autocomplete' => 'off']); ?>
                    </div>
                </div>

                <div class="mt-3">
                    <label class="form-label text-base font-semibold">Mã việt 越南单号</label>
                    <?php echo Form::text('code_vn', '', ['class' => 'form-control w-full js_codevn', 'placeholder' => '', 'autocomplete' => 'off']); ?>
                </div>
                <div class="mt-3 overflow-x-auto">
                    <table class="table">
                        <thead class="table-dark">
                            <tr>
                                <th class="whitespace-nowrap hidden">Mã trung 中国单号</th>
                                <th class="whitespace-nowrap">Mã việt 越南单号</th>
                                <th class="whitespace-nowrap"><?php echo e(trans('admin.weight')); ?></th>
                                <th class="whitespace-nowrap">#</th>
                            </tr>
                        </thead>
                        <tbody id="list">
                            <?php $products = !empty(old('products')) ? old('products') : json_decode($detail->products, TRUE);
                            if (isset($products) && is_array($products) && count($products)) { ?>
                                <?php foreach ($products['code'] as $key => $val) { ?>

                                    <tr>
                                        <td class="hidden">
                                            <?php echo Form::text('products[code][]', !empty($products['code'][$key]) ? $products['code'][$key] : '', ['class' => 'form-control w-full codeP  codeCN-' . $key . '', 'data-type' => 'china', 'data-stt' => $key]); ?>
                                        </td>
                                        <td>
                                            <?php echo Form::text('products[code_vn][]', !empty($products['code_vn'][$key]) ? $products['code_vn'][$key] : '', ['class' => 'form-control w-full codeP codeFirst codeVN-' . $key . '', 'data-type' => 'vietnamese', 'data-stt' => $key]); ?>
                                        </td>
                                        <td>
                                            <?php echo Form::text('products[id][]', !empty($products['id'][$key]) ? $products['id'][$key] : '', ['class' => 'form-control hidden w-full ids-' . $key . '', 'placeholder' => '', '']); ?>
                                            <?php echo Form::text('products[weight][]', !empty($products['weight'][$key]) ? $products['weight'][$key] : '', ['class' => 'form-control w-full weight weight-' . $key . '', 'placeholder' => '', '']); ?>
                                        </td>
                                        <td>
                                            <a href="javascript:void(0)" class="js_removeColumn text-danger font-bold">Xóa</a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            <?php } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td class="text-right font-bold">
                                    <?php echo e(trans('admin.total_weight')); ?>

                                </td>
                                <td class="text-danger font-bold" colspan="4" id="tongsocan">
                                    0
                                </td>
                            </tr>
                            <tr>
                                <td class="text-right font-bold">
                                    Tổng số cân thực tế 包袋重量
                                </td>
                                <td class="" colspan="4">
                                    <?php echo Form::text('value_weight', $detail->value_weight, ['class' => 'form-control w-full', 'placeholder' => '', 'autocomplete' => 'off']); ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-right font-bold">
                                    Số lượng kiện hàng 数量件
                                </td>
                                <td class="" colspan="4">
                                    <?php echo Form::text('value_quantity', $detail->quantity_weight, ['class' => 'form-control w-full', 'placeholder' => '', 'autocomplete' => 'off']); ?>
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
                    <button type="submit" class="btn btn-primary"><?php echo e(trans('admin.update')); ?></button>
                </div>
            </div>
        </div>
    </form>

</div>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('javascript'); ?>
<?php echo $__env->make('packaging.backend.script', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('dashboard.layout.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/quyenitc/vanchuyenhangtrung.com/resources/views/packaging/backend/edit.blade.php ENDPATH**/ ?>