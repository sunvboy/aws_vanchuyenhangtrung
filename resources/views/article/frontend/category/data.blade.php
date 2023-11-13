 @if($data)
 <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 -mx-[15px]">
     @foreach ($data as $k => $item)
     <div class="px-[15px] mb-[30px]">
         <?php echo htmlArticle($item) ?>
     </div>
     @endforeach
 </div>
 <div class="mt-5">
     <?php echo $data->links() ?>
 </div>
 @endif