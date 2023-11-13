@extends('homepage.layout.home')
@section('content')
<main class="my-5">
    <div class="container mx-auto px-4 space-y-5">
        <h1 class="font-bold uppercase text-2xl">TÌM KIẾM BAO</h1>
        <form class="w-full flex flex-col md:flex-row justify-between space-y-4 md:space-y-0">
            <div class="flex-1 grid grid-cols-1 md:grid-cols-3 gap-4">
                <input type="text" name="keyword" placeholder="Mã bao 包号" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="{{request()->get('keyword')}}">
                <button type="submit" class="bg-red-600 rounded-lg w-10 h-[42px] flex justify-center items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-white">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                </button>
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
                                    <td class="bg-sky-500 py-4 font-bold">{{ $count}}</td>
                                    <td class="bg-red-600 py-4">Tổng cân</td>
                                    <td class="bg-red-600 py-4 font-bold">{{number_format((float)$total, 2, '.', '')}}</td>
                                </tr>
                                <tr>
                                    <th scope="col" class=" py-4">NGÀY 日期 </th>
                                    <th scope="col" class=" py-4">MÃ BAO 包号 </th>
                                    <th scope="col" class=" py-4">MÃ VẬN ĐƠN 运单号 </th>
                                    <th scope="col" class=" py-4">CÂN NẶNG(KG) 重量 </th>
                                    <th scope="col" class=" py-4">MÃ KHÁCH 客户码 </th>
                                    <th scope="col" class=" py-4">TÊN KHÁCH HÀNG名字</th>
                                    <th scope="col" class=" py-4">Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($data) && count($data) > 0)
                                @foreach($data as $v)
                                <tr class="border-b dark:border-neutral-500">
                                    <td class="whitespace-nowrap  py-4">{{$v->created_at}}</td>
                                    <td class="whitespace-nowrap  py-4">{{$v->code}}</td>
                                    <td class="whitespace-nowrap  py-4">
                                        <a href="{{route('bag.detail',['id' => $v->id])}}" class="text-primary font-bold" style="text-decoration: underline;">Mã vận đơn</a>
                                    </td>
                                    <td class="whitespace-nowrap  py-4"> {{$v->value_weight}}</td>
                                    <td class="whitespace-nowrap  py-4"> {{!empty($v->customer) ?$v->customer->code:''}}</td>
                                    <td class="whitespace-nowrap  py-4"> {{!empty($v->customer) ?$v->customer->name:''}}</td>
                                    <td class="whitespace-nowrap  py-4">
                                        <?php
                                        if(!empty($v->packaging_v_n_s)){
                                            $status_color = '#dc2626';
                                            $status = 'Đã về VN';
                                        }elseif(!empty($v->packaging_trucks)){
                                            $status_color = '#2563eb';
                                            $status = 'Đang vận chuyển về VN';
                                        }else{
                                            $status_color = '#db2777';
                                            $status = 'Nhập kho TQ';
                                        }
                                        ?>

                                        <span class="text-white rounded-md p-2" style="background-color: {{$status_color}}">{{$status}}</span>

                                    </td>
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                        {{!empty($data)?$data->links():''}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
