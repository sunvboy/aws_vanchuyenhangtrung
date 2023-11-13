@extends('homepage.layout.home')
@section('content')
<main>
    {!!htmlBreadcrumb($catalogues->title,$breadcrumb)!!}
    <section class="py-[30px]" id="scrollTop">
        <div class="container px-4 mx-auto" id="loadHtmlAjax">
            <div class="grid grid-cols-1 md:grid-cols-12 -mx-[15px]">
                @include('article.frontend.aside')
                <div class="md:col-span-9 px-[15px] order-0 md:order-1 space-y-5">
                    <div class="space-y-2">
                        <h1 class="font-bold text-xl leading-[1.1]">{{$detail->title}}</h1>
                        <div class="flex items-center space-x-3 text-sm text-[#999]">
                            <span>
                                By <a href="javascript:void(0)">{{$detail->user->name}}</a>
                            </span>
                            <span class="flex items-center space-x-1">
                                <i class="fa fa-calendar" aria-hidden="true"></i>
                                <span>
                                    {{$detail->created_at}}
                                </span>
                            </span>
                            <span class="flex items-center space-x-1">
                                <i class="fa fa-eye" aria-hidden="true"></i>
                                <span>
                                    {{$detail->viewed}} lượt xem
                                </span>
                            </span>
                        </div>
                        <div class="box_content">
                            <?php echo $detail->content ?>
                        </div>
                        <div class="flex items-center justify-between space-x-8">
                            <div class="w-1/2">
                                @if(!empty($previous))
                                <a href="{{route('routerURL',['slug' => $previous->slug])}}" class="flex items-center space-x-2 hover:text-global">
                                    <span class="border w-[35px] h-[35px] flex items-center justify-center text-lg">
                                        <i class="fa fa-angle-left" aria-hidden="true"></i>
                                    </span>
                                    <span class="flex-1 clamp font-medium">{{$previous->title}}</span>
                                </a>
                                @endif
                            </div>
                            <div class="w-1/2">
                                @if(!empty($next))
                                <a href="{{route('routerURL',['slug' => $next->slug])}}" class="flex items-center space-x-2 hover:text-global">
                                    <span class="flex-1 clamp font-medium">{{$next->title}}</span>
                                    <span class="border w-[35px] h-[35px] flex items-center justify-center text-lg">
                                        <i class="fa fa-angle-right" aria-hidden="true"></i>
                                    </span>
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    @if(!$sameArticle->isEmpty())
                    <div>
                        <h2 class="font-bold text-xl mb-2">Bài viết liên quan</h2>
                        <ul class="list-disc pl-5">
                            @foreach($sameArticle as $k=>$v)
                            <li><a class="hover:text-global" href="{{route('routerURL',['slug' => $v->slug])}}">{{$v->title}}</a></li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>
            </div>

        </div>
    </section>
</main>
@endsection