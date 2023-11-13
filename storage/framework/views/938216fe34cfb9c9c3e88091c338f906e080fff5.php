<?php $__env->startSection('content'); ?>
<?php echo htmlBreadcrumb($seo['meta_title']); ?>

<main class="py-8">
    <div class="container px-4 mx-auto">
        <div class="mt-4 flex flex-col md:flex-row items-start md:space-x-4">
            <?php echo $__env->make('customer/frontend/auth/common/sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <div class="flex-1 overflow-x-hidden shadowC rounded-xl w-full md:w-auto order-1 md:order-2">
                <div class="p-6 bg-white">
                    <div class="flex justify-between items-center">
                        <h1 class="text-black font-bold text-xl">Nạp tiền</h1>

                    </div>
                    <div class="mt-2">
                        <form class="w-full flex flex-col md:flex-row justify-between space-y-4 md:space-y-0">
                            <div class="flex-1 grid grid-cols-1 md:grid-cols-6 gap-4">
                                <input type="text" name="keyword" placeholder="Nhập từ khóa tìm kiếm" class="col-span-2 bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="<?php echo e(request()->get('keyword')); ?>" autocomplete="off">
                                <input type="text" name="date_start" placeholder="Ngày bắt đầu" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="<?php echo e(request()->get('date_start')); ?>" autocomplete="off">
                                <input type="text" name="date_end" placeholder="Ngày kết thúc" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="<?php echo e(request()->get('date_end')); ?>" autocomplete="off">
                                <button type="submit" class="bg_gradient rounded-lg w-10 h-[42px] flex justify-center items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-white">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                                    </svg>
                                </button>
                                <div>
                                    <a href="javascript:void(0)" class="bg_gradient rounded-lg text-white h-[42px] px-4 flex justify-center items-center" id="js_clickModal"> Nạp tiền</a>
                                </div>
                            </div>
                        </form>
                        <div class="flex flex-col">
                            <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                                <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8">
                                    <div class="overflow-hidden space-y-5">
                                        <table class="min-w-full text-center text-sm font-light">
                                            <thead class="border-b bg-neutral-800 font-medium text-white dark:border-neutral-500 dark:bg-neutral-900">
                                                <tr>
                                                    <th scope="col" class=" py-4">#</th>
                                                    <th scope="col" class=" py-4">MÃ GIAO DỊCH</th>
                                                    <th scope="col" class=" py-4">SỐ TIỀN</th>
                                                    <th scope="col" class=" py-4">NGÀY 日期</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if(!empty($data) && count($data) > 0): ?>
                                                <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr class="border-b dark:border-neutral-500">
                                                    <td class="whitespace-nowrap  py-4"> <?php echo e($data->firstItem()+$loop->index); ?></td>
                                                    <td class="whitespace-nowrap  py-4 font-bold"><?php echo e($v->code); ?></td>
                                                    <td class="whitespace-nowrap  py-4 font-bold" <?php if($v->type == 'plus'): ?> style="color:#04AA6D" <?php else: ?> style="color:#f44336" <?php endif; ?>> <?php if($v->type == 'plus'): ?> + <?php else: ?> - <?php endif; ?> <?php echo e(number_format($v->price,'0',',','.')); ?></td>
                                                    <td class="whitespace-nowrap  py-4"> <?php echo e($v->created_at); ?></td>
                                                </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                        <?php echo e($data->links()); ?>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php $__env->stopSection(); ?>
<?php $__env->startPush('javascript'); ?>
<!-- Main modal -->
<div id="defaultModal" tabindex="-1" aria-hidden="true" style="background: #00000047;" class="hidden fixed top-0 left-0 right-0 z-50 w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-screen max-h-full">
    <div class="relative w-full max-w-2xl max-h-full mx-auto">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Nạp tiền vào tài khoản
                </h3>
                <button type="button" class="js_btnCloseModal text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="defaultModal">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <div class="p-6">
                <?php echo $fcSystem['homepage_naptien']; ?>

            </div>
            <!-- Modal footer -->
        </div>
    </div>
</div>
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
    <?php if (!empty(request()->get('modal'))) { ?>
        $('#defaultModal').removeClass('hidden')
    <?php } ?>
    $('.menu_item_auth:eq(3)').addClass('active')
    $(document).on('click', '#js_clickModal', function(e) {
        $('#defaultModal').removeClass('hidden')
    })
    $(document).on('click', '.js_btnCloseModal', function(e) {
        $('#defaultModal').addClass('hidden')
    })
</script>

<?php $__env->stopPush(); ?>
<?php echo $__env->make('homepage.layout.home', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\vanchuyenhangtrung.local\resources\views/customer/frontend/customer_payments/index.blade.php ENDPATH**/ ?>