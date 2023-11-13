<div class="offcanvas-overlay fixed inset-0 bg-black opacity-50 z-50 hidden"></div>
<div id="offcanvas-cart" class="w-auto offcanvas left-auto right-0 transform fixed font-normal text-sm top-0 z-50 h-screen transition-all ease-in-out duration-300 bg-white hidden animated fadeInRight" style="z-index: 999099;">
    <div class="flex justify-between h-screen">
        <div class="max-w-full md:w-[500px] product-cart flex flex-col">
            <div class="flex flex-wrap justify-between items-center px-10 py-6 border-b border-gray-300 flex-shrink-0">
                <h4 class="font-bold text-xl flex items-center space-x-2">
                    <svg focusable="false" width="20" height="18" class="icon icon--header-cart   " viewBox="0 0 20 18">
                        <path d="M3 1h14l1 16H2L3 1z" fill="none" stroke="currentColor" stroke-width="2"></path>
                        <path d="M7 4v0a3 3 0 003 3v0a3 3 0 003-3v0" fill="none" stroke="currentColor" stroke-width="2"></path>
                    </svg>
                    <span class="cart-quantity">{{$cart['quantity']}}</span><span>items</span>
                </h4>
                <button class="offcanvas-close hover:text-green-500">
                    <svg focusable="false" width="14" height="14" class="icon icon--close   " viewBox="0 0 14 14">
                        <path d="M13 13L1 1M13 1L1 13" stroke="currentColor" stroke-width="2" fill="none"></path>
                    </svg>
                </button>
            </div>
            <div id="cart-show-header" <?php if (empty($cart['cart'])) { ?> style="display: none" <?php } ?> class="px-10 mt-6 content flex-grow h-full overflow-y-auto scrollbar max-h-screen">
                <ul class="cart-html-header  space-y-6">
                    @if(isset($cart['cart']) && is_array($cart['cart']) && count($cart['cart']) > 0 )
                    @foreach($cart['cart'] as $k=>$item)
                    <?php
                    echo htmlItemCartHeader($k, $item);
                    ?>
                    @endforeach
                    @endif
                </ul>
            </div>
            <div id="cart-bottom-header" class="px-10 py-5" <?php if (empty($cart['cart'])) { ?> style="display: none" <?php } ?>>
                <a href="{{route('cart.checkout')}}" class="w-full flex bg-global tracking-widest h-[52px] justify-center items-center text-white font-bold relative space-x-3">
                    <span class="absolute left-5">
                        <svg focusable="false" width="17" height="17" class="icon icon--lock   " viewBox="0 0 17 17">
                            <path d="M2.5 7V15H14.5V7H2.5Z" stroke="currentColor" stroke-width="1.5" fill="none"></path>
                            <path d="M5.5 4C5.5 2.34315 6.84315 1 8.5 1V1C10.1569 1 11.5 2.34315 11.5 4V7H5.5V4Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" fill="none"></path>
                            <circle cx="8.5" cy="11" r="0.5" stroke="currentColor"></circle>
                        </svg>
                    </span>
                    <span class="uppercase">
                        {{trans('index.Pay')}}
                    </span>
                    <span class="w-1 h-1 rounded-full bg-white"></span>
                    <span class="cart-total">
                        <?php echo !empty($cart['total']) ? number_format($cart['total'], 0, ',', '.') . '₫' : '' ?>
                    </span>
                </a>
            </div>
            <div id="cart-none-header" <?php if (!empty($cart['cart'])) { ?> style="display: none" <?php } ?> class="px-10 mt-6 content flex-grow">
                <div class="flex flex-col items-center justify-center space-y-4">
                    <svg width="100" height="100" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-gray-400">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M5.79166 2H1V4H4.2184L6.9872 16.6776H7V17H20V16.7519L22.1932 7.09095L22.5308 6H6.6552L6.08485 3.38852L5.79166 2ZM19.9869 8H7.092L8.62081 15H18.3978L19.9869 8Z" fill="currentColor" />
                        <path d="M10 22C11.1046 22 12 21.1046 12 20C12 18.8954 11.1046 18 10 18C8.89543 18 8 18.8954 8 20C8 21.1046 8.89543 22 10 22Z" fill="currentColor" />
                        <path d="M19 20C19 21.1046 18.1046 22 17 22C15.8954 22 15 21.1046 15 20C15 18.8954 15.8954 18 17 18C18.1046 18 19 18.8954 19 20Z" fill="currentColor" />
                    </svg>
                    <span class="block text-xl font-bold text-gray-400">{{trans('index.ThereAreNo')}}</span>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- functions tp -->
<script src="{{asset('library/toastr/toastr.min.js')}}"></script>
<link href="{{asset('library/toastr/toastr.min.css')}}" rel="stylesheet">
<script src="{{asset('frontend/library/js/products.js')}}"></script>
<!-- end -->