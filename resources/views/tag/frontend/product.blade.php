@extends('homepage.layout.home')
@section('content')
{!!htmlBreadcrumb($detail->title)!!}
<main>
    <div class="grid md:grid-cols-12">
        @if(!$listTags->isEmpty())
        <div class="md:col-span-3 bg-[#F3F3F3]">
            <div class="p-5 md:p-[50px]">
                <nav class="list-type">
                    <h4 class="text-f18 border-b border-global pb-[5px] BoldC text-[#333333] uppercase font-bold" style="letter-spacing: 2px;line-height: 29px;">TAGS</h4>
                    <ul class="js_ul_ct flex flex-wrap mt-5">
                        @foreach($listTags as $item)
                        <li class="<?php if ($item->id == $detail->id) { ?>active<?php } ?>">
                            <a href="{{route('tagURL',['slug'=>$item->slug])}}" class="text-base hover:bg-global hover:text-white border float-left mr-2 mb-2 px-2 py-1" style="letter-spacing: 2px;line-height: 29px;">#{{$item->title}}</a>
                        </li>
                        @endforeach
                    </ul>
                </nav>
            </div>
        </div>
        @endif
        <div class="md:col-span-9 py-5 md:py-[50px] px-4 md:px-8">

            @if(!empty($detail->description))
            <div class="text-base mb-5" style="letter-spacing: 1px;">
                <?php echo $detail->description ?>
            </div>
            @endif
            @if($data)
            <div class="grid grid-cols-2 md:grid-cols-4 justify-center -mx-[5px] md:-mx-[10px]">
                @foreach($data as $key=>$item)
                <?php echo htmlItemProduct($key, $item->products, 'px-[5px] md:px-[10px]'); ?>
                @endforeach
            </div>
            <div class="my-10 flex justify-center">
                <?php echo $data->links() ?>
            </div>
            @endif
        </div>
    </div>
</main>

@endsection

@push('css')
<style>
    .js_ul_ct li.active a {
        font-weight: bold;
    }

    .js_ul_ct li {
        margin-bottom: 5px;
    }
</style>

@endpush