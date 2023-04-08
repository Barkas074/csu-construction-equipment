<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;

class CatalogController extends Controller
{
    public function index(): View|\Illuminate\Foundation\Application|Factory|Application
    {
        $roots = DB::table('categories')->where('parent_id', 0)->get();
        return view('catalog.index', compact('roots'));
    }

    /**
     * @param $slug
     * @return View|\Illuminate\Foundation\Application|Factory|Application
     */
    public function category($slug): View|\Illuminate\Foundation\Application|Factory|Application
    {
        $category = Category::with('products')->where('slug', $slug)->first();
        return view('catalog.category', compact('category'));
    }

    /**
     * @param $slug
     * @return View|\Illuminate\Foundation\Application|Factory|Application
     */
    public function brand($slug): View|\Illuminate\Foundation\Application|Factory|Application
    {
        $brand = Brand::with('products')->where('slug', $slug)->first();
        return view('catalog.brand', compact('brand'));
    }

    /**
     * @param $slug
     * @return View|\Illuminate\Foundation\Application|Factory|Application
     */
    public function product($slug): View|\Illuminate\Foundation\Application|Factory|Application
    {
        $product = Product::with('category', 'brand')->where('slug', $slug)->first();
        return view('catalog.product', compact('product'));
    }
}
