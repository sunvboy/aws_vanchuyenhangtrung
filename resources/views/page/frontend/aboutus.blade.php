@extends('homepage.layout.home')
@section('content')
    <main>
        {!!htmlBreadcrumb($page->title)!!}
        <section class="py-[30px]" id="scrollTop">
            <div class="container px-4 mx-auto" id="loadHtmlAjax">
                <div class="grid grid-cols-1 md:grid-cols-12 -mx-[15px]">
                    @include('article.frontend.aside')
                    <div class="md:col-span-9 px-[15px] order-0 md:order-1 space-y-5">
                        <div class="space-y-2">
                            <h1 class="font-bold text-xl leading-[1.1]">{{$page->title}}</h1>

                            <div class="box_content">
                                <?php echo $page->description ?>
                            </div>

                        </div>

                    </div>
                </div>

            </div>
        </section>
    </main>
@endsection
