<?php $__env->startSection('content'); ?>
<main class="my-5">
    <div class="container mx-auto px-4 space-y-5">
        <h1 class="font-bold uppercase text-2xl">Dánh sách giao hàng</h1>
        <form class="w-full flex flex-col md:flex-row justify-between space-y-4 md:space-y-0">
            <div class="flex-1 grid grid-cols-1 md:grid-cols-4 gap-4">
                <input type="text" name="code" placeholder="Nhập mã vận đơn" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="<?php echo e(request()->get('code')); ?>">

                <button type="submit" class="bg-red-600 rounded-lg w-10 h-[42px] flex justify-center items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-white">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                </button>
            </div>
            <div class="w-28">
                <a href="<?php echo e(route('deliveryHome.export',['code'=>request()->get('code')])); ?>" class="bg-[#5cb85c] rounded-lg h-[42px] w-full float-left flex items-center justify-center text-white">Xuất excel</a>
            </div>
        </form>
        <div class="flex flex-col">
            <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8">
                    <div class="overflow-hidden space-y-5">
                        <table class="min-w-full text-center text-sm font-light">
                            <thead class="border-b bg-neutral-800 font-medium text-white dark:border-neutral-500 dark:bg-neutral-900">
                                <tr>
                                    <th scope="col" class=" py-4">NGÀY</th>
                                    <th scope="col" class=" py-4">CODE </th>
                                    <th scope="col" class=" py-4">Mã KH </th>
                                    <th scope="col" class=" py-4">CÂN NẶNG(KG) </th>
                                    <th scope="col" class=" py-4">Biểu phí </th>
                                    <th scope="col" class=" py-4">Phụ phí </th>
                                    <th scope="col" class=" py-4">Thành tiền </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($data) && count($data) > 0): ?>
                                <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="border-b dark:border-neutral-500">
                                    <td class="whitespace-nowrap  py-4"> <?php echo e($v->created_at); ?></td>
                                    <td class="whitespace-nowrap  py-4 "><a href="<?php echo e(route('deliveryHome.detail',['id' => $v->id])); ?>" class="text-red-600 font-bold" style="text-decoration: underline;"><?php echo e($v->code); ?></a></td>
                                    <td class="whitespace-nowrap  py-4"> <?php echo e(!empty($v->customer) ? $v->customer->code : ''); ?></td>
                                    <td class="whitespace-nowrap  py-4"> <?php echo e($v->weight); ?></td>
                                    <td class="whitespace-nowrap  py-4"> <?php echo e(number_format($v->fee,'0',',','.')); ?></td>
                                    <td class="whitespace-nowrap  py-4"> <?php echo e(number_format($v->shipping,'0',',','.')); ?></td>
                                    <td class="whitespace-nowrap  py-4"> <?php echo e(number_format($v->price,'0',',','.')); ?></td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('homepage.layout.home', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/quyenitc/vanchuyenhangtrung.com/resources/views/delivery/frontend/search.blade.php ENDPATH**/ ?>