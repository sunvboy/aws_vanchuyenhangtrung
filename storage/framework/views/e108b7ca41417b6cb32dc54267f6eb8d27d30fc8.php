 <?php if($data): ?>
 <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 -mx-[15px]">
     <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
     <div class="px-[15px] mb-[30px]">
         <?php echo htmlArticle($item) ?>
     </div>
     <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
 </div>
 <div class="mt-5">
     <?php echo $data->links() ?>
 </div>
 <?php endif; ?><?php /**PATH /home/quyenitc/vanchuyenhangtrung.com/resources/views/article/frontend/category/data.blade.php ENDPATH**/ ?>