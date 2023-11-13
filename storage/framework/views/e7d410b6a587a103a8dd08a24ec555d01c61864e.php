<?php $__env->startSection('title'); ?>

<title> Danh sách bao 包清单</title>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb'); ?>

<?php

$array = array(

    [

        "title" => " Danh sách bao 包清单",

        "src" => 'javascript:void(0)',

    ]

);

echo breadcrumb_backend($array);

?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="content hidePrint">

    <div class="flex justify-between items-center mt-5">

        <h1 class=" text-lg font-medium">

            Danh sách bao 包清单

        </h1>

        <div class="flex">

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('packagings_create')): ?>

            <a href="<?php echo e(route('packagings.create')); ?>" class="btn btn-primary shadow-md mr-2"><?php echo e(trans('admin.create')); ?></a>

            <?php endif; ?>

            <a href="<?php echo e(route('packagings.export',['packaging_v_n_s' => request()->get('packaging_v_n_s'),'customer_id' => request()->get('customer_id'),'date_start'=>request()->get('date_start'),'date_end'=>request()->get('date_end'),'keyword'=>request()->get('keyword'),'packaging_v_n_s'=>request()->get('packaging_v_n_s')])); ?>" class="btn btn-success mr-2 shadow-md text-white btn-excel">Export excel</a>

            <a href="javascript:void(0)" onclick="PrintElem()" class="btn btn-danger hidePrint">In hàng loạt</a>

        </div>

    </div>



    <div class="grid grid-cols-12 gap-6 mt-5">

        <div class="col-span-12 mt-2">

            <form action="" class="grid grid-cols-12 gap-2" id="search" style="margin-bottom: 0px;">

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('packagings_destroy')): ?>

                <div class="col-span-1">

                    <select class="form-control ajax-delete-all-packagings  h-10" data-title="Lưu ý: Khi bạn xóa danh mục nội dung tĩnh, toàn bộ nội dung tĩnh trong nhóm này sẽ bị xóa. Hãy chắc chắn rằng bạn muốn thực hiện chức năng này!" data-module="<?php echo e($module); ?>">

                        <option>Hành động</option>

                        <option value="">Xóa</option>

                    </select>

                </div>

                <?php endif; ?>

                <div class="col-span-2">

                    <?php echo Form::select('customer_id', $customers, request()->get('customer_id'), ['class' => 'form-control tom-select tom-select-custom tomselected', 'data-placeholder' => "Select your favorite actors"]); ?>

                </div>

                <div class="col-span-2">

                    <?php echo Form::text('date_start', request()->get('date_start'), ['class' => 'form-control h-10',  'autocomplete' => 'off', 'placeholder' => 'Ngày bắt đầu']); ?>

                </div>

                <div class="col-span-2">

                    <?php echo Form::text('date_end', request()->get('date_end'), ['class' => 'form-control h-10',  'autocomplete' => 'off', 'placeholder' => 'Ngày kết thúc']); ?>

                </div>

                <div class="col-span-2">

                    <?php echo Form::select('packaging_v_n_s', ['1' => 'Đã về VN', '2' => 'Nhập kho TQ', '3' => 'Xếp xe TQ'], request()->get('packaging_v_n_s'), ['class' => 'form-control h-10',  'autocomplete' => 'off', 'placeholder' => 'Trạng thái']); ?>

                </div>

                <div class="col-span-4">

                    <input type="search" name="keyword" class="keyword form-control filter w-full h-10" placeholder="Nhập từ khóa tìm kiếm ..." autocomplete="off" value="<?php echo request()->get('keyword') ?>">

                </div>

                <div class="col-span-2 flex items-center space-x-2 justify-end">

                    <button class="btn btn-primary btn-sm">

                        <i data-lucide="search"></i>

                    </button>

                    <a href="<?php echo e(route('packagings.export_packagings',['packaging_v_n_s' => request()->get('packaging_v_n_s'),'customer_id' => request()->get('customer_id'),'date_start'=>request()->get('date_start'),'date_end'=>request()->get('date_end'),'keyword'=>request()->get('keyword')])); ?>" class="btn shadow-md text-white btn-excel-index" style="background-color: #5cb85c;">Export excel</a>



                </div>

            </form>



        </div>

        <!-- BEGIN: Data List -->

        <div class=" col-span-12 lg:col-span-12" style="overflow-x:auto;">

            <table class="table table-report -mt-2">

                <thead class="table-dark">

                    <tr>

                        <td colspan="3" class="text-center font-bold" style="border-radius: 0px;color: #000">Tổng dòng : <?php echo e(!empty($count)?$count : 0); ?> </td>

                        <td class=" text-white" style="background-color: red">Tổng cân 总重量</td>

                        <td class=" text-white font-bold" style="background-color: red;border-radius: 0px;"><?php echo e(number_format((float)$total_weight, 2, '.', '')); ?></td>

                        <td class=" text-black" style="background-color: yellow;color:black">Tổng số cân thực tế 包袋重量</td>

                        <td class=" text-black font-bold" style="background-color: yellow;border-radius: 0px;color:black"><?php echo e(number_format((float)$total_value_weight, 2, '.', '')); ?></td>

                    </tr>

                    <tr>

                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('packagings_destroy')): ?>

                        <th style="width:40px;">

                            <input type="checkbox" id="checkbox-all-quyen">

                        </th>

                        <?php endif; ?>

                        <th class="text-center">STT</th>

                        <th class="whitespace-nowrap">NGÀY 日期 </th>

                        <th class="whitespace-nowrap">MÃ BAO 包号 </th>

                        <th class="whitespace-nowrap">MÃ VẬN ĐƠN 运单号 </th>

                        <th class="whitespace-nowrap">Số lượng kiện hàng 数量件</th>

                        <th class="whitespace-nowrap">Cân thực tế 包袋重量</th>

                        <th class="whitespace-nowrap">CÂN NẶNG(KG) 重量 </th>

                        <th class="whitespace-nowrap">MÃ KHÁCH 客户码 </th>

                        <th class="whitespace-nowrap">TÊN KHÁCH HÀNG名字</th>

                        <th class="whitespace-nowrap">Trạng thái</th>

                        <th class="whitespace-nowrap">Người tạo</th>

                        <th class="whitespace-nowrap text-center">#</th>

                    </tr>

                </thead>

                <tbody>

                    <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                    <tr class="odd " id="post-<?php echo $v->id; ?>">



                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('packagings_destroy')): ?>

                        <td>

                            <input type="checkbox" name="checkbox[]" value="<?php echo $v->id; ?>" class="checkbox-item-quyen">

                        </td>

                        <?php endif; ?>

                        <td>

                            <?php echo e($data->firstItem()+$loop->index); ?>


                        </td>

                        <td><?php echo e($v->created_at); ?></td>

                        <td>

                            <a href="<?php echo e(route('packagings.show',['id' => $v->id])); ?>" class="text-danger font-bold"><?php echo e($v->code); ?></a>

                        </td>

                        <td>

                            <a href="<?php echo e(route('packagings.show',['id' => $v->id])); ?>" class="text-primary font-bold" style="text-decoration: underline;">Mã vận đơn</a>

                        </td>

                        <td>

                            <?php echo e(count($v->packaging_relationships)); ?>


                        </td>



                        <td>

                            <?php echo e($v->value_weight); ?>


                        </td>

                        <td>

                            <?php echo e($v->total_weight); ?>


                        </td>

                        <td>

                            <?php echo e(!empty($v->customer) ?$v->customer->code:''); ?>


                        </td>

                        <td>

                            <?php echo e(!empty($v->customer) ?$v->customer->name:''); ?>


                        </td>

                        <td>

                            <?php if(!empty($v->packaging_v_n_s)): ?>
                            <span class="btn btn-success text-white btn-sm">Đã về VN</span>
                            <?php elseif(!empty($v->packaging_trucks)): ?>
                                <span class="btn btn-warning text-white btn-sm">Xếp xe TQ</span>
                            <?php else: ?>
                            <span class="btn btn-primary btn-sm p-2">Nhập kho TQ</span>
                            <?php endif; ?>

                        </td>

                        <td>

                            <?php echo e(!empty($v->user) ?$v->user->name:''); ?>


                        </td>

                        <td class="">

                            <div style="display: none;">

                                <textarea class="textareacopy-<?php echo e($v->id); ?>"><?php if(!empty($v->packaging_relationships) && count($v->packaging_relationships) > 0): ?><?php $__currentLoopData = $v->packaging_relationships; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php echo e($item->code_vn); ?>&#13;&#10;<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> <?php endif; ?></textarea>

                            </div>

                            <div class="flex justify-center items-center">

                                <a class="flex items-center mr-3" href="javascript:void(0)" onclick="getinfo(<?php echo e($v->id); ?>)">

                                    <i data-lucide="printer" class="w-8 h-8 mr-1"></i> Print

                                </a>

                                <a class="flex items-center mr-3" href="javascript:void(0)" onclick="copyToClipboard('.textareacopy-<?php echo e($v->id); ?>')">

                                    <i data-lucide="copy" class="w-4 h-4 mr-1"></i> Copy

                                </a>

                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('packagings_edit')): ?>

                                <a class="flex items-center mr-3" href="<?php echo e(route('packagings.edit',['id'=>$v->id])); ?>">

                                    <i data-lucide="check-square" class="w-4 h-4 mr-1"></i>

                                    Edit

                                </a>

                                <?php endif; ?>

                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('packagings_destroy')): ?>

                                <a class="flex items-center text-danger ajax-delete-packagings" href="javascript:;" data-id="<?php echo $v->id ?>" data-module="<?php echo $module ?>" data-child="0" data-title="Lưu ý: Khi bạn xóa thương hiệu, thương hiệu sẽ bị xóa vĩnh viễn. Hãy chắc chắn rằng bạn muốn thực hiện hành động này!">

                                    <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i> Delete

                                </a>

                                <?php endif; ?>

                            </div>

                        </td>

                    </tr>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                </tbody>

            </table>

        </div>

        <!-- END: Data List -->

        <!-- BEGIN: Pagination -->

        <div class=" col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center justify-center">

            <?php echo e($data->links()); ?>


        </div>

        <!-- END: Pagination -->

    </div>

    <div id="Print" style="float:left;margin-left:0px;display:none">

        <table style="width:420px;height:300px;border:1px solid #000;font-size:40px !important;text-align:center">

            <tbody>

                <tr>

                    <td style="border:1px solid #000">

                        <div id="inputdatat">

                            <img style="margin: 0px auto;" src="" id="imgbarcode" width="300" height="80">

                        </div>

                        <div style="text-align: center;font-weight: bold;margin: 20px 0px;" class="codePrint">



                        </div>

                        <p style="text-align:center;font-size: 30px;margin-bottom: 20px" class="infoPrint"></p>

                        <p style="text-align:center;font-size: 30px" class=""> Số kiện: <span class="sokienPrint"></span></p>

                    </td>

                </tr>



            </tbody>

        </table>

    </div>

