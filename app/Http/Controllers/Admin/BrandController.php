<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ImageSaver;
use App\Http\Controllers\Controller;
use App\Http\Requests\BrandCatalogRequest;
use App\Models\Brand;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;

class BrandController extends Controller
{
    private ImageSaver $imageSaver;

    public function __construct(ImageSaver $imageSaver)
    {
        $this->imageSaver = $imageSaver;
    }

    /**
     * Display a listing of the resource.
     * @return View|Application|Factory|\Illuminate\Contracts\Foundation\Application
     */
    public function index(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $brands = Brand::all();
        return view('admin.brand.index', compact('brands'));
    }

    /**
     * Show the form for creating a new resource.
     * @return View|Application|Factory|\Illuminate\Contracts\Foundation\Application
     */
    public function create(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin.brand.create');
    }

    /**
     * Store a newly created resource in storage.
     * @param BrandCatalogRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|Application|RedirectResponse|Redirector
     */
    public function store(BrandCatalogRequest $request): Application|Redirector|RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $data = $request->except(['_token', '_method']);
        $data['image'] = $this->imageSaver->upload($request, null, 'brand');
        $brand = Brand::create($data);
        session()->flash('success', 'Новый бренд успешно создан');
        return redirect(route('admin.brand.show', ['brand' => $brand->id]));
    }

    /**
     * Display the specified resource.
     * @param Brand $brand
     * @return \Illuminate\Contracts\Foundation\Application|Factory|View|Application
     */
    public function show(Brand $brand): Application|View|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin.brand.show', compact('brand'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param Brand $brand
     * @return \Illuminate\Contracts\Foundation\Application|Factory|View|Application
     */
    public function edit(Brand $brand): Application|View|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin.brand.edit', compact('brand'));
    }

    /**
     * Update the specified resource in storage.
     * @param BrandCatalogRequest $request
     * @param Brand $brand
     * @return \Illuminate\Contracts\Foundation\Application|Application|RedirectResponse|Redirector
     */
    public function update(BrandCatalogRequest $request, Brand $brand): Application|Redirector|RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $data = $request->except(['_token', '_method']);
        $data['image'] = $this->imageSaver->upload($request, $brand, 'brand');
        $brand->update($data);
        session()->flash('success', 'Бренд был успешно отредактирован');
        return redirect(route('admin.brand.show', ['brand' => $brand->id]));
    }

    /**
     * Remove the specified resource from storage.
     * @param Brand $brand
     * @return \Illuminate\Contracts\Foundation\Application|Application|RedirectResponse|Redirector
     */
    public function destroy(Brand $brand): Application|Redirector|\Illuminate\Contracts\Foundation\Application|RedirectResponse
    {
        if ($brand->products->count()) {
            return back()->withErrors('Нельзя удалить бренд, у которого есть товары');
        }
        $this->imageSaver->remove($brand, 'brand');
        $brand->delete();
        session()->flash('success', 'Бренд каталога успешно удален');
        return redirect(route('admin.brand.index'));
    }
}
