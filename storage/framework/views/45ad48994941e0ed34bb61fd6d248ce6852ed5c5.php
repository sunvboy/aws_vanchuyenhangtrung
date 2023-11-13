
<?php $__env->startSection('title'); ?>
    <title> Danh sách bao 包清单</title>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <?php
    $array = array(
        [
            "title" => " Danh sách bao 包清单",
            "src" => route('packagings.index'),
        ]
    );
    echo breadcrumb_backend($array);
    ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="content">
        <div class="flex justify-between items-center mt-5">
            <h1 class=" text-lg font-medium">
                Danh sách bao 包清单 <?php echo e($detail->code); ?>

            </h1>
        </div>
        <div class="grid grid-cols-12 gap-6 mt-5">

            <!-- BEGIN: Data List -->
            <div class=" col-span-12 lg:col-span-12">
                <table class="table table-report -mt-2">
                    <thead>
                    <tr>
                        <td colspan="2" class="text-center font-bold" style="border-radius: 0px;color: #000">Tổng kiện
                            总数量单: <?php echo e(!empty($data)?count($data) : 0); ?> </td>
                        <td class=" text-white" style="background-color: red">Tổng cân 总重量</td>
                        <td class=" text-white font-bold"
                            style="background-color: red;border-radius: 0px;"><?php echo e(number_format((float)$total_weight, 2, '.', '')); ?></td>
                        <td class=" text-black" style="background-color: yellow">Tổng số cân thực tế 包袋重量</td>
                        <td class=" text-black font-bold"
                            style="background-color: yellow;border-radius: 0px;"><?php echo e(number_format((float)$detail->value_weight, 2, '.', '')); ?></td>
                    </tr>
                    <tr>
                        <th class="whitespace-nowrap">STT</th>
                        <th class="whitespace-nowrap">NGÀY 日期</th>
                        <th class="whitespace-nowrap">MÃ BAO 包号</th>
                        <th class="whitespace-nowrap">Mã trung 中国单号</th>
                        <th class="whitespace-nowrap">Mã việt 越南单号</th>
                        <th class="whitespace-nowrap">CÂN NẶNG(KG) 重量</th>
                        <th class="whitespace-nowrap">MÃ KHÁCH 客户码</th>
                        <th class="whitespace-nowrap">TÊN KHÁCH HÀNG名字</th>
                        <th class="whitespace-nowrap text-center">#</th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="odd " id="post-<?php echo $v->id; ?>">
                        <?php

                        ?>
                        <td><?php echo e($k+1); ?></td>
                        <td><?php echo e($detail->created_at); ?></td>
                        <td>
                            <?php echo e($detail->code); ?>

                        </td>

                        <td>
                            <?php echo e(!empty($v->warehouses_vietnam) ? $v->warehouses_vietnam->code_cn : (!empty($v->warehouses_china) ? $v->warehouses_china->code_cn : '')); ?>

                        </td>
                        <td>
                            <?php echo e(!empty($v->warehouses_vietnam) ? $v->warehouses_vietnam->code_vn : (!empty($v->warehouses_china) ? $v->warehouses_china->code_vn : '')); ?>

                        </td>

                        <td>
                            <?php echo e($v->weight); ?>

                        </td>
                        <td>
                            <?php echo e(!empty($detail->customer) ?$detail->customer->code:''); ?>

                        </td>
                        <td>
                            <?php echo e(!empty($detail->customer) ?$detail->customer->name:''); ?>

                        </td>
                        <td class="">
                            <a class="flex items-center mr-3" href="javascript:void(0)" onclick="getinfo(<?php echo e($detail->id); ?>)">
                                <i data-lucide="printer" class="w-6 h-6 mr-1 text-primary"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            <!-- END: Data List -->
        </div>
    </div>
    <div id="Print" style="float:left;margin-left:0px;display:none">
        <table style="width:420px;height:300px;border:1px solid #000;font-size:40px !important;text-align:center">
            <tbody>
            <tr>
                <td style="border:1px solid #000;">
                    <div id="inputdatat">
                        <img style="margin: 0px auto;"
                             src="<?php echo "data:image/png;base64," . \Milon\Barcode\DNS1D::getBarcodePNG($detail->code, "C128", 2, 80); ?>"
                             id="imgbarcode" width="300" height="80">
                    </div>
                    <div style="text-align: center;font-weight: bold;margin: 20px 0px;">
                        <?php echo e(!empty($detail->customer) ?$detail->customer->code:''); ?><?php echo e($detail->code); ?>

                    </div>
                    <p style="text-align:center;margin-bottom: 20px;font-size: 30px;"
                       class="makhachprint"><?php echo e(!empty($detail->customer) ?$detail->customer->code:''); ?>

                        - <?php echo e(!empty($detail->customer) ?$detail->customer->name:''); ?>

                        / <?php echo e(number_format((float)$detail->value_weight, 2, '.', '')); ?> kg</p>
                    <p style="text-align:center;font-size: 30px;" class=""> Số kiện: <span
                            id="sokien"><?php echo e($detail->packaging_relationships->count()); ?></span></p>
                </td>
            </tr>

            </tbody>
        </table>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('javascript'); ?>
    <script>
        function PrintDivContent(divId) {
            var printContents = document.getElementById(divId).innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }

        function getinfo(id) {
            PrintDivContent("Print");

        }
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('dashboard.layout.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\vanchuyenhangtrung.local\resources\views/packaging/backend/show.blade.php ENDPATH**/ ?>