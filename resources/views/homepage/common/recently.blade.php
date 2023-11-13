 <?php
    $recently_viewed = Session::get('products.recently_viewed');
    if (!empty($recently_viewed)) {
        $recentlyProduct = \App\Models\Product::select('id', 'title', 'slug', 'price', 'price_sale', 'price_contact', 'image')
            ->where(['alanguage' => config('app.locale'), 'publish' => 0])
            ->whereIn('id', $recently_viewed)
            ->orderBy('order', 'asc')
            ->orderBy('id', 'desc')
            ->with('getTags')
            ->get();
    }
    ?>
 @if(!empty($recentlyProduct))
 <!-- start: box 4 -->
 <section>
     <div class="container mx-auto px-4 md:px-0">
         <h2 class="titleH2 text-global text-2xl hover:text-primary font-bold border-b border-gray-300 ">
             <a href="javascript:void(0)" class="relative">
                 {{trans('index.ProductViewed')}}
             </a>
         </h2>
         <div class="mt-5 -mx-[15px]">
             <div class="slider-product owl-carousel">
                 @foreach ($recentlyProduct as $key=>$item)
                 <div class="item">
                     <?php echo htmlItemProduct($key, $item, 'px-[5px] md:px-[10px]'); ?>
                 </div>
                 @endforeach
             </div>

         </div>
     </div>
 </section>
 <!-- end: box 4 -->
 @endif