</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('javascript'); ?>

<script src="<?php echo e(asset('library/toastr/toastr.min.js')); ?>"></script>

<link href="<?php echo e(asset('library/toastr/toastr.min.css')); ?>" rel="stylesheet">

<script>
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



    function getinfo(id) {

        $.ajax({

            headers: {

                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(

                    "content"

                ),

            },

            type: 'GET',

            url: "<?php echo route('packagings.printer') ?>",

            data: {

                id: id

            },

            contentType: "application/json;charset=utf-8",

            dataType: "json",

            success: function(result) {

                $('.codePrint').html(result.detail.customer.code + result.detail.code)

                $('.infoPrint').html(result.detail.customer.code + '-' + result.detail.customer.name + '/ ' + result.detail.value_weight + ' kg')

                $('.sokienPrint').html(result.count)

                var img = document.getElementById("imgbarcode");

                img.src = result.code;

                img.onload = function() {

                    PrintBarcode("Print");

                }

            }

        });

    }
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.js" integrity="sha512-+UiyfI4KyV1uypmEqz9cOIJNwye+u+S58/hSwKEAeUMViTTqM9/L4lqu8UxJzhmzGpms8PzFJDzEqXL9niHyjA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.css" integrity="sha512-bYPO5jmStZ9WI2602V2zaivdAnbAhtfzmxnEGh9RwtlI00I9s8ulGe4oBa5XxiC6tCITJH/QG70jswBhbLkxPw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<script type="text/javascript">
    $(function() {

        $('input[name="date_start"]').datetimepicker({

            format: 'Y-m-d',

        });

        $('input[name="date_end"]').datetimepicker({

            format: 'Y-m-d',

        });

    });
