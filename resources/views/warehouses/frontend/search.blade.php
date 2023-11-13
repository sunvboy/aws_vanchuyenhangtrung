@extends('homepage.layout.home')
@section('content')
<main class="my-5">
    <div class="container mx-auto px-4 space-y-5">
        <h1 class="font-bold uppercase text-2xl">TÌM KIẾM MÃ VẬN ĐƠN</h1>
        <form class="w-full flex flex-col md:flex-row justify-between space-y-4 md:space-y-0">
            <div class="flex-1 grid grid-cols-12 md:grid-cols-3 gap-4">
                <input type="text" name="keyword" placeholder="Mã vận đơn 运单号" class="col-span-9 md:col-span-2 bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="{{request()->get('keyword')}}">
                <button type="submit" class="col-span-3 md:col-span-1 bg-red-600 rounded-lg w-10 h-[42px] flex justify-center items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-white">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                </button>
            </div>
        </form>
        <div class="mt-5">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                @if(!empty($data) && count($data) > 0)
                @foreach($data as $v)
                <div class="h-full">
                    <div class="border border-primary space-y-3 pb-5 h-full">
                        <div class="bg-primary flex justify-between items-center">
                            <div class=" text-white px-4 py-2 font-bold">
                                {{$v->code_cn}}
                            </div>
                        </div>
                        <div class="px-4 py-2 space-y-1">
                            <div class="flex justify-between items-center">
                                <span>Khách hàng</span>
                                <span>{{$v->customer->code}} - {{$v->customer->name}}</span>
                            </div>
                            @if(!empty($v->delivery_relationships))
                            <div class="flex justify-between items-center">
                                <span>Khách giao</span>
                                <span>{{$v->delivery_relationships->deliveries->customer->code}} - {{$v->delivery_relationships->deliveries->customer->name}}</span>
                            </div>
                            @endif
                            <div class="flex justify-between items-center">
                                <span>MÃ VIỆT</span>
                                <span class="font-bold text-red-600">
                                 {{$v->code_vn}}
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span>MÃ BAO</span>
                                <span class="font-bold text-red-600">
                                      @if(!empty($v->packaging_relationships) && !empty($v->packaging_relationships->packagings))
                                        {{$v->packaging_relationships->packagings->code}}
                                    @endif
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span>TÊN SẢN PHẨM</span>
                                <span>{{$v->name_cn}} ({{$v->name_vn}})</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span>Số lượng sản phẩm</span>
                                <span>{{$v->quantity}}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span>CÂN NẶNG</span>
                                <span>{{$v->weight}} kg</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span>Số lượng kiện</span>
                                <span>{{$v->quantity_kien}}</span>
                            </div>

                        </div>
                        <div class="px-4">
                            <ol class="relative border-l border-gray-200 dark:border-gray-700">
                                <li class="mb-2 ml-4">
                                    <div class="absolute w-3 h-3 bg-gray-200 rounded-full mt-1.5 -left-1.5 border border-white dark:border-gray-900 dark:bg-gray-700"></div>
                                    <time class="mb-1 text-sm font-normal leading-none text-gray-400 dark:text-gray-500">{{$v->created_at}}</time>
                                    <h3 class="text-lg font-semibold text-red-600">Nhập kho Trung Quốc</h3>
                                </li>
                                @if(!empty($v->status_packaging_truck))
                                <li class="mb-2 ml-4">
                                    <div class="absolute w-3 h-3 bg-gray-200 rounded-full mt-1.5 -left-1.5 border border-white dark:border-gray-900 dark:bg-gray-700"></div>
                                    <time class="mb-1 text-sm font-normal leading-none text-gray-400 dark:text-gray-500">{{$v->date_packaging_truck}}</time>
                                    <h3 class="text-lg font-semibold" style="color: #F77300">Đang trên đường về Việt Nam</h3>
                                </li>
                                @endif

                                @if(!empty($v->packaging_relationships) && !empty($v->packaging_relationships->packagings) && !empty($v->packaging_relationships->packagings->packaging_v_n_s))
                                <li class="mb-2 ml-4">
                                    <div class="absolute w-3 h-3 bg-gray-200 rounded-full mt-1.5 -left-1.5 border border-white dark:border-gray-900 dark:bg-gray-700"></div>
                                    <time class="mb-1 text-sm font-normal leading-none text-gray-400 dark:text-gray-500">{{$v->packaging_relationships->packagings->packaging_v_n_s->created_at}}</time>
                                    <h3 class="text-lg font-semibold text-primary" >Nhập kho Việt Nam</h3>
                                </li>
                                @endif
                                @if(!empty($v->delivery_relationships))
                                <li class="mb-2 ml-4">
                                    <div class="absolute w-3 h-3 bg-gray-200 rounded-full mt-1.5 -left-1.5 border border-white dark:border-gray-900 dark:bg-gray-700"></div>
                                    <time class="mb-1 text-sm font-normal leading-none text-gray-400 dark:text-gray-500"> {{$v->delivery_relationships->deliveries->created_at}}</time>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Giao hàng <span class="text-red-600 font-bold">#{{$v->delivery_relationships->deliveries->code}}</span></h3>
                                </li>
                                @endif
                            </ol>

                        </div>

                    </div>
                </div>
                @endforeach
                @endif
            </div>

        </div>
    </div>
</main>
@endsection
@push('javascript')

@endpush
