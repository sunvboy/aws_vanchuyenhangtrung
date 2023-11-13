<?php $__env->startSection('title'); ?>
<title><?php echo e(trans('admin.index_category_customer')); ?></title>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
<?php
$array = array(
    [
        "title" => trans('admin.index_category_customer'),
        "src" => 'javascript:void(0)',
    ]
);
echo breadcrumb_backend($array);
?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="content">
    <h1 class=" text-lg font-medium mt-10">
        <?php echo e(trans('admin.index_category_customer')); ?>

    </h1>

    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class=" col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2 justify-between">
            <?php echo $__env->make('components.search',['catalogue' => TRUE,'module'=>$module], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('customers_create')): ?>
            <a href="<?php echo e(route('customer_categories.create')); ?>" class="btn btn-primary shadow-md mr-2"><?php echo e(trans('admin.create')); ?></a>
            <?php endif; ?>
        </div>
        <!-- BEGIN: Data List -->
        <div class=" col-span-12 lg:col-span-12">
            <table class="table table-report -mt-2">
                <thead class="table-dark">
                    <tr>

                        <th class="text-center">STT</th>
                        <th class="whitespace-nowrap"><?php echo e(trans('admin.title')); ?></th>
                        <th class="whitespace-nowrap"><?php echo e(trans('admin.code_customer')); ?></th>
                        <th class="whitespace-nowrap"><?php echo e(trans('admin.created')); ?></th>
                        <th class="whitespace-nowrap text-center">#</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                    <tr class="odd " id="post-<?php echo $v->id; ?>">
                        <td>
                            <?php echo e($data->firstItem()+$loop->index); ?>

                        </td>
                        <td style="text-align:left"><a href="<?php echo e(route('customers.index',['catalogueid' => $v->id])); ?>"><?php echo e($v->title); ?>(<?php echo e($v->customers->count()); ?>)</a></td>
                        <td>
                            <?php echo e($v->slug); ?>

                        </td>
                        <td>
                            <?php echo e($v->created_at); ?>

                        </td>
                        <td class="table-report__action w-56">
                            <div class="flex justify-center items-center">
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('customers_edit')): ?>
                                <a class="flex items-center mr-3" href="<?php echo e(route('customer_categories.edit',['id'=>$v->id])); ?>">
                                    <i data-lucide="check-square" class="w-4 h-4 mr-1"></i>
                                    Edit
                                </a>
                                <?php endif; ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('customers_destroy')): ?>
                                <?php if($v->id != 5 && $v->id != 8): ?>

                                <a class="flex items-center text-danger <?php echo !empty($v->customers->count() == 0) ? 'ajax-delete' : '' ?> <?php echo !empty($v->customers->count() == 0) ? '' : 'disabled' ?>" href="javascript:;" data-id="<?php echo $v->id ?>" data-module="<?php echo $module ?>" data-child="0" data-title="Lưu ý: Khi bạn xóa thương hiệu, thương hiệu sẽ bị xóa vĩnh viễn. Hãy chắc chắn rằng bạn muốn thực hiện hành động này!">
                                    <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i> Delete
                                </a>
                                <?php endif; ?>
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
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('dashboard.layout.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/quyenitc/vanchuyenhangtrung.com/resources/views/customer/backend/category/index.blade.php ENDPATH**/ ?>