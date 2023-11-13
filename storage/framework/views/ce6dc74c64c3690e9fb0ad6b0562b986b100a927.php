<?php $__env->startSection('title'); ?>
<title>Tạo bao 集包</title>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
<?php
$array = array(
    [
        "title" => "Danh sách bao 包清单",
        "src" => route('packagings.index'),
    ],
    [
        "title" => "Tạo bao 集包",
        "src" => 'javascript:void(0)',
    ]
);
echo breadcrumb_backend($array);
?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<?php
$customer_id = !empty(old('customer_id')) ? old('customer_id') : (!empty(request()->get('customer_id')) ? request()->get('customer_id') : '');
?>
<div class="content hidePrint">
    <div class=" flex items-center mt-8">
        <h1 class="text-lg font-medium mr-auto">
            Tạo bao 集包
        </h1>
    </div>
    <form class="grid grid-cols-12 gap-6 mt-5" role="form" action="<?php echo e(route('packagings.store')); ?>" method="post" enctype="multipart/form-data">
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
                        <?php echo Form::text('fullname', old('fullname'), ['class' => 'form-control w-full', 'placeholder' => '', 'disabled']); ?>
                    </div>
                </div>
                <div class="mt-3">
                    <label class="form-label text-base font-semibold">Ngày 日期*</label>
                    <?php echo Form::text('date', !empty(old('date')) ? old('date') : date('Y-m-d H:i:s'), ['class' => 'form-control w-full', 'placeholder' => '', 'autocomplete' => 'off']); ?>
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
                            <?php
                            if (!empty(old('products'))) {
                                $products = old('products');
                            } else {
                                $products = $products;
                            }
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
                                    <?php echo Form::text('value_weight', '', ['class' => 'form-control w-full', 'placeholder' => '', 'autocomplete' => 'off']); ?>

                                </td>
                            </tr>
                            <tr>
                                <td class="text-right font-bold">
                                    Số lượng kiện hàng 数量件
                                </td>
                                <td class="" colspan="4">
                                    <?php echo Form::text('value_quantity', '', ['class' => 'form-control w-full', 'placeholder' => '', 'autocomplete' => 'off']); ?>

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
    <?php if(!empty($detail)): ?>
    <div class=" col-span-12 lg:col-span-12">
        <table class="table table-report -mt-2">
            <thead>

                <tr>
                    <th class="whitespace-nowrap">NGÀY 日期 </th>
                    <th class="whitespace-nowrap">MÃ BAO 包号 </th>
                    <th class="whitespace-nowrap">MÃ VẬN ĐƠN 运单号 </th>
                    <th class="whitespace-nowrap">CÂN NẶNG(KG) 重量 </th>
                    <th class="whitespace-nowrap">MÃ KHÁCH 客户码 </th>
                    <th class="whitespace-nowrap">TÊN KHÁCH HÀNG名字</th>
                    <th class="whitespace-nowrap text-center">#</th>
                </tr>
            </thead>
            <tbody>
                <tr class="odd " id="post-<?php echo $v->id; ?>">

                    <td><?php echo e($detail->created_at); ?></td>
                    <td>
                        <a href="<?php echo e(route('packagings.show',['id' => $detail->id])); ?>" class="text-danger font-bold"><?php echo e($detail->code); ?></a>
                    </td>
                    <td>
                        <a href="<?php echo e(route('packagings.show',['id' => $detail->id])); ?>" class="text-primary font-bold" style="text-decoration: underline;">Mã vận đơn</a>
                    </td>
                    <td>
                        <?php echo e($detail->total_weight); ?>

                    </td>

                    <td>
                        <?php echo e(!empty($detail->customer) ?$detail->customer->code:''); ?>

                    </td>
                    <td>
                        <?php echo e(!empty($detail->customer) ?$detail->customer->name:''); ?>

                    </td>
                    <td class="">
                        <a class="flex items-center mr-3" href="javascript:void(0)" onclick="PrintDivContent('Print')">
                            <i data-lucide="printer" class="w-6 h-6 mr-1 text-primary"></i>
                        </a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
    <?php if(!empty($detail)): ?>
    <div id="Print" style="float:left;margin-left:0px;display:none">
        <table style="width:320px;height:200px;border:1px solid #000;font-size:20px !important;text-align:center">
            <tbody>
                <tr>
                    <td style="border:1px solid #000;font-size:18px">
                        <div id="inputdatat">
                            <img style="margin: 0px auto;" src="<?php echo "data:image/png;base64," . \Milon\Barcode\DNS1D::getBarcodePNG($detail->code, "C128", 2, 70); ?>" id="imgbarcode" width="200" height="80">
                        </div>
                        <div style="text-align: center;font-weight: bold;font-size: 20px;margin: 10px 0px;">
                            <?php echo e($detail->code); ?>

                        </div>
                        <p style="font-size:18px;text-align:center" class="makhachprint"><?php echo e(!empty($detail->customer) ?$detail->customer->code:''); ?> - <?php echo e(!empty($detail->customer) ?$detail->customer->name:''); ?> / <?php echo e(number_format((float)$detail->value_weight, 2, '.', '')); ?> kg</p>
                        <p style="font-size:18px;text-align:center" class=""> Số kiện: <span id="sokien"><?php echo e($detail->packaging_relationships->count()); ?></span></p>
                    </td>
                </tr>
                <tr>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>

<?php $__env->stopSection(); ?>
<?php $__env->startPush('javascript'); ?>
<?php echo $__env->make('packaging.backend.script', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<script>
    function PrintDivContent(divId) {
        var printContents = document.getElementById(divId).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('dashboard.layout.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/quyenitc/vanchuyenhangtrung.com/resources/views/packaging/backend/create.blade.php ENDPATH**/ ?>