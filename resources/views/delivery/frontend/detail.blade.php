@extends('homepage.layout.home')
@section('content')
    <main class="my-5">
        <div class="container mx-auto px-4 space-y-5">
            <h1 class="font-bold uppercase text-2xl"><a href="{{route('deliveryHome.search')}}">Chi tiết đơn
                    giao {{$detail->code}}</a></h1>
            <div class="flex flex-col">
                <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8">
                        <div class="overflow-hidden space-y-5">
                            <table class="min-w-full text-center text-sm font-light">
                                <thead
                                    class="border-b bg-neutral-800 font-medium text-white dark:border-neutral-500 dark:bg-neutral-900">
                                <tr>
                                    <th scope="col" class=" py-4">STT</th>
                                    <th scope="col" class=" py-4">Mã vận đơn 提單代碼</th>
                                    <th scope="col" class=" py-4">CÂN NẶNG(KG) 重量</th>
                                    <th scope="col" class=" py-4">Ghi chú 筆記</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(!empty($detail->advanced))
                                    <?php
                                    $products = $detail->delivery_relationships;
                                    if (!empty($products) && count($products) > 0) {
                                    foreach ($products as $key => $val) { ?>
                                    <tr class="border-b dark:border-neutral-500">
                                        <td class="whitespace-nowrap  py-4"> {{$key+1}}</td>
                                        <td>
                                            {{$val->code}}
                                        </td>
                                        <td>
                                            {{$val->weight}}
                                        </td>
                                        <td>
                                            {{$val->note}}
                                        </td>
                                    </tr>
                                    <?php } ?>
                                    <?php } ?>
                                @else
                                    <?php
                                    $products = json_decode($detail->products, TRUE);
                                    if (isset($products) && is_array($products) && count($products)) { ?>
                                    <?php foreach ($products['code'] as $key => $val) {
                                    ?>
                                    <tr class="border-b dark:border-neutral-500">
                                        <td class="whitespace-nowrap  py-4"> {{$key+1}}</td>
                                        <td>
                                            {{$products['code'][$key]}}
                                        </td>
                                        <td>
                                            {{ $products['weight'][$key]}}
                                        </td>
                                        <td>
                                            {{$products['note'][$key]}}
                                        </td>
                                    </tr>
                                    <?php } ?>
                                    <?php } ?>
                                @endif
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td class="text-right font-bold py-4" colspan="2">
                                        {{trans('admin.total_weight')}}
                                    </td>
                                    <td class="text-danger font-bold py-4" id="tongsocan">
                                        {{$detail->weight}}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-right font-bold py-4" colspan="2">
                                        Đơn giá
                                    </td>
                                    <td>
                                        {{number_format($detail->fee,'0',',','.')}}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-right font-bold py-4" colspan="2">
                                        Phụ phí
                                    </td>
                                    <td>
                                        {{number_format($detail->shipping,'0',',','.')}}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-right font-bold py-4" colspan="2">
                                        Thành tiền
                                    </td>

                                    <td class="text-danger font-bold">
                                        {{number_format($detail->price,'0',',','.')}}

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
@endsection
