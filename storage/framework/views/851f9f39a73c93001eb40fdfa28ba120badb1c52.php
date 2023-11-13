<?php $__env->startSection('title'); ?>
<title>Chi tiết đơn nhập kho <?php echo e($detail->code_cn); ?></title>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
<?php
$array = array(
    [
        "title" => "Danh sách đơn nhập kho",
        "src" => route('warehouses.index'),
    ],
    [
        "title" => "Chi tiết đơn nhập kho" . $detail->code_cn,
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
            Danh sách đơn tự sinh <?php echo e($detail->code_cn); ?>

        </h1>
    </div>
    <div class=" col-span-12 lg:col-span-12">
        <table class="table table-report">
            <thead class="table-dark">
                <tr>
                    <th class="">NGÀY 日期</th>
                    <th class="whitespace-nowrap">MÃ VẬN ĐƠN 运单号</th>
                    <th class="whitespace-nowrap">MÃ VN</th>
                    <th class="">CÂN NẶNG(KG) 重量 </th>
                    <th class="">Số lượng sản phẩm 数量产品</th>
                    <th class="">TÊN SẢN PHẨM 品名 </th>
                    <th class="">TÊN SẢN PHẨM VN </th>
                    <th class="">GIÁ</th>
                    <th class="">MÃ KHÁCH 客户码 </th>
                    <th class=" text-center">#</th>
                </tr>
            </thead>
            <tbody>
                <tr class="odd " id="post-<?php echo $detail->id; ?>">
                    <td>
                        <?php echo e($detail->date); ?>

                    </td>
                    <td>
                        <?php echo e($detail->code_cn); ?>

                    </td>
                    <td class="whitespace-nowrap">
                        <?php echo e($detail->code_vn); ?>

                    </td>
                    <td>
                        <?php echo e($detail->weight); ?>

                    </td>
                    <td>
                        <?php echo e($detail->quantity); ?>

                    </td>
                    <td>
                        <?php echo e($detail->name_cn); ?>

                    </td>
                    <td>
                        <?php echo e($detail->name_vn); ?>

                    </td>
                    <td>
                        <?php echo e($detail->price); ?>

                    </td>
                    <td>
                        <?php echo e(!empty($detail->customer->code)?$detail->customer->code:''); ?>

                    </td>
                    <td class="table-report__action">
                        <div class="flex justify-center items-center">
                            <a class="flex items-center mr-3" href="javascript:void(0)" onclick="PrintBarcode('PrintShow')">
                                <i data-lucide="printer" class="w-8 h-8 mr-1"></i>
                            </a>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('javascript'); ?>
<div id="PrintShow" style="float:left;margin-left:0px;display:none">
    <table style="width:320px;height:600px;border:1px solid #000;font-size:15px !important">
        <tr>
            <td style="border:1px solid #000" colspan="4">
                <div style="margin-top:2px;font-weight:bold;display: flex;justify-content:center;align-items: center;" id="inputdata">
                    <img src="<?php echo "data:image/png;base64," . \Milon\Barcode\DNS1D::getBarcodePNG($detail->code_vn, "C128", 2, 70); ?>" id="imgbarcode" style="width: 272px;height: 70px;margin: 0px auto;" />
                </div>
                <p style="text-align: center;font-weight: bold;font-size:20px;margin-top: 10px;"><span class="code_vn"><?php echo e($detail->code_vn); ?></span></p>

            </td>
        </tr>
        <tr>
            <td style="border:1px solid #000" colspan="2">
                <?php echo e($str_2['content']); ?>

            </td>
            <td style="border:1px solid #000" colspan="4">
                <p class="toPrint">ĐẾN: <?php echo e($detail->fullname); ?> - <?php echo e($detail->address); ?></p>
                <p>SĐT: <span class="phonePrint"><?php echo e($detail->phone); ?></span></p>
            </td>
        </tr>
        <tr>
            <td style="border:1px solid #000;text-align: center;" colspan="2">Tên sản phẩm</td>
            <td style="border:1px solid #000;text-align: center;">Số lượng</td>
            <td style="border:1px solid #000;text-align: center;">Trọng lượng</td>
        </tr>
        <tr>
            <td class="namePrint" style="border:1px solid #000;text-align: center;" colspan="2"><?php echo e($detail->name_vn); ?></td>
            <td class="quantityPrint" style="border:1px solid #000;text-align:center"><?php echo e($detail->quantity); ?></td>
            <td class="weightPrint" style="border:1px solid #000;text-align:center"><?php echo e($detail->weight); ?></td>
        </tr>
        <tr>
            <td style="border:1px solid #000;text-align: center;font-weight: bold;" colspan="3" rowspan="2" class="codeCNPrint">
                <?php echo e($detail->code_cn); ?>


            </td>
            <td style="border:1px solid #000;text-align:center">Ngày</td>
        </tr>
        <tr>
            <td class="datePrint" style="border:1px solid #000;text-align: center;">
                <?php echo e($detail->created_at); ?>

            </td>
        </tr>
        <tr>
            <td style="border:1px solid #000;text-align: center;" colspan="2">
                Khu vực
            </td>
            <td style="border:1px solid #000;text-align: center;font-weight: bold;" colspan="2">
                MBT
            </td>
        </tr>
        <tr>
            <td style="border:1px solid #000;height:100px" colspan="4" class="strPrint">
                <?php echo $str_1['content']; ?>

            </td>
        </tr>

    </table>
</div>
<script>
    function PrintBarcode(divId) {
        var printContents = document.getElementById(divId).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print({
            numberOfCopies: 5
        });
        document.body.innerHTML = originalContents;
    }

</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('dashboard.layout.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/quyenitc/vanchuyenhangtrung.com/resources/views/warehouses/backend/show.blade.php ENDPATH**/ ?>