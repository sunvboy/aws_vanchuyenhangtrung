<?php

namespace App\Http\Controllers\article\frontend;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\CategoryArticle;
use Illuminate\Http\Request;
use App\Components\System;
use Cache;

class CategoryController extends Controller
{
    protected $paginate = 18;
    protected $system;
    public function __construct()
    {
        $this->system = new System();
    }
    public function index($slug = "", Request $request)
    {
        $segments = request()->segments();
        $slug = end($segments);
        $detail = CategoryArticle::select('id', 'slug', 'title', 'description', 'meta_description', 'meta_title', 'publish', 'lft', 'image', 'banner', 'ishome', 'highlight', 'isaside', 'isfooter', 'parentid')
            ->with('children')
            ->where('alanguage', config('app.locale'))
            ->where('publish', 0)
            ->where('slug', $slug)
            ->first();
        if (!isset($detail)) {
            return redirect()->route('homepage.index');
        }
        $data = \App\Models\CataloguesRelationship::where(['catalogueid' => $detail->id, 'module' => 'articles', 'articles.publish' => 0])
            ->join('articles', 'articles.id', '=', 'catalogues_relationships.moduleid')
            // ->with('tagsArticle')
            ->orderBy('articles.id', 'desc')
            ->paginate($this->paginate);
        // breadcrumb
        $breadcrumb = CategoryArticle::select('title', 'slug')->where('alanguage', config('app.locale'))->where('lft', '<=', $detail->lft)->where('rgt', '>=', $detail->lft)->orderBy('lft', 'ASC')->orderBy('order', 'ASC')->get();
        $seo['canonical'] = route('routerURL', ['slug' => $slug]);
        $seo['meta_title'] =  !empty($detail['meta_title']) ? $detail['meta_title'] : $detail['title'];
        $seo['meta_description'] = !empty($detail['meta_description']) ? $detail['meta_description'] : cutnchar(strip_tags($detail->description));
        $seo['meta_image'] = $detail['image'];
        $fcSystem = $this->system->fcSystem();
        return view('article.frontend.category.index', compact('fcSystem', 'detail', 'seo', 'data', 'breadcrumb'));
    }
    public function search(Request $request)
    {
        $keyword = $request->keyword;
        $month = $request->month;
        $sort = '';
        if (!empty($_GET['sort'])) {
            $sort = $_GET['sort'];
        }
        $data =  Article::select('id', 'title', 'description', 'image', 'slug', 'userid_created', 'created_at')->where(['alanguage' => config('app.locale'), 'publish' => 0]);
        if (!empty($keyword)) {
            $data =  $data->where('title', 'like', '%' . $keyword . '%');
        }
        if (!empty($month)) {
            $data =  $data->whereMonth('created_at', $month);
        }
        $data =  $data->orderBy('order', 'asc')->orderBy('id', 'desc');
        $data =  $data->paginate($this->paginate);
        if (is($keyword)) {
            $data->appends(['keyword' => $keyword]);
        }
        $seo['canonical'] = $request->url();
        $seo['meta_title'] =  "Tìm kiếm " . $keyword;
        $seo['meta_description'] = '';
        $seo['meta_image'] = '';
        $fcSystem = $this->system->fcSystem();
        return view('article.frontend.search.index', compact('fcSystem', 'seo', 'data'));
    }
    public function ajaxPagination(Request $request)
    {
        $id = $request->id;
        $data = \App\Models\CataloguesRelationship::where(['catalogueid' => $id, 'module' => 'articles', 'articles.publish' => 0])
            ->join('articles', 'articles.id', '=', 'catalogues_relationships.moduleid')
            ->orderBy('articles.id', 'desc')
            ->paginate($this->paginate);
        return view('article.frontend.category.data', compact('data'))->render();
    }
}
