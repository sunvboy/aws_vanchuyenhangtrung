
<?php $__env->startSection('title'); ?>
<title>Lịch sử giao dịch</title>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
<?php
$array = array(
    [
        "title" => "Lịch sử giao dịch",
        "src" => 'javascript:void(0)',
    ]
);
echo breadcrumb_backend($array);
?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="content ">
    <div class="flex justify-between mt-5">
        <h1 class=" text-lg font-medium">
            Lịch sử giao dịch
        </h1>
    </div>
    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="col-span-12 mt-2">
            <form action="" class="grid grid-cols-12 gap-2" id="search" style="margin-bottom: 0px;">
                <div class="col-span-2">
                    <?php echo Form::select('customer_id', $customers, request()->get('customer_id'), ['class' => 'form-control tom-select tom-select-custom tomselected', 'data-placeholder' => "Select your favorite actors"]); ?>
                </div>
                <div class="col-span-2">
                    <?php echo Form::text('date_start', request()->get('date_start'), ['class' => 'form-control h-10', 'autocomplete' => 'off', 'placeholder' => 'Ngày bắt đầu']); ?>
                </div>
                <div class="col-span-2">
                    <?php echo Form::text('date_end', request()->get('date_end'), ['class' => 'form-control h-10', 'autocomplete' => 'off', 'placeholder' => 'Ngày kết thúc']); ?>
                </div>

                <div class="col-span-1 flex items-center space-x-2 justify-end">
                    <button class="btn btn-primary btn-sm">
                        <i data-lucide="search"></i>
                    </button>
                </div>
            </form>

        </div>
        <!-- BEGIN: Data List -->
        <div class=" col-span-12 lg:col-span-12">
            <table class="table table-report -mt-2">
                <thead class="table-dark">
                    <tr>
                        <th>STT</th>
                        <th>HÀNH ĐỘNG</th>
                        <th>SỐ DƯ TRƯỚC</th>
                        <th>SỐ TIỀN TIÊU</th>
                        <th>SỐ DƯ HIỆN TẠI</th>
                        <th>Ngày giao dịch</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="odd" id="post-<?php echo $v->id; ?>">
                        <td> <?php echo e($data->firstItem()+$loop->index); ?></td>
                        <td class="whitespace-nowrap font-bold">
                            <?php echo e($v->note); ?>

                        </td>
                        <td class="whitespace-nowrap">
                            <?php echo e(number_format($v->price_old,'0',',','.')); ?>

                        </td>
                        <?php if($v->price_final-$v->price_old < 0): ?> <td class="whitespace-nowrap font-bold text-danger"><?php echo e(number_format($v->price_final-$v->price_old,'0',',','.')); ?></td>
                            <?php else: ?>
                            <td class="whitespace-nowrap font-bold text-success">+ <?php echo e(number_format($v->price_final-$v->price_old,'0',',','.')); ?></td>
                            <?php endif; ?>
                            <td class="whitespace-nowrap font-bold">
                                <?php echo e(number_format($v->price_final,'0',',','.')); ?>

                            </td>
                            <td class="whitespace-nowrap">
                                <?php echo e($v->created_at); ?>

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
</div>
<style>
    .btn-success td {
        background: #dff0d8 !important;
        color: #3c763d !important;
    }

    .btn-danger td {
        color: #a94442 !important;
        background-color: #f2dede !important;
        border-color: #ebccd1 !important;
    }
</style>
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
<?php echo $__env->make('dashboard.layout.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\vanchuyenhangtrung.local\resources\views/customer/backend/customer_payments/index_payment_logs.blade.php ENDPATH**/ ?>