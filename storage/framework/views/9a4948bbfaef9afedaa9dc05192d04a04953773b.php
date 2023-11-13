
<?php $__env->startSection('content'); ?>
<?php echo htmlBreadcrumb($seo['meta_title']); ?>

<form role="form" action="<?php echo e(route('cartF.store')); ?>" method="post" enctype="multipart/form-data">
    <main class="pb-[250px] pt-8">
        <div class="container px-4 mx-auto">
            <div class="mt-4 flex flex-col md:flex-row items-start lg:space-x-4">
                <?php echo $__env->make('customer/frontend/auth/common/sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <div class="flex-1 overflow-x-hidden shadowC rounded-xl w-full md:w-auto order-1 md:order-2">
                    <div class="p-6 bg-white space-y-3 pb-[150px]">
                        <div class="flex items-center justify-between">
                            <h1 class="text-black font-bold text-xl">Mua hàng</h1>
                        </div>
                        <div class="space-y-5">
                            <?php if($errors->any()): ?>
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-2 print-error-msg">
                                <strong class="font-bold">ERROR!</strong>
                                <span class="block sm:inline"> <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php echo e($error); ?>

                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></span>
                            </div>
                            <?php endif; ?>
                            <?php echo csrf_field(); ?>
                            <div class="space-y-3">
                                <div class="grid grid-cols-5 border-b pb-2 space-x-3">
                                    <label class="font-semibold">Tên sản phẩm</label>
                                    <input name="title" class="col-span-4 outline-none focus:outline-none hover:outline-none" placeholder="Vui lòng nhập tên">
                                </div>
                                <div class="grid grid-cols-5 border-b pb-2 space-x-3 hidden">
                                    <label class="font-semibold">Kích thước tiêu chuẩn</label>
                                    <input name="weight" class="col-span-4 outline-none focus:outline-none hover:outline-none" placeholder="Vui lòng nhập thông số kích thước">
                                </div>
                                <div class="grid grid-cols-5 border-b pb-2 space-x-3">
                                    <label class="font-semibold">Số lượng</label>
                                    <input type="number" required name="quantity" class="col-span-4 outline-none focus:outline-none hover:outline-none" placeholder="Vui lòng nhập số lượng">
                                </div>
                                <div class="grid grid-cols-5 border-b pb-2 space-x-3">
                                    <label class="font-semibold">Đơn giá (¥)</label>
                                    <input type="text" required name="price" class="col-span-4 outline-none focus:outline-none hover:outline-none" placeholder="Vui lòng nhập đơn giá">
                                </div>
                                <div class="grid grid-cols-5 border-b pb-2 space-x-3">
                                    <label class="font-semibold">Tổng tiền hàng (¥)</label>
                                    <input type="text" name="total" class="col-span-4 outline-none focus:outline-none hover:outline-none" placeholder="">
                                </div>
                                <div class="grid grid-cols-5 border-b pb-2 space-x-3">
                                    <label class="font-semibold">Ghi chú</label>
                                    <textarea name="note" class="col-span-4 outline-none focus:outline-none hover:outline-none" placeholder="Ghi chú"></textarea>
                                </div>
                            </div>
                            <div class="space-y-3 p-5 border rounded-md shadow">
                                <div class="space-y-3" id="formData">
                                    <div class="space-y-3 ">
                                        <div class="grid grid-cols-5 border-b pb-2 space-x-3">
                                            <label class="font-semibold">Liên kết sản phẩm</label>
                                            <input name="links[]" class="col-span-4 outline-none focus:outline-none hover:outline-none" placeholder="Vui lòng nhập liên kết">
                                        </div>
                                    </div>
                                </div>
                                <div class="flex justify-end">
                                    <button id="btnAdd" class="text-white bg_gradient font-medium rounded-lg text-sm px-4 lg:px-5 py-2 lg:py-2.5 dark:bg-primary-600 outline-none focus:outline-none hover:outline-none" type="button">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 space-y-3">
                                <label class="font-semibold">Hình ảnh</label>
                                <div class="dropzone dz-clickable" id="myDropzone">
                                    <div class="dz-message" data-dz-message="">
                                        <div class="text-lg font-medium">Click để upload hình ảnh.</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <div class="fixed bottom-0 lg:bottom-0 left-0 w-full z-[999]">
        <div class="container mx-auto md:px-4">
            <div class="flex flex-col md:flex-row items-start lg:space-x-4">
                <div class="hidden lg:block w-full md:w-full lg:w-[376px] order-2 md:order-1 mt-10 md:mt-0">
                </div>
                <div class="flex-1 flex justify-between items-center overflow-x-hidden w-full order-1 md:order-2 py-5 text-white bg_gradient p-4 md:rounded-lg">
                    <div class="flex flex-col">
                        <div>Giá tệ: <span class="font-bold"><?php echo e(number_format($cny->content,'0',',','.')); ?></span> VNĐ</div>
                        <div>Tổng phí: <span class="font-bold total_price_vnd">0</span> VNĐ</div>
                    </div>
                    <div>
                        <button type="submit" class="text-white bg-red-600 outline-none focus:outline-none hover:outline-none font-medium rounded-lg text-sm px-4 lg:px-5 py-2 lg:py-2.5">
                            Thêm giỏ hàng
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('javascript'); ?>
<script>
    function numberWithCommas(nStr) {
        const formattedNumber = nStr.toLocaleString("de-DE");
        return formattedNumber;
    }

    function loadPrice() {
        var quantity = $('input[name="quantity"]').val();
        var price = $('input[name="price"]').val();
        var total = 0;
        total = parseInt(quantity) * parseFloat(price)
        $('input[name="total"]').val(total)
        $('.total_price_cny').html(total)
        $('.total_price_vnd').html(numberWithCommas(total * <?php echo !empty($cny) ? $cny->content : 0 ?>))
    }
    $(document).on('keyup', 'input[name="quantity"]', function(e) {
        loadPrice();
    })
    $(document).on('keyup', 'input[name="price"]', function(e) {
        loadPrice();
    })
</script>
<script type="text/javascript">
    $('.menu_item_auth:eq(1)').addClass('active')
    $(document).on('click', '#btnAdd', function(e) {
        var html = '';
        html += '<div class="space-y-3">';
        html += '<div class="grid grid-cols-5 border-b pb-2 space-x-3">';
        html += '<label class="font-semibold">Liên kết sản phẩm</label>';
        html += '<input name="links[]" class="col-span-4 outline-none focus:outline-none hover:outline-none" placeholder="Vui lòng nhập liên kết">';
        html += '</div>';
        html += '</div>';
        $('#formData').append(html)
    })
</script>
<script src="<?php echo e(asset('library/dropzone/dropzone.min.js')); ?>"></script>
<link rel="stylesheet" href="<?php echo e(asset('library/dropzone/dropzone.min.css')); ?>" type="text/css" />
<!-- sortable -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js" type="text/javascript"></script>
<script>
    $(function() {
        $("#myDropzone").sortable({
            items: '.dz-preview',
            cursor: 'move',
            opacity: 0.5,
            containment: '#myDropzone',
            distance: 20,
            tolerance: 'pointer'
        });
    })
</script>
<!-- end sortable -->
<?php
$image_json = old('images');
?>
<script>
    Dropzone.autoDiscover = false;
    var acceptedFileTypes = ".jpeg,.jpg,.png,.gif";
    var fileList = new Array;
    var i = 0;
    var callForDzReset = false;
    $("#myDropzone").dropzone({
        url: "<?php echo e(route('dropzone_upload_frontend')); ?>",
        addRemoveLinks: true,
        maxFiles: 100,
        acceptedFiles: 'image/*',
        maxFilesize: 5,
        renameFile: function(file) {
            var dt = new Date();
            var time = dt.getTime();
            return time + '-' + file.name;
        },
        headers: {
            'x-csrf-token': $('meta[name="csrf-token"]').attr('content'),
        },
        init: function() {
            myDropzone = this;
            /*update load image*/
            <?php if (!empty($image_json)) { ?>
                <?php foreach ($image_json as $key => $item) {
                    if (!empty($item != 'undefined')) { ?>
                        var mockFile = {
                            name: '<?php echo $item ?>',
                            size: <?php echo File::exists(base_path($item)) ? filesize(base_path($item)) : 0; ?>
                        };
                        myDropzone.emit("addedfile", mockFile);
                        myDropzone.emit("thumbnail", mockFile, '<?php echo url($item) ?>');
                        myDropzone.emit("complete", mockFile);
                        $(".dz-image").eq(<?php echo $key ?>).append('<input type="hidden" name="images[]" value="<?php echo $item ?>">')
                <?php  }
                } ?>
            <?php } ?>
            /*end:  update load image*/
            /*thêm path image khi upload thành công */
            this.on("success", function(file, serverFileName) {
                $(file.previewTemplate).find('.dz-remove').attr('data-path', serverFileName);
                $(file.previewTemplate).find('.dz-image').append(
                    '<input type="hidden" name="images[]" value="' + serverFileName + '">')
                file.serverFn = serverFileName;
                fileList[i] = {
                    serverFileName
                };
                i++;
            });
            /*END: thêm path image khi upload thành công*/
        },
        removedfile: function(file) {
            if ($('.dz-preview').length === 1) {
                $('.dz-message').removeClass('hidden');
            } else {
                $('.dz-message').addClass('hidden');
            }
            var fileRef;
            return (fileRef = file.previewElement) != null ? fileRef.parentNode.removeChild(file.previewElement) : void 0;
        },
    });
</script>
<style>
    .dropzone {
        min-height: 150px;
        border: 2px dashed rgb(226, 232, 240, 0.6);
        background: white;
        padding: 10px 10px;
    }

    .dz-image img {
        height: 120px;
        width: 120px;
        object-fit: cover;
    }
</style>


<?php $__env->stopPush(); ?>
<?php echo $__env->make('homepage.layout.home', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/quyenitc/api/resources/views/customer/frontend/cart/create.blade.php ENDPATH**/ ?>