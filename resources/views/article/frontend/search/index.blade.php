@extends('homepage.layout.home')
@section('content')
{!!htmlBreadcrumb($seo['meta_title'])!!}
<main class="">
    <section class="py-[30px]" id="scrollTop">
        <div class="container px-4" id="loadHtmlAjax">
            <div class="grid grid-cols-1 md:grid-cols-12 -mx-[15px]">
                @include('article.frontend.aside')
                <div class="md:col-span-9 px-[15px] order-0 md:order-1">
                    @include('article.frontend.category.data')
                </div>
            </div>
        </div>
    </section>
</main>
@endsection