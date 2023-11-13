<?php

namespace App\Http\Controllers\page\frontend;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Components\System;
use App\Models\Orders_item;
use Carbon\Carbon;
use Validator;

class PageController extends Controller
{
    protected $system;
    public function __construct()
    {
        $this->system = new System();
    }
    public function aboutUs()
    {
        //page: HOME
        $page = Page::where(['alanguage' => config('app.locale'), 'page' => 'aboutus', 'publish' => 0])
            ->select('id', 'image', 'title', 'description', 'meta_title', 'meta_description')
            ->first();

        $seo['canonical'] = url('/');
        $seo['meta_title'] = !empty($page['meta_title']) ? $page['meta_title'] : $page['title'];
        $seo['meta_description'] = !empty($page['meta_description']) ? $page['meta_description'] : '';
        $seo['meta_image'] = !empty($page['image']) ? url($page['image']) : '';
        $fcSystem = $this->system->fcSystem();
        return view('page.frontend.aboutus', compact('seo', 'page', 'fcSystem'));
    }
    public function agency()
    {
        //page: HOME
        $page = Page::where(['alanguage' => config('app.locale'), 'page' => 'agency', 'publish' => 0])
            ->select('id', 'image', 'title', 'description', 'meta_title', 'meta_description')
            ->with('fields')
            ->first();
        $fields = [];
        if (!empty($page->fields)) {
            foreach ($page->fields as $item) {
                $fields[$item->meta_key] = $item->meta_value;
            }
        }

        $seo['canonical'] = url('/');
        $seo['meta_title'] = !empty($page['meta_title']) ? $page['meta_title'] : $page['title'];
        $seo['meta_description'] = !empty($page['meta_description']) ? $page['meta_description'] : '';
        $seo['meta_image'] = !empty($page['image']) ? url($page['image']) : '';
        $fcSystem = $this->system->fcSystem();
        return view('page.frontend.agency', compact('seo', 'page', 'fcSystem', 'fields'));
    }
    public function reviews()
    {
        //page: HOME
        $page = Page::where(['alanguage' => config('app.locale'), 'page' => 'reviews', 'publish' => 0])
            ->select('id', 'image', 'title', 'description', 'meta_title', 'meta_description')
            ->with('fields')
            ->first();
        $fields = [];
        if (!empty($page->fields)) {
            foreach ($page->fields as $item) {
                $fields[$item->meta_key] = $item->meta_value;
            }
        }
        $data = \App\Models\Comment::where(['parentid' => 0, 'module' => 'products', 'publish' => 0])
            ->orderBy('id', 'desc')
            ->paginate(30);
        if (!empty($data)) {
            foreach ($data as $key => $item) {
                $checkOrder = \App\Models\Orders_item::where(['product_id' => $item->module_id, 'customer_id' => $item->customerid])->first();
                $data[$key]['checkOrder'] = !empty($checkOrder) ? 1 : 0;
            }
        }
        $ratings = \App\Models\Comment::where(['parentid' => 0, 'module' => 'products'])->sum('rating');
        $seo['canonical'] = url('/');
        $seo['meta_title'] = !empty($page['meta_title']) ? $page['meta_title'] : $page['title'];
        $seo['meta_description'] = !empty($page['meta_description']) ? $page['meta_description'] : '';
        $seo['meta_image'] = !empty($page['image']) ? url($page['image']) : '';
        $fcSystem = $this->system->fcSystem();
        return view('page.frontend.reviews', compact('seo', 'page', 'fcSystem', 'fields', 'data', 'ratings'));
    }
}
