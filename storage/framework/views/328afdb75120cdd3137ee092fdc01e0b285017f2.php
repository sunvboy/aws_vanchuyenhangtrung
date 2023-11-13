<?php $__env->startSection('content'); ?>
<?php echo htmlBreadcrumb($seo['meta_title']); ?>

<main class="py-8">
    <div class="container px-4 mx-auto">
        <div class="mt-4 flex flex-col md:flex-row items-start md:space-x-4">
            <?php echo $__env->make('customer/frontend/auth/common/sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <div class="flex-1 overflow-x-hidden shadowC rounded-xl w-full md:w-auto order-1 md:order-2">
                <div class="p-6 bg-white">
                    <div class="flex justify-between items-center">
                        <h1 class="text-black font-bold text-xl"><?php echo e($seo['meta_title']); ?></h1>
                    </div>
                    <div class="mt-2">
                        <form class="w-full flex flex-col md:flex-row justify-between space-y-4 md:space-y-0">
                            <div class="flex-1 grid grid-cols-1 md:grid-cols-5 gap-4">
                                <input type="text" name="keyword" placeholder="Nhập từ khóa tìm kiếm" class="col-span-2 bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="<?php echo e(request()->get('keyword')); ?>" autocomplete="off">
                                <input type="text" name="date_start" placeholder="Ngày bắt đầu" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="<?php echo e(request()->get('date_start')); ?>" autocomplete="off">
                                <input type="text" name="date_end" placeholder="Ngày kết thúc" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="<?php echo e(request()->get('date_end')); ?>" autocomplete="off">
                                <button type="submit" class="bg_gradient rounded-lg w-10 h-[42px] flex justify-center items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-white">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                                    </svg>
                                </button>
                            </div>
                        </form>
                        <div class="flex flex-col">
                            <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                                <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8">
                                    <div class="overflow-hidden space-y-5">
                                        <table class="min-w-full text-sm font-light">
                                            <thead class="border-b bg-neutral-800 font-medium text-white dark:border-neutral-500 dark:bg-neutral-900">
                                                <tr>
                                                    <th scope="col" class=" py-2">#</th>
                                                    <th scope="col" class=" py-2">HÀNH ĐỘNG</th>
                                                    <th scope="col" class=" py-2">SỐ DƯ TRƯỚC</th>
                                                    <th scope="col" class=" py-2">SỐ TIỀN TIÊU</th>
                                                    <th scope="col" class=" py-2">SỐ DƯ HIỆN TẠI</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if(!empty($data) && count($data) > 0): ?>
                                                <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr class="border-b dark:border-neutral-500">
                                                    <td class="whitespace-nowrap  p-2"> <?php echo e($data->firstItem()+$loop->index); ?></td>
                                                    <td class="whitespace-nowrap  p-2 ">
                                                        <p><?php echo e($v->created_at); ?></p>
                                                        <p class="font-bold"><?php echo e($v->note); ?></p>
                                                    </td>
                                                    <td class="whitespace-nowrap  p-2 font-bold"><?php echo e(number_format($v->price_old,'0',',','.')); ?></td>
                                                    <?php if($v->price_final-$v->price_old < 0): ?> <td class="whitespace-nowrap  p-4 font-bold text-red-600"><?php echo e(number_format($v->price_final-$v->price_old,'0',',','.')); ?></td>
                                                        <?php else: ?>
                                                        <td class="whitespace-nowrap  p-2 font-bold text-green-500">+ <?php echo e(number_format($v->price_final-$v->price_old,'0',',','.')); ?></td>
                                                        <?php endif; ?>

                                                        <td class="whitespace-nowrap  p-2 font-bold"><?php echo e(number_format($v->price_final,'0',',','.')); ?></td>
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
    $('.menu_item_auth:eq(4)').addClass('active')
</script>

<?php $__env->stopPush(); ?>
<?php echo $__env->make('homepage.layout.home', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\vanchuyenhangtrung.local\resources\views/customer/frontend/customer_payments/index_payment_logs.blade.php ENDPATH**/ ?>