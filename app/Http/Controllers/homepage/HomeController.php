<?php

namespace App\Http\Controllers\homepage;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Components\System;
use App\Models\Warehouse;
use Cache;
use Illuminate\Support\Arr;
use Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\WarehouseExport;
use App\Models\Packaging;
use App\Models\PackagingRelationships;
use App\Exports\PackagingsFrontendExport;

class HomeController extends Controller
{
    protected $system;
    public function __construct()
    {
        $this->system = new System();
    }
    public function index()
    {
        $fcSystem = $this->system->fcSystem();
        $slideHome = \App\Models\CategorySlide::select('title', 'id')->where(['alanguage' => config('app.locale'), 'keyword' => 'banner-home'])->with('slides')->first();
        $slideVip = \App\Models\CategorySlide::select('title', 'id')->where(['alanguage' => config('app.locale'), 'keyword' => 'vip'])->with('slides')->first();
        $slideServices = \App\Models\CategorySlide::select('title', 'id')->where(['alanguage' => config('app.locale'), 'keyword' => 'services'])->with('slides')->first();
        $slideClient = \App\Models\CategorySlide::select('title', 'id')->where(['alanguage' => config('app.locale'), 'keyword' => 'client'])->with('slides')->first();
        $ishomeCategory = \App\Models\CategoryArticle::select('id', 'title', 'slug')
            ->where(['alanguage' => config('app.locale'), 'publish' => 0, 'id' => 2])
            ->with('posts')
            ->first();

        $page = Page::where(['alanguage' => config('app.locale'), 'page' => 'index', 'publish' => 0])->select('id', 'title', 'image', 'meta_title', 'meta_description')->first();
        $seo['canonical'] = url('/');
        $seo['meta_title'] = !empty($page['meta_title']) ? $page['meta_title'] : $page['title'];
        $seo['meta_description'] = !empty($page['meta_description']) ? $page['meta_description'] : '';
        $seo['meta_image'] = !empty($page['image']) ? url($page['image']) : '';
        return view('homepage.home.index', compact('page', 'seo', 'fcSystem', 'slideHome', 'slideVip', 'slideServices', 'slideClient', 'ishomeCategory'));
    }


    public function sitemap()
    {
        /*
        $Tags = \App\Models\Tag::select('id', 'slug', 'created_at')->where('alanguage', config('app.locale'))->where('publish', 0)->get();
        $Brands = \App\Models\Brand::select('id', 'slug', 'created_at')->where('alanguage', config('app.locale'))->where('publish', 0)->get(); */
        $router = DB::table('router')->select('slug', 'created_at')->get();
        return response()->view('homepage.home.sitemap', compact('router'))->header('Content-Type', 'text/xml');
    }
}
