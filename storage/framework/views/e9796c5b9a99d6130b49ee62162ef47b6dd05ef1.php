<?php $__env->startSection('content'); ?>
<?php echo htmlBreadcrumb($seo['meta_title']); ?>

<main class="py-8">
    <div class="container px-4 mx-auto">
        <div class="mt-4 flex flex-col md:flex-row items-start md:space-x-8">
            <?php echo $__env->make('customer/frontend/auth/common/sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <div class="flex-1 w-full md:w-auto order-1 md:order-1">
                <div class="overflow-x-hidden space-y-4">
                    <div class=" bg-white">
                        <h1 class="text-black font-bold text-xl">Danh sách đơn hàng</h1>
                        <!-- Slider main container -->
                        <?php if($data): ?>
                        <div class="mt-5 space-y-2">

                            <div class="flex flex-wrap" style="margin: 0px -5px">
                                <div class="borderA border px-2 py-2 <?php if (empty(request()->get('status'))) { ?>active<?php } ?>" style="margin: 0px 5px;float: left;margin-bottom: 10px">
                                    <a href="<?php echo e(route('ordersF.index')); ?>">Tất cả<span class="font-bold"> (<?php echo e($data->total()); ?>)</span></a>
                                </div>
                                <?php $i = 0; ?>
                                <?php $__currentLoopData = config('cart')['status']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php $i++; ?>
                                <?php if($i > 1): ?>
                                <div class="borderA border px-2 py-2 <?php if ($key == request()->get('status')) { ?>active<?php } ?>" style="margin: 0px 5px;float: left;margin-bottom: 10px">
                                    <a href="<?php echo e(route('ordersF.index',['status' => $key])); ?>"><?php echo e($item); ?> <span class="font-bold">(<?php echo e($dataCount[$key]); ?>)</span></a>
                                </div>
                                <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>

                            <div class="">
                                <form class="w-full flex flex-col md:flex-row justify-between space-y-4 md:space-y-0">
                                    <div class="flex-1 grid grid-cols-4 md:grid-cols-8 gap-4">
                                        <input type="text" name="date_start" placeholder="Ngày bắt đầu" class="col-span-2 md:col-span-2 bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="<?php echo e(request()->get('date_start')); ?>" autocomplete="off">
                                        <input type="text" name="date_end" placeholder="Ngày kết thúc" class="col-span-2 md:col-span-2 bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="<?php echo e(request()->get('date_end')); ?>" autocomplete="off">
                                        <input type="text" name="keyword" placeholder="Nhập từ khóa tìm kiếm" class="col-span-3 md:col-span-3 bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="<?php echo e(request()->get('keyword')); ?>" autocomplete="off">
                                        <button type="submit" class="col-span-1 md:col-span-1 bg_gradient rounded-lg w-10 h-[42px] flex justify-center items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-white">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                                            </svg>
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <div class="listItem">
                                <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="grid grid-cols-1 md:grid-cols-3 md:space-x-5 p-3 rounded-xl gap-1 md:gap-8 mb-5 bg-gray-100">
                                    <div class="md:col-span-2">
                                        <a href="<?php echo e(route('ordersF.show',['id' => $item->id])); ?>" class="">
                                            <div class="flex justify-between items-center">
                                                <div class="font-bold text-red-600"><b class="text-black">Mã đơn hàng </b>#<?php echo e($item->code); ?></div>
                                            </div>
                                            <div>
                                                <label><b>Tên sản phẩm:</b> <?php echo e($item->title); ?></label>
                                            </div>
                                            <div>
                                                <label><b>Trạng thái:</b></label>
                                                <span style="background-color: <?php echo e(config('cart')['class'][$item->status]); ?>;" class="text-white px-2 py-1 rounded text-xs"><?php echo e(config('cart')['status'][$item->status]); ?></span>
                                            </div>
                                            <div class="flex space-x-1">
                                                <label><b>GIÁ:</b></label>
                                                <div class="flex space-x-1">
                                                    <?php if(!empty($item->total_price_vnd_final)): ?>
                                                    <span class="text-red-600 font-bold"><?php echo e(!empty($item->total_price_vnd_final) ? number_format($item->total_price_vnd_final,'0',',','.') : number_format($item->total_price_old,'0',',','.')); ?> VNĐ</span>
                                                    <span style="text-decoration: line-through;"><?php echo e(number_format($item->total_price_old,'0',',','.')); ?> VNĐ</span>
                                                    <?php else: ?>
                                                    <span class="text-red-600 font-bold"><?php echo e(number_format($item->total_price_old,'0',',','.')); ?> VNĐ</span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <?php if(!empty($item->status_return)): ?>
                                            <div>
                                                <label><b>Hoàn tiền:</b></label>
                                                <span style="background-color: <?php echo e(config('cart')['class'][$item->status_return]); ?>;" class="text-white px-2 py-1 rounded text-xs">#<?php echo e(config('cart')['status_return'][$item->status_return]); ?></span>
                                            </div>
                                            <?php endif; ?>
                                            <div>
                                                <label><b>Ngày:</b> <?php echo e($item->created_at); ?></label>
                                            </div>
                                        </a>
                                    </div>
                                    <div>
                                        <div class="flex flex-wrap md:flex-col md:space-y-2 space-x-2 md:space-x-0">
                                           
                                            <?php if($item->status === 'pending_payment'): ?>
                                            <a href="javascript:void(0)" data-id="<?php echo e($item->id); ?>" data-price="<?php echo e($item->total_price_vnd_final); ?>" class="ajax-order-store mb-2 md:mb-0 text-white text-center outline-none focus:outline-none hover:outline-none font-medium rounded-lg text-sm px-4 lg:px-5 h-9 leading-9" style="background-color: green">Thanh toán</a>
                                            <?php endif; ?>
                                            <?php if(empty($item->status_return)): ?>
                                            <?php if($item->status == 'pending_order' || $item->status == 'completed_order' || $item->status == 'pending' || $item->status == 'completed'): ?>
                                            <a href="<?php echo e(route('ordersF.return',['id' => $item->id])); ?>" style="background-color: <?php echo e(config('cart')['class']['returns']); ?>;" class="js_handle_returns text-center text-white outline-none focus:outline-none hover:outline-none font-medium rounded-lg text-sm px-4 lg:px-5  h-9 leading-9 mb-2 md:mb-0">Hoàn
                                                tiền</a>
                                            <?php endif; ?>
                                            <?php endif; ?>
                                            <?php if($item->status === 'wait' || $item->status === 'pending_payment'): ?>
                                            <a href="javascript:void(0)" data-id="<?php echo e($item->id); ?>" data-status="canceled" style="background-color: <?php echo e(config('cart')['class']['canceled']); ?>;" class="js_handle_status text-center text-white outline-none focus:outline-none hover:outline-none font-medium rounded-lg text-sm px-4 lg:px-5  h-9 leading-9 mb-2 md:mb-0">
                                                Hủy đơn hàng
                                            </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <div class="flex justify-center">
                                    <?php echo $data->links() ?>
                                </div>
                            </div>
                        </div>
                        <?php else: ?>
                        <div class="flex flex-col items-center ml-4  bg-white  rounded-xl mt-4 space-y-3">
                            <div class="bg-gray-100 rounded-full flex items-center justify-center w-[50px] h-[50px]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-global" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <strong class="font-bold mb-2"><?php echo e(trans('index.NoOrdersYet')); ?></strong>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
</main>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('javascript'); ?>
<style>
    .borderA.active {
        background: red;
        border-color: red;
    }

    .borderA.active a {
        color: white;
    }
</style>
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
<script>
    $(".menu_item_auth:eq(2)").addClass('active');
</script>
<script>
    $(document).on('click', '.ajax-order-store', function(e) {
        var price = $(this).attr('data-price')
        var id = $(this).attr('data-id')
        if (price > <?php echo  Auth::guard('customer')->user()->price ?>) {
            swal({
                    title: "Số dư trong tài khoản của bạn không đủ",
                    text: "",
                    type: "error",
                    confirmButtonText: "Nạp tiền!",
                },
                function() {
                    window.location.replace("<?php echo route('customer_payment.frontend_index', ['modal' => 'show']) ?>");
                }
            );
        } else {
            swal({
                    title: "Hãy chắc chắn rằng bạn muốn thực hiện thao tác này?",
                    text: '',
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
                        let formURL = "<?php echo route('ordersF.store_payment') ?>";
                        $.ajax({
                            headers: {
                                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                            },
                            url: formURL,
                            type: "POST",
                            dataType: "JSON",
                            data: {
                                id: id,
                            },
                            success: function(data) {
                                if (data.code === 200) {
                                    swal({
                                            title: "Thành công!",
                                            text: "Thanh toán đơn hàng thành công.",
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
                                    });
                                }
                            },
                            error: function(jqXhr, json, errorThrown) {
                                var errors = jqXhr.responseJSON;
                                var errorsHtml = "";
                                $.each(errors["errors"], function(index, value) {
                                    errorsHtml += value + "/ ";
                                });
                                swal({
                                    title: "ERROR",
                                    text: errorsHtml,
                                    type: "error",
                                });
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
        }
    })
</script>
<script>
    $(document).on('click', '.js_handle_status', function(e) {
        var status = $(this).attr('data-status');
        var id = $(this).attr('data-id');
        swal({
                title: "Hãy chắc chắn rằng bạn muốn thực hiện thao tác này?",
                text: '',
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
                    let formURL = "<?php echo route('ordersF.update_status') ?>";
                    $.ajax({
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                        },
                        url: formURL,
                        type: "POST",
                        dataType: "JSON",
                        data: {
                            id: id,
                            status: status
                        },
                        success: function(data) {
                            if (data.code === 200) {
                                swal({
                                        title: "Thành công!",
                                        text: "Thanh toán đơn hàng thành công.",
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
                                });
                            }
                        },
                        error: function(jqXhr, json, errorThrown) {
                            var errors = jqXhr.responseJSON;
                            var errorsHtml = "";
                            $.each(errors["errors"], function(index, value) {
                                errorsHtml += value + "/ ";
                            });
                            swal({
                                title: "ERROR",
                                text: errorsHtml,
                                type: "error",
                            });
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
<?php $__env->stopPush(); ?>

<?php echo $__env->make('homepage.layout.home', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/quyenitc/api/resources/views/customer/frontend/order/index.blade.php ENDPATH**/ ?>