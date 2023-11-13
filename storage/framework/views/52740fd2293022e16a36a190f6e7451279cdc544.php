
<?php $__env->startSection('content'); ?>
<style>
    .container input.checkboxC {
        position: relative;
        opacity: 1;
        width: 24px;
        height: 24px;

    }
</style>
<?php echo htmlBreadcrumb($seo['meta_title']); ?>


<main class="pb-[250px] pt-8">
    <div class="container px-4 mx-auto">
        <div class="mt-4 flex flex-col md:flex-row items-start lg:space-x-8">
            <?php echo $__env->make('customer/frontend/auth/common/sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <div class="flex-1 overflow-x-hidden shadowC rounded-xl w-full md:w-auto order-1 md:order-2">
                <div class="p-6 bg-white space-y-3 pb-[150px]">
                    <div class="flex md:items-center flex-col md:flex-row justify-between">
                        <h1 class="text-black font-bold text-xl">Hoàn tiền #<?php echo e($detail->code); ?></h1>
                        <div class="flex flex-col justify-end">
                            <span class="text-white rounded-md text-center px-2" style="background-color: <?php echo e(config('cart')['class'][$detail->status]); ?>;"> <?php echo e(config('cart')['status'][$detail->status]); ?></span>
                            <i><?php echo e($detail->created_at); ?></i>
                        </div>
                    </div>
                    <div class="space-y-5">
                        <?php
                        $links = json_decode($detail->links, true);
                        $images = json_decode($detail->images, true);
                        ?>
                        <?php if(!empty($links)): ?>
                        <div class="space-y-3 p-5 border rounded-md shadow">
                            <div class="space-y-3" id="formData">
                                <div>
                                    <label for="checkbox-all-quyen" class="flex border-b pb-2 items-center font-bold relative cursor-pointer">
                                        <input type="checkbox" id="checkbox-all-quyen" class="w-4 h-4 checkboxC">
                                        <span class="outline-none flex-1 pl-2">
                                            Chọn tất cả
                                        </span>
                                    </label>
                                </div>
                                <?php $__currentLoopData = $links; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if(!empty($link)): ?>
                                <div class="space-y-3 ">
                                    <label for="checkbox-<?php echo e($key); ?>" class="flex border-b pb-2 cursor-pointer items-center relative overflow-hidden">
                                        <input type="checkbox" name="links[]" id="checkbox-<?php echo e($key); ?>" class="w-4 h-4 checkbox-item-quyen checkboxC" value="<?php echo e($link); ?>">
                                        <span class="flex-1 outline-none focus:outline-none hover:outline-none underline pl-2">
                                            <?php echo e($link); ?>

                                        </span>
                                    </label>
                                </div>
                                <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                        <?php endif; ?>
                        <div class="grid grid-cols-1 space-y-3">
                            <label for="checkbox-all-quyen-2" class="flex border-b pb-2 cursor-pointer items-center font-bold relative">
                                <input type="checkbox" id="checkbox-all-quyen-2" class="w-4 h-4 checkboxC">
                                <span class="outline-none flex-1 pl-2">
                                    Chọn tất cả hình ảnh
                                </span>
                            </label>
                            <?php if(!empty($images)): ?>
                            <div class="grid grid-cols-1 flex-wrap gap-3">
                                <?php $__currentLoopData = $images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if(!empty($img)): ?>
                                <label for="checkbox2-<?php echo e($key); ?>" class="flex border-b pb-2 cursor-pointer items-center relative">
                                    <input type="checkbox" name="images[]" id="checkbox2-<?php echo e($key); ?>" class="w-4 h-4 checkbox-item-quyen-2 checkboxC" value="<?php echo e($img); ?>">
                                    <div>
                                        <img src="<?php echo e(asset($img)); ?>" class="h-[70px] object-cover pl-2 flex-1" />
                                    </div>
                                </label>
                                <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="grid grid-cols-5 pb-2 space-x-3 items-center">
                            <label class="col-span-2 text-red-600 font-bold">Ghi chú</label>
                            <textarea class="col-span-3 outline-none focus:outline-none hover:outline-none border rounded-md px-4" rows="4" cols="50" name="message_return"></textarea>
                        </div>
                        <div class="grid grid-cols-5 pb-2 space-x-3 items-center">
                            <label class="col-span-2 text-red-600 font-bold">Tổng tiền đã thanh toán (VNĐ)</label>
                            <input class="col-span-3 outline-none focus:outline-none hover:outline-none border rounded-md h-10 px-4 int" name="price_return" value="<?php echo e(number_format($detail->total_price_vnd_final,'0',',','.')); ?>">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="fixed bottom-0 lg:bottom-0 left-0 w-full z-[999]">
        <div class="container mx-auto md:px-4">
            <div class="flex flex-col lg:flex-row items-start lg:space-x-8">
                <div class="hidden lg:block w-full lg:w-[376px] order-2 md:order-1 mt-10 md:mt-0">
                </div>
                <div class="flex-1 flex justify-between items-center overflow-x-hidden w-full md:w-full order-1 md:order-2 py-5 text-white bg_gradient p-4 md:rounded-lg">
                    <div class="w-1/2 flex flex-col space-y-1">
                        <div>Số tiền có thể hoàn(VNĐ): <span class="font-bold total_price_vnd"><?php echo e(number_format($detail->total_price_vnd_final,'0',',','.')); ?></span></div>
                    </div>
                    <div class="w-1/2 space-x-1 justify-end flex flex-wrap">
                        <a href="javascript:void(0)" style="background-color: <?php echo e(config('cart')['class']['returns']); ?>;" class="js_handleSubmit text-white outline-none focus:outline-none hover:outline-none font-medium rounded-lg text-sm px-4 lg:px-5 py-2 lg:py-2.5">Xác nhận</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('javascript'); ?>
<script>
    $(document).on("change keyup blur", ".int", function() {
        let data = $(this).val();
        if (data == "") {
            $(this).val("0");
            return false;
        }
        data = data.replace(/\./gi, "");
        $(this).val(addCommas(data));
        /*khi đánh chữ thì về 0 */
        data = data.replace(/\./gi, "");
        if (isNaN(data)) {
            $(this).val("0");
            return false;
        }
    });

    function addCommas(nStr) {
        nStr = String(nStr);
        nStr = nStr.replace(/\./gi, "");
        let str = "";
        for (i = nStr.length; i > 0; i -= 3) {
            a = i - 3 < 0 ? 0 : i - 3;
            str = nStr.slice(a, i) + "." + str;
        }
        str = str.slice(0, str.length - 1);
        return str;
    }

    function replace(Str = "") {
        if (Str == "") {
            return "";
        } else {
            Str = Str.replace(/\./gi, "");
            return Str;
        }
    }

    $(".menu_item_auth:eq(2)").addClass('active');
</script>
<script>
    $(document).on('change keyup blur', "input[name='price_return']", function(e) {
        var price_return = $(this).val()
        var price_return_new = replace(price_return);
        if (price_return_new > <?php echo $detail->total_price_vnd_final ?>) {
            $("input[name='price_return']").val(<?php echo $detail->total_price_vnd_final ?>)
        }
    })
    $(document).on('click', '.js_handleSubmit', function(e) {
        e.preventDefault();
        var price_return = $('input[name="price_return"]').val()
        let _this = $(this);
        let value = _this.val();
        let links_checked = []; /*Lấy id bản ghi links*/
        let images_checked = []; /*Lấy id bản ghi links*/
        $(".checkbox-item-quyen:checked").each(function() {
            links_checked.push($(this).val());
        });
        $(".checkbox-item-quyen-2:checked").each(function() {
            images_checked.push($(this).val());
        });
        if (links_checked.length <= 0) {
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
            links: links_checked,
            images: images_checked,
            price_return: replace(price_return),
            id: <?php echo $detail->id ?>,
            message_return: $('textarea[name="message_return"]').val(),
        };
        swal({
                title: "Hãy chắc chắn rằng bạn muốn thực hiện thao tác này?",
                text: "",
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
                        url: "<?php echo route('ordersF.store_return') ?>",
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
                                swal({
                                        title: "Gửi yêu cầu hoàn tiền thành công!",
                                        text: "",
                                        type: "success",
                                    },
                                    function() {
                                        window.location.replace("<?php echo route('ordersF.index') ?>");
                                    }
                                );
                            } else {
                                swal({
                                    title: "Có vấn đề xảy ra",
                                    text: "Vui lòng thử lại",
                                    type: "error",
                                });
                            }
                        },
                    });
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
<script>
    $(document).on("click", "#checkbox-all-quyen", function() {
        let _this = $(this);
        checkall(_this);
    });

    $(document).on("click", ".checkbox-item-quyen", function() {
        let _this = $(this);
        check_item_all(_this);
    });

    function checkall(_this) {
        let table = _this.parents("main");
        if ($("#checkbox-all-quyen").length) {
            if (table.find("#checkbox-all-quyen").prop("checked")) {
                table.find(".checkbox-item-quyen").prop("checked", true);
            } else {
                table.find(".checkbox-item-quyen").prop("checked", false);
            }
        }
    }

    function check_item_all(_this) {
        let table = _this.parents("main");
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
</script>
<script>
    $(document).on("click", "#checkbox-all-quyen-2", function() {
        let _this = $(this);
        checkall2(_this);
    });

    $(document).on("click", ".checkbox-item-quyen-2", function() {
        let _this = $(this);
        check_item_all_2(_this);
    });

    function checkall2(_this) {
        let table = _this.parents("main");
        if ($("#checkbox-all-quyen-2").length) {
            if (table.find("#checkbox-all-quyen-2").prop("checked")) {
                table.find(".checkbox-item-quyen-2").prop("checked", true);
            } else {
                table.find(".checkbox-item-quyen-2").prop("checked", false);
            }
        }
    }

    function check_item_all_2(_this) {
        let table = _this.parents("main");
        if (table.find(".checkbox-item-quyen-2").length) {
            if (
                table.find(".checkbox-item-quyen-2:checked").length ==
                table.find(".checkbox-item-quyen-2").length
            ) {
                table.find("#checkbox-all-quyen-2").prop("checked", true);
            } else {
                table.find("#checkbox-all-quyen-2").prop("checked", false);
            }
        }
    }
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('homepage.layout.home', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\vanchuyenhangtrung.local\resources\views/customer/frontend/order/return.blade.php ENDPATH**/ ?>