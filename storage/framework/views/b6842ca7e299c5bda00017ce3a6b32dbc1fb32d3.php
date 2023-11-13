
<?php $__env->startSection('content'); ?>
<main class="my-5">
    <div class="container mx-auto px-4 space-y-5">
        <h1 class="font-bold uppercase text-2xl">Dánh sách mã vận đơn</h1>
        <form class="w-full flex flex-col md:flex-row justify-between space-y-4 md:space-y-0">
            <div class="flex-1 grid grid-cols-1 md:grid-cols-4 gap-4">
                <input type="text" name="keyword" placeholder="Nhập từ khóa tìm kiếm" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="<?php echo e(request()->get('keyword')); ?>">
                <input type="text" name="date_start" placeholder="Ngày bắt đầu" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="<?php echo e(request()->get('date_start')); ?>">
                <input type="text" name="date_end" placeholder="Ngày kết thúc" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="<?php echo e(request()->get('date_end')); ?>">
                <button type="submit" class="bg-red-600 rounded-lg w-10 h-[42px] flex justify-center items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-white">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                </button>
            </div>
            <div class="w-28">
                <a href="<?php echo e(route('bill.excel',['date_start'=>request()->get('date_start'),'date_end'=>request()->get('date_end'),'keyword'=>request()->get('keyword')])); ?>" class="bg-[#5cb85c] rounded-lg h-[42px] w-full float-left flex items-center justify-center text-white">Xuất excel</a>
            </div>
        </form>
        <div class="flex flex-col">
            <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8">
                    <div class="overflow-hidden space-y-5">
                        <table class="min-w-full text-center text-sm font-light">
                            <thead class="border-b bg-neutral-800 font-medium text-white dark:border-neutral-500 dark:bg-neutral-900">
                                <tr>
                                    <td class="bg-sky-500 py-4">Tổng dòng</td>
                                    <td class="bg-sky-500 py-4 font-bold"><?php echo e($count); ?></td>
                                    <td class="bg-red-600 py-4">Tổng cân</td>
                                    <td class="bg-red-600 py-4 font-bold"><?php echo e($total); ?></td>
                                </tr>
                                <tr>
                                    <th scope="col" class=" py-4">NGÀY 日期</th>
                                    <th scope="col" class=" py-4">MÃ VẬN ĐƠN 运单号 </th>
                                    <th scope="col" class=" py-4">MÃ VN </th>
                                    <th scope="col" class=" py-4">CÂN NẶNG(KG) 重量 </th>
                                    <th scope="col" class=" py-4">Số lượng kiện 数量件 </th>
                                    <th scope="col" class=" py-4">Số lượng sản phẩm 数量产品 </th>
                                    <th scope="col" class=" py-4">TÊN SẢN PHẨM 品名 </th>
                                    <th scope="col" class=" py-4">TÊN SẢN PHẨM VN </th>
                                    <th scope="col" class=" py-4">GIÁ </th>
                                    <th scope="col" class=" py-4">MÃ BAO 包号 </th>
                                    <th scope="col" class=" py-4">Trạng thái</th>
                                    <th scope="col" class=" py-4">MÃ giao hàng </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($data) && count($data) > 0): ?>
                                <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="border-b dark:border-neutral-500">
                                    <td class="whitespace-nowrap  py-4"> <?php echo e($v->date); ?></td>
                                    <td class="whitespace-nowrap  py-4"><?php echo e($v->code_cn); ?></td>
                                    <td class="whitespace-nowrap  py-4"> <?php echo e($v->code_vn); ?></td>
                                    <td class="whitespace-nowrap  py-4"> <?php echo e($v->weight); ?></td>
                                    <td class="whitespace-nowrap  py-4"> <?php echo e($v->quantity_kien); ?></td>
                                    <td class="whitespace-nowrap  py-4"> <?php echo e($v->quantity); ?></td>
                                    <td class="whitespace-nowrap  py-4">
                                        <?php echo e($v->name_cn); ?>

                                    </td>
                                    <td class="whitespace-nowrap  py-4">
                                        <?php echo e($v->name_vn); ?>

                                    </td>
                                    <td class="whitespace-nowrap  py-4">
                                        <?php echo e($v->price); ?>

                                    </td>

                                    <td class="whitespace-nowrap  py-4">
                                        <?php if(!empty($v->packaging_relationships) && !empty($v->packaging_relationships->packagings)): ?>
                                        <?php echo e($v->packaging_relationships->packagings->code); ?>

                                        <?php endif; ?>
                                    </td>
                                    <td class="whitespace-nowrap  py-4 px-2">
                                        <?php if(!empty($v->packaging_relationships) && !empty($v->packaging_relationships->packagings) && !empty($v->packaging_relationships->packagings->packaging_v_n_s)): ?>
                                        <span class="bg-green-600 text-white rounded-md p-2">Đã về VN</span>
                                        <?php else: ?>
                                        <span class="bg-red-600 text-white rounded-md p-2">Nhập kho TQ</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="whitespace-nowrap  py-4">
                                        <?php if(!empty($v->delivery_relationships)): ?>
                                        <?php echo e($v->delivery_relationships->deliveries_code); ?>

                                        <?php endif; ?>
                                    </td>
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
</main>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('javascript'); ?>
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

<?php $__env->stopPush(); ?>
<?php echo $__env->make('homepage.layout.home', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\vanchuyenhangtrung.local\resources\views/warehouses/frontend/index.blade.php ENDPATH**/ ?>