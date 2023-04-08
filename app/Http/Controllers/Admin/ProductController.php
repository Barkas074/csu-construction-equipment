<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ImageSaver;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductCatalogRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;

class ProductController extends Controller
{
    private ImageSaver $imageSaver;

    public function __construct(ImageSaver $imageSaver)
    {
        $this->imageSaver = $imageSaver;
    }

    /**
     * Display a listing of the resource.
     * @return View|\Illuminate\Foundation\Application|Factory|Application
     */
    public function index(): View|\Illuminate\Foundation\Application|Factory|Application
    {
        // корневые категории для возможности навигации
        $roots = Category::where('parent_id', 0)->get();
        $products = Product::paginate(5);
        return view('admin.product.index', compact('products', 'roots'));
    }

    /**
     * Show the form for creating a new resource.
     * @return View|\Illuminate\Foundation\Application|Factory|Application
     */
    public function create(): View|\Illuminate\Foundation\Application|Factory|Application
    {
        // все категории для возможности выбора родителя
        $items = Category::all();
        // все бренды для возмозжности выбора подходящего
        $brands = Brand::all();
        return view('admin.product.create', compact('items', 'brands'));
    }

    /**
     * Store a newly created resource in storage.
     * @param ProductCatalogRequest $request
     * @return \Illuminate\Foundation\Application|Redirector|RedirectResponse|Application
     */
    public function store(ProductCatalogRequest $request): \Illuminate\Foundation\Application|Redirector|RedirectResponse|Application
    {
        $data = $request->except(['_token', '_method']);
        $data['image'] = $this->imageSaver->upload($request, null, 'product');
        $product = Product::create($data);
        session()->flash('Новый товар успешно создан');
        return redirect(route('admin.product.show', ['product' => $product->id]));
    }

    /**
     * Display the specified resource.
     * @param Product $product
     * @return View|\Illuminate\Foundation\Application|Factory|Application
     */
    public function show(Product $product): View|\Illuminate\Foundation\Application|Factory|Application
    {
        return view('admin.product.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param Product $product
     * @return View|\Illuminate\Foundation\Application|Factory|Application
     */
    public function edit(Product $product): View|\Illuminate\Foundation\Application|Factory|Application
    {
        // все категории для возможности выбора родителя
        $items = Category::all();
        // все бренды для возмозжности выбора подходящего
        $brands = Brand::all();
        return view('admin.product.edit', compact('product', 'items', 'brands'));
    }

    /**
     * Update the specified resource in storage.
     * @param ProductCatalogRequest $request
     * @param Product $product
     * @return \Illuminate\Foundation\Application|Redirector|RedirectResponse|Application
     */
    public function update(ProductCatalogRequest $request, Product $product): \Illuminate\Foundation\Application|Redirector|RedirectResponse|Application
    {
        $data = $request->except(['_token', '_method']);
        $data['image'] = $this->imageSaver->upload($request, $product, 'product');
        $product->update($data);
        session()->flash('Товар был успешно обновлен');
        return redirect(route('admin.product.show', ['product' => $product->id]));
    }

    /**
     * Remove the specified resource from storage.
     * @param Product $product
     * @return \Illuminate\Foundation\Application|Redirector|RedirectResponse|Application
     */
    public function destroy(Product $product): \Illuminate\Foundation\Application|Redirector|RedirectResponse|Application
    {
        $this->imageSaver->remove($product, 'product');
        $product->delete();
        session()->flash('Товар каталога успешно удален');
        return redirect(route('admin.product.index'));
    }

    /**
     * @param Category $category
     * @return View|\Illuminate\Foundation\Application|Factory|Application
     */
    public function category(Category $category): View|\Illuminate\Foundation\Application|Factory|Application
    {
        $products = $category->products()->paginate(5);
        return view('admin.product.category', compact('category', 'products'));
    }
}