</script>

<!-- /*START: Xóa 1 bản ghi*/ -->

<script>
    $(document).on("click", ".ajax-delete-packagings", function(e) {

        e.preventDefault();

        let _this = $(this);

        let param = {

            title: _this.attr("data-title"),

            name: _this.attr("data-name"),

            module: _this.attr("data-module"),

            id: _this.attr("data-id"),

            router: _this.attr("data-router"),

            child: _this.attr("data-child"),

        };

        let parent =

            _this.attr("data-parent"); /*Đây là khối mà sẽ ẩn sau khi xóa */

        swal({

                title: "Hãy chắc chắn rằng bạn muốn thực hiện thao tác này?",

                text: param.title,

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

                    let formURL = "<?php echo route('packagings.destroy') ?>";

                    $.ajax({

                        headers: {

                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(

                                "content"

                            ),

                        },

                        url: formURL,

                        type: "POST",

                        dataType: "JSON",

                        data: {

                            module: param.module,

                            id: param.id,

                            router: param.router,

                            child: param.child,

                        },

                        success: function(data) {

                            if (data.code === 200) {

                                if (typeof parent != "undefined") {

                                    _this

                                        .parents("." + parent + "")

                                        .hide()

                                        .remove();

                                } else {

                                    _this

                                        .parent()

                                        .parent()

                                        .parent()

                                        .hide()

                                        .remove();

                                }

                                if (param.child == 1) {

                                    $("#listData" + param.id).remove();

                                }

                                swal({

                                        title: "Xóa thành công!",

                                        text: "Hạng mục đã được xóa khỏi danh sách.",

                                        type: "success",

                                    },

                                    function() {

                                        location.reload();

                                    }

                                );

                            } else {

                                swal({

                                        title: "Có vấn đề xảy ra",

                                        text: "Vui lòng thử lại",

                                        type: "error",

                                    },

                                    function() {

                                        location.reload();

                                    }

                                );

                            }

                        },

                        error: function(jqXhr, json, errorThrown) {

                            var errors = jqXhr.responseJSON;

                            var errorsHtml = "";

                            $.each(errors["errors"], function(index, value) {

                                errorsHtml += value + "/ ";

                            });

                            $("#myModal .alert").html(errorsHtml).show();

                        },

                    });

                } else {

                    swal({

                            title: "Hủy bỏ",

                            text: "Thao tác bị hủy bỏ",

                            type: "error",

                        },

                        function() {

                            location.reload();

                        }

                    );

                }

            }

        );

    });

    /*END: Xóa 1 bản ghi*/



    /*START: XÓA tất cả bản ghi */

    $(document).on("change", ".ajax-delete-all-packagings", function() {

        let _this = $(this);

        let id_checked = []; /*Lấy id bản ghi */

        $(".checkbox-item:checked").each(function() {

            id_checked.push($(this).val());

        });

        if (id_checked.length <= 0) {

            swal({

                    title: "Có vấn đề xảy ra",

                    text: "Bạn phải chọn ít nhất 1 bản ghi để thực hiện chức năng này",

                    type: "error",

                },

                function() {

                    location.reload();

                }

            );

            return false;

        }

        let param = {

            title: _this.attr("data-title"),

            module: _this.attr("data-module"),

            router: _this.attr("data-router"),

            child: _this.attr("data-child"),

            list: id_checked,

        };

        let parent =

            _this.attr("data-parent"); /*Đây là khối mà sẽ ẩn sau khi xóa */

        swal({

                title: "Hãy chắc chắn rằng bạn muốn thực hiện thao tác này?",

                text: param.title,

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

                    $.ajax({

                        type: "POST",

                        url: "<?php echo route('packagings.destroy_all') ?>",

                        headers: {

                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(

                                "content"

                            ),

                        },

                        data: {

                            param: param

                        },

                        success: function(data) {

                            if (data.code == 200) {

                                setTimeout(function() {

                                    location.reload();

                                }, 1500);

                                for (let i = 0; i < id_checked.length; i++) {

                                    $("#post-" + id_checked[i])

                                        .hide()

                                        .remove();

                                }

                                swal({

                                        title: "Xóa thành công!",

                                        text: "Các bản ghi đã được xóa khỏi danh sách.",

                                        type: "success",

                                    },

                                    function() {

                                        location.reload();

                                    }

                                );

                            } else {

                                swal({

                                        title: "Có vấn đề xảy ra",

                                        text: "Vui lòng thử lại",

                                        type: "error",

                                    },

                                    function() {

                                        location.reload();

                                    }

                                );

                            }

                        },

                    });

                } else {

                    swal({

                            title: "Hủy bỏ",

                            text: "Thao tác bị hủy bỏ",

                            type: "error",

                        },

                        function() {

                            location.reload();

                        }

                    );

                }

            }

        );

    });

    /*END: XÓA tất cả bản ghi */

    $(document).on("click", "#checkbox-all-quyen", function() {

        let _this = $(this);

        checkall(_this);

        change_background();

        loadIDS()

    });



    $(document).on("click", ".checkbox-item-quyen", function() {

        let _this = $(this);

        change_background(_this);

        check_item_all(_this);

        loadIDS()

    });



    function checkall(_this) {

        let table = _this.parents("table");

        if ($("#checkbox-all-quyen").length) {

            if (table.find("#checkbox-all-quyen").prop("checked")) {

                table.find(".checkbox-item-quyen").prop("checked", true);

            } else {

                table.find(".checkbox-item-quyen").prop("checked", false);

            }

        }

    }



    function check_item_all(_this) {

        let table = _this.parents("table");

        if (table.find(".checkbox-item-quyen").length) {

            if (

                table.find(".checkbox-item-quyen:checked").length ==

                table.find(".checkbox-item-quyen").length

            ) {

                table.find("#checkbox-all-quyen").prop("checked", true);

            } else {

                table.find("#checkbox-all-quyen").prop("checked", false);

            }

        }

    }



    function change_background() {

        $(".checkbox-item-quyen").each(function() {

            if ($(this).is(":checked")) {

                $(this).parents("tr").addClass("bg-active");

            } else {

                $(this).parents("tr").removeClass("bg-active");

            }

        });

    }



    function loadIDS() {

        var ids = '';

        $(".checkbox-item-quyen").each(function() {

            if ($(this).is(":checked")) {

                ids += $(this).val() + ','

            }

        });



        $('.btn-excel').attr('href', '<?php echo route("packagings.export") ?>?ids=' + ids)

        $('.btn-excel-index').attr('href', '<?php echo route("packagings.export_packagings") ?>?ids=' + ids)



    }
