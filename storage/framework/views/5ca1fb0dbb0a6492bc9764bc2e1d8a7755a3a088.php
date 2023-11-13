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
    <div class="content hidePrint">
        <div class=" flex items-center justify-between mt-8">
            <h1 class="text-lg font-medium mr-auto">
                Tạo bao 集包
            </h1>
            <div class="flex space-x-1">
                <a href="<?php echo e(route('packagings.create', ['id' => $detail->id, 'customer_id' => $detail->customer_id])); ?>" class="btn btn-primary text-white ">
                     Tạo bao  集包
                </a>
                <a href="javascript:void(0)" class="btn btn-success text-white getinfo">
                    <i data-lucide="printer" class="w-4 h-4 mr-1"></i> Print
                </a>
            </div>
        </div>
        <form class="grid grid-cols-12 gap-6 mt-5" role="form" action="<?php echo e(route('packagings.store')); ?>" method="post" enctype="multipart/form-data">
            <div class=" col-span-12">
                <!-- BEGIN: Form Layout -->
                <div class=" box p-5">
                    <?php echo $__env->make('components.alert-error', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    <?php echo csrf_field(); ?>
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="form-label text-base font-semibold">Mã khách hàng 客户码 *</label>
                            <select name="customer_id" class="form-control tom-select tom-select-custom w-full">
                                <option value="">Chọn khách hàng</option>
                                <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option data-name="<?php echo e($v->name); ?>" value="<?php echo e($v->id); ?>" <?php echo e(!empty($detail->customer_id == $v->id) ? 'selected':''); ?>>
                                        <?php echo e($v->code); ?> - <?php echo e($v->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
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
                                <th class="whitespace-nowrap">Mã việt 越南单号</th>
                                <th class="whitespace-nowrap"><?php echo e(trans('admin.weight')); ?></th>
                                <th class="whitespace-nowrap">#</th>
                            </tr>
                            </thead>
                            <tbody id="list">

                            <?php $products = $detail->packaging_relationships;
                            if (!empty($products) && count($products) > 0) { ?>
                            <?php foreach ($products as $key => $val) { ?>
                            <tr class="<?php echo e($val->code_vn); ?>">
                                <td>
                                    <?php echo Form::text('product_code', $val->code_vn, ['class' => 'form-control w-full weight', 'data-id' =>$val->id, 'placeholder' => '', 'required']); ?>
                                </td>
                                <td>
                                    <?php echo Form::text('product_weight', $val->weight, ['class' => 'form-control w-full', 'data-id' =>$val->id, 'placeholder' => '']); ?>
                                </td>
                                <td>
                                    <a href="javascript:void(0)" class="js_removeColumn text-danger font-bold" data-id="<?php echo $val->id?>" data-warehouse-id="<?php echo $val->warehouse_id?>" data-code="<?php echo $val->code_vn?>">Xóa</a>
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
                                    <?php echo e($detail->total_weight); ?>

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
                                    <?php echo Form::text('value_quantity', $detail->value_quantity, ['class' => 'form-control w-full', 'placeholder' => '', 'autocomplete' => 'off','disabled']); ?>

                                </td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </form>
        <?php if(!empty($detailOld)): ?>
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
                    <tr class="odd">

                        <td><?php echo e($detailOld->created_at); ?></td>
                        <td>
                            <a href="<?php echo e(route('packagings.show',['id' => $detailOld->id])); ?>" class="text-danger font-bold"><?php echo e($detailOld->code); ?></a>
                        </td>
                        <td>
                            <a href="<?php echo e(route('packagings.show',['id' => $detailOld->id])); ?>" target="_blank" class="text-primary font-bold" style="text-decoration: underline;">Mã vận đơn</a>
                        </td>
                        <td>
                            <?php echo e($detailOld->total_weight); ?>

                        </td>

                        <td>
                            <?php echo e(!empty($detailOld->customer) ?$detailOld->customer->code:''); ?>

                        </td>
                        <td>
                            <?php echo e(!empty($detailOld->customer) ?$detailOld->customer->name:''); ?>

                        </td>
                        <td class="">
                            <a class="flex items-center mr-3" href="javascript:void(0)" onclick="PrintDivContentOld('PrintOld')">
                                <i data-lucide="printer" class="w-6 h-6 mr-1 text-primary"></i>
                            </a>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
        <?php if(!empty($detailOld)): ?>
            <div id="PrintOld" style="float:left;margin-left:0px;display:none">
                <table style="width:320px;height:200px;border:1px solid #000;font-size:20px !important;text-align:center">
                    <tbody>
                    <tr>
                        <td style="border:1px solid #000;font-size:18px">
                            <div>
                                <img style="margin: 0px auto;" src="<?php echo "data:image/png;base64," . \Milon\Barcode\DNS1D::getBarcodePNG($detailOld->code, "C128", 2, 70); ?>" width="200" height="80">
                            </div>
                            <div style="text-align: center;font-weight: bold;font-size: 20px;margin: 10px 0px;">
                                <?php echo e($detailOld->code); ?>

                            </div>
                            <p style="font-size:18px;text-align:center"><?php echo e(!empty($detailOld->customer) ?$detailOld->customer->code:''); ?> - <?php echo e(!empty($detailOld->customer) ?$detailOld->customer->name:''); ?> / <?php echo e(number_format((float)$detailOld->value_weight, 2, '.', '')); ?> kg</p>
                            <p style="font-size:18px;text-align:center"> Số kiện: <span><?php echo e($detailOld->packaging_relationships->count()); ?></span></p>
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
    <script>
        function PrintDivContentOld(divId) {
            var printContents = document.getElementById(divId).innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }
    </script>
    <div id="Print" style="float:left;margin-left:0px;display:none">

        <table style="width:420px;height:300px;border:1px solid #000;font-size:40px !important;text-align:center">

            <tbody>

            <tr>

                <td style="border:1px solid #000">

                    <div id="inputdatat">

                        <img style="margin: 0px auto;" src="" id="imgbarcode" width="250" height="80">

                    </div>

                    <div class="printInfo">

                    </div>

                </td>

            </tr>



            </tbody>

        </table>

    </div>

    <script>
        var audio = new Audio("<?php echo asset('frontend/images/Tieng-ting-www_tiengdong_com.mp3') ?>");
        var audioError = new Audio("<?php echo asset('frontend/images/63M888piCRKc.mp3') ?>");
        var audioEmpty = new Audio("<?php echo asset('frontend/images/tontai.mp3') ?>");
        var loadStatus = 0;
        var loadStatusError = 0;
        var loadEmpty = 0;
        function playAudio() {
            if (loadStatus === 1) {
                audio.pause();
                audio.currentTime = 0;
                audio.play();
            }
        }
        function playAudioError() {
            if (loadStatusError === 1) {
                audioError.pause();
                audioError.currentTime = 0;
                audioError.play();
            }
        }
        function playAudioEmpty() {
            if (loadEmpty === 1) {
                audioEmpty.pause();
                audioEmpty.currentTime = 0;
                audioEmpty.play();
            }
        }
        function loadHTML(data) {
            var html = '<tr class="' + data.code_vn + '">';
            html += '<td>';
            html += '<input class="form-control w-full" placeholder="" name="product_code" data-id="' + data.id + '" type="text" value="' + data.code_vn + '">';
            html += '</td> ';
            html += '<td>';
            html += '<input class="form-control w-full" placeholder="" name="product_weight" data-id="' + data.id + '" type="text" value="' + data.weight + '">';
            html += '</td>';
            html += '<td>';
            html += '<a href="javascript:void(0)" class="js_removeColumn text-danger font-bold" data-id="' + data.id + '" data-warehouse-id="'+data.warehouse_id+'" data-code="' + data.code_vn + '">Xóa</a>';
            html += '</td> ';
            html += '</tr>';
            return html
        }
        $(document).on('change', 'select[name="customer_id"]', function() {
            var customer_id = $(this).val()
            $.ajax({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                url: "<?php echo route('packagings.new.update') ?>",
                type: "POST",
                dataType: "JSON",
                data: {
                    customer_id: customer_id,
                    id: <?php echo $detail->id?>
                },
                success: function(data) {
                    if(data.status == 200){
                        toastr.success(
                            data.message,
                            "Thành công!"
                        );
                    }else{
                        toastr.error(
                            "Lỗi",
                            "Error!"
                        );
                    }
                },
            });
        });
        $(document).on('keypress', '.js_codevn', function(e) {
            var _this = $(this);
            var key = e.which;
            if (key == 13) // the enter key code
            {
                e.preventDefault()
                var value = $(this).val();
                if (value != '') {
                    //ajax
                    setTimeout(function() {
                        $.ajax({
                            headers: {
                                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                                    "content"
                                ),
                            },
                            url: "<?php echo route('packagings.new.autocomplete') ?>",
                            type: "POST",
                            dataType: "JSON",
                            data: {
                                code: value,
                                id: <?php echo $detail->id?>,
                                customer_id: $('select[name="customer_id"]').find(":selected").val()
                            },
                            success: function(data) {
                                if(data.status == 500){
                                    toastr.error(
                                        data.error,
                                        "Error!"
                                    );
                                    loadEmpty = 1;
                                    playAudioEmpty();
                                    _this.val('').focus();
                                    return false;
                                }
                                if(data.status == 400){
                                    toastr.error(
                                        data.error,
                                        "Error!"
                                    );
                                    loadStatusError = 1;
                                    playAudioError();
                                    _this.val('').focus();
                                    return false;
                                }
                                if(data.status == 600){
                                    toastr.error(
                                        data.error,
                                        "Error!"
                                    );
                                    loadStatusError = 1;
                                    playAudioError();
                                    $('#tongsocan').html(data.weightTotal)
                                    $('input[name="value_weight"]').val(data.weightTotal)
                                    $('input[name="value_quantity"]').val(data.value_quantity)
                                    $('#list').prepend(loadHTML(data.detail));
                                    _this.val('').focus();
                                }
                                if(data.status == 200){
                                    toastr.success(
                                        data.message,
                                        "Thành công!"
                                    );
                                    loadStatus = 1
                                    playAudio()
                                    $('#tongsocan').html(data.weightTotal)
                                    $('input[name="value_weight"]').val(data.weightTotal)
                                    $('input[name="value_quantity"]').val(data.value_quantity)
                                    $('#list').prepend(loadHTML(data.detail));
                                    _this.val('').focus();
                                }
                                /*if (data.error) {
                                    toastr.error(
                                        data.error,
                                        "Error!"
                                    );
                                    if (data.error != 'Mã vận đơn đã tồn tại') {
                                        loadStatusError = 1;
                                        playAudioError();
                                        // $('#list').append(loadHTML());
                                        $('.codeP').focus();
                                        eachSum();
                                    } else {
                                        loadEmpty = 1;
                                        playAudioEmpty();
                                        _this.val('').focus();
                                    }
                                } else {
                                    if(data.detail.emptyCheck){
                                        loadStatusError = 1;
                                        playAudioError();
                                    }else{
                                        loadStatus = 1
                                        playAudio()
                                        eachSum();
                                    }
                                    $('#list').append(loadHTML(data.detail));
                                    $('.codeP').val('').focus();

                                }
                                if (!data.error) {
                                    $('.weight-' + count).val(data.detail.weight)
                                }*/
                            },
                        });
                    }, 10);
                    //endfoeach
                }
            }
        })
        $(document).on('click', '.js_removeColumn', function(e) {
            var id = $(this).attr('data-id');
            var code = $(this).attr('data-code');
            e.preventDefault()
            swal({
                    title: "Hãy chắc chắn rằng bạn muốn thực hiện thao tác này?",
                    text: code,
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Thực hiện!",
                    cancelButtonText: "Hủy bỏ!",
                    closeOnConfirm: false,
                    closeOnCancel: false,
                },
                function(isConfirm) {
                    if (isConfirm) {
                        setTimeout(function() {
                            $.ajax({
                                headers: {
                                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                                        "content"
                                    ),
                                },
                                url: "<?php echo route('packagings.new.delete') ?>",
                                type: "POST",
                                dataType: "JSON",
                                data: {
                                    id: id,
                                    code: code,
                                    packaging_id: <?php echo $detail->id?>,
                                },
                                success: function(data) {
                                    if(data.status == 200){
                                        $('.' + code).remove();
                                        swal({
                                            title: "Xóa thành công!",
                                            text: data.message,
                                            type: "success",
                                        });
                                        $('#tongsocan').html(data.detail.weightTotal)
                                        $('input[name="value_weight"]').val(data.detail.weightTotal)
                                        $('input[name="value_quantity"]').val(data.detail.value_quantity)
                                    }else{
                                        swal({
                                            title: "Xóa không thành công!",
                                            text: "Lỗi",
                                            type: "error",
                                        });
                                    }
                                },
                            });
                        }, 10);
                    } else {
                        swal({
                            title: "Hủy bỏ",
                            text: "Thao tác bị hủy bỏ",
                            type: "error",
                        });
                    }
                }
            );
        })
        $(document).on('keyup', 'input[name="value_weight"]', function(e) {
            /*var _this = $(this);
            var key = e.which;
            if (key == 13) // the enter key code
            {
                e.preventDefault()

            }*/

            var value = $(this).val()
            if (value != '') {
                //ajax
                setTimeout(function() {
                    $.ajax({
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                                "content"
                            ),
                        },
                        url: "<?php echo route('packagings.new.totalWeight') ?>",
                        type: "POST",
                        dataType: "JSON",
                        data: {
                            value: value,
                            id: <?php echo $detail->id?>,
                        },
                        success: function(data) {
                            // toastr.success(
                            //     data.message,
                            //     "Thành công!"
                            // );
                        },
                    });
                }, 10);
                //endfoeach
            }


        })
        $(document).on('click', '.getinfo', function(e) {
            $.ajax({

                headers: {

                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(

                        "content"

                    ),

                },

                type: 'GET',

                url: "<?php echo route('packagings.printer') ?>",

                data: {

                    id: <?php echo $detail->id?>

                },

                contentType: "application/json;charset=utf-8",

                dataType: "json",

                success: function(result) {


                    $('.printInfo').html(result.html)

                    var img = document.getElementById("imgbarcode");

                    img.src = result.code;

                    img.onload = function() {

                        PrintBarcode("Print");

                    }

                }

            });
        })
        $(document).on('keyup', 'input[name="product_weight"]', function(e) {
            var weight = $(this).val()
            var id = $(this).attr('data-id')
            if (weight != '') {
                //ajax
                setTimeout(function() {
                    $.ajax({
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                                "content"
                            ),
                        },
                        url: "<?php echo route('packagings.new.weight') ?>",
                        type: "POST",
                        dataType: "JSON",
                        data: {
                            weight: weight,
                            id: id,
                            packaging_id: <?php echo $detail->id?>,
                        },
                        success: function(data) {
                            // toastr.success(
                            //     data.message,
                            //     "Thành công!"
                            // );
                            $('#tongsocan').html(data.detail.weightTotal)
                            $('input[name="value_weight"]').val(data.detail.weightTotal)
                        },
                    });
                }, 10);
                //endfoeach
            }


        })
        function PrintBarcode(divId) {

            $('.linePrint').remove();

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

<?php echo $__env->make('dashboard.layout.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/quyenitc/vanchuyenhangtrung.com/resources/views/packaging/backend/advanced.blade.php ENDPATH**/ ?>