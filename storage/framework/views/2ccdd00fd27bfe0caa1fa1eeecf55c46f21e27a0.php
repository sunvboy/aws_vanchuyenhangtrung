<?php $__env->startSection('title'); ?>
    <title>Cập nhập đơn giao hàng</title>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <?php
    $array = array(
        [
            "title" => "Danh sách đơn giao hàng",
            "src" => route('deliveries.index'),
        ],
        [
            "title" => "Cập nhập",
            "src" => 'javascript:void(0)',
        ]
    );
    echo breadcrumb_backend($array);
    ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="content">
        <div class=" flex items-center mt-8 justify-between">
            <h1 class="text-lg font-medium mr-auto">
                Cập nhập đơn giao hàng
            </h1>
            <div class="flex space-x-1">
                <a href="<?php echo e(route('deliveries.create')); ?>" class="btn btn-primary text-white ">
                   Thêm mới
                </a>
                <a href="javascript:void(0)" class="btn btn-success text-white " onclick="getinfo(<?php echo e($detail->id); ?>)">
                    <i data-lucide="printer" class="w-4 h-4 mr-1"></i> Print
                </a>
            </div>
        </div>
        <form class="grid grid-cols-12 gap-6 mt-5" role="form" action="<?php echo e(route('deliveries.update',['id' => $detail->id])); ?>" method="post" enctype="multipart/form-data">
            <div class=" col-span-12">
                <!-- BEGIN: Form Layout -->
                <div class=" box p-5">
                    <?php echo $__env->make('components.alert-error', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    <?php echo csrf_field(); ?>
                    <div>
                        <label class="form-label text-base font-semibold"><?php echo e(trans('admin.select_customer')); ?></label>
                        <?php echo Form::select('customer_id', $customers, $detail->customer_id, ['class' => 'form-control w-full tom-select tom-select-custom', 'placeholder' => '']); ?>
                    </div>
                    <div class="mt-3">
                        <label class="form-label text-base font-semibold"><?php echo e(trans('admin.code_deliveries')); ?></label>
                        <?php echo Form::text('code', $detail->code, ['class' => 'form-control w-full', 'placeholder' => '', 'disabled']); ?>
                    </div>
                    <div class="mt-3">
                        <label class="form-label text-base font-semibold"><?php echo e(trans('admin.code_bill')); ?></label>
                        <?php echo Form::text('code', '', ['class' => 'form-control w-full codeP', 'placeholder' => '']); ?>
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
                            <?php $products = $detail->delivery_relationships;
                            if (!empty($products) && count($products) > 0) { ?>
                            <?php foreach ($products as $key => $val) { ?>
                            <tr class="<?php echo e($val->code); ?>">
                                <td>
                                    <?php echo Form::text('product_code', $val->code, ['class' => 'form-control w-full', 'placeholder' => '', 'required']); ?>
                                </td>
                                <td>
                                    <?php echo Form::text('product_weight', $val->weight, ['class' => 'form-control w-full weight', 'data-id' =>$val->id, 'placeholder' => '', 'required']); ?>
                                </td>
                                <td>
                                    <?php echo Form::text('product_note', $val->note, ['class' => 'form-control w-full', 'data-id' =>$val->id, 'placeholder' => '']); ?>
                                </td>
                                <td>
                                    <a href="javascript:void(0)" class="js_removeColumn text-danger font-bold" data-id="<?php echo $val->id?>" data-code="<?php echo $val->code?>">Xóa</a>
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
                                <td class="text-danger font-bold" colspan="3" id="tongsocan">
                                    <?php echo e($detail->weight); ?>

                                </td>
                            </tr>
                            <tr>
                                <td class="text-right font-bold">
                                    Biểu phí
                                </td>
                                <td colspan="3" id="dongia">
                                    <?php echo e(number_format($detail->fee,'0',',','.')); ?>

                                </td>

                            </tr>
                            <tr class="">
                                <td class="text-right font-bold">
                                    Thành tiền
                                </td>
                                <td colspan="3" class="text-danger font-bold" id="tongsotien">
                                    <?php echo e(number_format($detail->price,'0',',','.')); ?>

                                </td>
                            </tr>
                            </tfoot>
                        </table>

                    </div>


                </div>
            </div>
        </form>
        <!--Logs đơn hàng-->

    </div>
    <style>
        .mt-1\.5 {
            margin-top: 0.375rem;
        }

        .-left-1\.5 {
            left: -0.375rem;
        }
    </style>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('javascript'); ?>
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
    $(document).on('change', 'select[name="customer_id"]', function() {
        var customer_id = $(this).val()
        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            url: "<?php echo route('deliveries.new.update') ?>",
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
                    /* if(data.tokenIos){
                        var formdata = new FormData();
                        formdata.append("to",data.tokenIos);
                        formdata.append("title",data.title);
                        formdata.append("body", data.body);
                        formdata.append("subtitle", "");
                        formdata.append("sound", "default");
                        var requestOptions = {
                            method: 'POST',
                            body: formdata,
                            redirect: 'follow'
                        };
                        fetch("https://exp.host/--/api/v2/push/send", requestOptions)
                            .then(response => response.text())
                    .then(result => console.log(result))
                    .catch(error => console.log('error', error));
                    }*/
                }else{
                    toastr.error(
                        "Lỗi",
                        "Error!"
                    );
                }
            },
        });
    });
    function loadHTML(data) {
        var html = '<tr class="' + data.code + '">';
        html += '<td>';
        html += '<input class="form-control w-full" name="product_code" type="text" value="' + data.code + '">';
        html += '</td>';
        html += '<td>';
        html += '<input class="form-control w-full" placeholder="" name="product_weight" data-id="' + data.id + '" type="text" value="' + data.weight + '">';
        html += '</td> ';
        html += '<td>';
        html += '<input class="form-control w-full" placeholder="" name="product_note" data-id="' + data.id + '" type="text" value="">';
        html += '</td>';
        html += '<td>';
        html += '<a href="javascript:void(0)" class="js_removeColumn text-danger font-bold" data-id="' + data.id + '" data-code="' + data.code + '">Xóa</a>';
        html += '</td> ';
        html += '</tr>';
        return html
    }
    $(document).on('keypress', '.codeP', function(e) {
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
                        url: "<?php echo route('deliveries.new.autocomplete') ?>",
                        type: "POST",
                        dataType: "JSON",
                        data: {
                            code: value,
                            id: <?php echo $detail->id?>
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


                            }
                            if(data.status == 400){
                                toastr.error(
                                    data.error,
                                    "Error!"
                                );
                                loadStatusError = 1;
                                playAudioError();
                                $('#tongsocan').html(data.detail.weightTotal)
                                $('#dongia').html(data.detail.fee)
                                $('#tongsotien').html(data.detail.price)
                                $('#list').prepend(loadHTML(data.detail));
                                _this.val('').focus();

                                return false;
                            }
                            if(data.status == 200){
                                loadStatus = 1
                                playAudio()
                                $('#tongsocan').html(data.detail.weightTotal)
                                $('#dongia').html(data.detail.fee)
                                $('#tongsotien').html(data.detail.price)
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
    $(document).on('keypress', 'input[name="product_note"]', function(e) {
        /*var _this = $(this);
        var key = e.which;
        if (key == 13) // the enter key code
        {
            e.preventDefault()

        }*/
        var id = $(this).attr('data-id');
        var note = $(this).val();
        if (note != '') {
            //ajax
            setTimeout(function() {
                $.ajax({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    url: "<?php echo route('deliveries.new.note') ?>",
                    type: "POST",
                    dataType: "JSON",
                    data: {
                        note: note,
                        id: id
                    },
                    success: function(data) {
                        // if(data.status == 200){
                        //     toastr.success(
                        //         data.message,
                        //         "Successs!"
                        //     );
                        // }else{
                        //     toastr.error(
                        //         "Cập nhập ghi chú không thành công",
                        //         "Error!"
                        //     );
                        // }
                    },
                });
            }, 10);
            //endfoeach
        }
    })
    $(document).on('keypress', 'input[name="product_weight"]', function(e) {
        // var _this = $(this);
        // var key = e.which;
        // if (key == 13) // the enter key code
        // {
        //     e.preventDefault()
        //
        // }
        var id = $(this).attr('data-id');
        var weight = $(this).val();
        if (weight != '') {
            //ajax
            setTimeout(function() {
                $.ajax({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    url: "<?php echo route('deliveries.new.weight') ?>",
                    type: "POST",
                    dataType: "JSON",
                    data: {
                        weight: weight,
                        id: id,
                        delivery_id: <?php echo $detail->id?>,
                    },
                    success: function(data) {
                        if(data.status == 200){
                            // toastr.success(
                            //     data.message,
                            //     "Successs!"
                            // );
                            $('#tongsocan').html(data.detail.weightTotal)
                            $('#dongia').html(data.detail.fee)
                            $('#tongsotien').html(data.detail.price)
                        }else{
                            // toastr.error(
                            //     "Cập nhập ghi chú không thành công",
                            //     "Error!"
                            // );
                        }
                    },
                });
            }, 10);
            //endfoeach
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
                            url: "<?php echo route('deliveries.new.delete') ?>",
                            type: "POST",
                            dataType: "JSON",
                            data: {
                                id: id,
                                delivery_id: <?php echo $detail->id?>,
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
                                    $('#dongia').html(data.detail.fee)
                                    $('#tongsotien').html(data.detail.price)
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

</script>
<div id="Print" style="float:left;margin-left:0px;display:none">
    <table style="width:100%;height:950px;border:1px solid #000;font-size:36px !important;line-height: 30px" id="tablePrint">

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

        function getinfo(id) {
            $.ajax({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                type: 'GET',
                url: "<?php echo route('deliveries.printer') ?>",
                data: {
                    id: id
                },
                contentType: "application/json;charset=utf-8",
                dataType: "json",
                success: function(result) {
                    $('#tablePrint').html(result.html)
                    setTimeout(function() {
                        var img = document.getElementById("imgbarcode");
                        img.src = result.src;
                        img.onload = function() {
                            PrintBarcode("Print");
                        }
                    }, 10);
                }
            });
        }
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('dashboard.layout.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/quyenitc/vanchuyenhangtrung.com/resources/views/delivery/backend/advanced.blade.php ENDPATH**/ ?>