</script>

<!-- in nhiều đơn hàng -->

<style>
    @media  print {

        @page  {

            margin: 0;

        }



        .linePrint {

            page-break-after: always;

            display: block;

        }



        .main>div.flex>* {

            display: none;

        }

    }
</style>

<script>
    function PrintElem() {

        var ids = '';

        $(".checkbox-item-quyen").each(function() {

            if ($(this).is(":checked")) {

                ids += $(this).val() + ','

            }

        });

        if (ids.length <= 0) {

            swal({

                    title: "Có vấn đề xảy ra",

                    text: "Bạn phải chọn ít nhất 1 bản ghi để thực hiện chức năng này",

                    type: "error",

                },

                function() {

                    location.reload();

                }

            );

            return false;

        }

        $.ajax({

            headers: {

                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(

                    "content"

                ),

            },

            type: 'GET',

            url: "<?php echo route('packagings.printer_all') ?>",

            data: {

                ids: ids

            },

            contentType: "application/json;charset=utf-8",

            dataType: "json",

            success: function(result) {

                $('.linePrint').remove();

                $('body').append(result.html)

                var img = document.getElementById("imgbarcodeall");

                img.onload = function() {

                    window.print();

                }

            }

        });

    }
</script>



<script>
    function copyToClipboard(element) {

        var $temp = $("<textarea>");

        $("body").append($temp);

        $temp.val($(element).val()).select();

        document.execCommand("copy");

        $temp.remove();

        toastr.success('Đã sao chép vào bộ nhớ tạm', 'Thông báo!')

    }
</script>

<?php $__env->stopPush(); ?>

<?php echo $__env->make('dashboard.layout.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/quyenitc/api/resources/views/packaging/backend/index.blade.php ENDPATH**/ ?>