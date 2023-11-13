
<?php $__env->startSection('content'); ?>
<main class="my-5">
    <div class="container mx-auto px-4 space-y-5">
        <h1 class="font-bold uppercase text-2xl"><a href="<?php echo e(route('deliveryHome.search')); ?>">Chi tiết đơn giao <?php echo e($detail->code); ?></a></h1>
        <div class="flex flex-col">
            <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8">
                    <div class="overflow-hidden space-y-5">
                        <table class="min-w-full text-center text-sm font-light">
                            <thead class="border-b bg-neutral-800 font-medium text-white dark:border-neutral-500 dark:bg-neutral-900">
                                <tr>
                                    <th scope="col" class=" py-4">STT</th>
                                    <th scope="col" class=" py-4">Mã vận đơn 提單代碼 </th>
                                    <th scope="col" class=" py-4">CÂN NẶNG(KG) 重量 </th>
                                    <th scope="col" class=" py-4">Ghi chú 筆記 </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $products = json_decode($detail->products, TRUE);
                                if (isset($products) && is_array($products) && count($products)) { ?>
                                    <?php foreach ($products['code'] as $key => $val) {
                                    ?>
                                        <tr class="border-b dark:border-neutral-500">
                                            <td class="whitespace-nowrap  py-4"> <?php echo e($key+1); ?></td>
                                            <td>
                                                <?php echo e($products['code'][$key]); ?>

                                            </td>
                                            <td>
                                                <?php echo e($products['weight'][$key]); ?>

                                            </td>
                                            <td>
                                                <?php echo e($products['note'][$key]); ?>

                                            </td>

                                        </tr>
                                    <?php } ?>
                                <?php } ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td class="text-right font-bold py-4" colspan="2">
                                        <?php echo e(trans('admin.total_weight')); ?>

                                    </td>
                                    <td class="text-danger font-bold py-4" id="tongsocan">
                                        <?php echo e($detail->weight); ?>

                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-right font-bold py-4" colspan="2">
                                        Đơn giá
                                    </td>
                                    <td>
                                        <?php echo e($detail->price); ?>

                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-right font-bold py-4" colspan="2">
                                        Thành tiền
                                    </td>

                                    <td class="text-danger font-bold">
                                        <?php echo e(number_format($detail->weight*$detail->price)); ?>

                                    </td>
                                </tr>

                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('homepage.layout.home', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/quyenitc/api/resources/views/delivery/frontend/detail.blade.php ENDPATH**/ ?>