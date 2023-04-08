<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ImageSaver;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryCatalogRequest;
use App\Models\Category;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;

class CategoryController extends Controller
{
    private ImageSaver $imageSaver;

    public function __construct(ImageSaver $imageSaver)
    {
        $this->imageSaver = $imageSaver;
    }

    /**
     * Display a listing of the resource.
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public function index(): \Illuminate\Foundation\Application|View|Factory|Application
    {
        $items = Category::all();
        return view('admin.category.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public function create(): \Illuminate\Foundation\Application|View|Factory|Application
    {
        $items = Category::all();
        return view('admin.category.create', compact('items'));
    }

    /**
     * Store a newly created resource in storage.
     * @param CategoryCatalogRequest $request
     * @return Application|\Illuminate\Foundation\Application|RedirectResponse|Redirector
     */
    public function store(CategoryCatalogRequest $request): \Illuminate\Foundation\Application|Redirector|RedirectResponse|Application
    {
        $data = $request->except(['_token', '_method']);
        $data['image'] = $this->imageSaver->upload($request, null, 'category');
        $category = Category::create($data);
        session()->flash('success', 'Новая категория успешно создана');
        return redirect(route('admin.category.show', ['category' => $category->id]));
    }

    /**
     * Display the specified resource.
     * @param Category $category
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public function show(Category $category): \Illuminate\Foundation\Application|View|Factory|Application
    {
        return view('admin.category.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param Category $category
     * @return View|\Illuminate\Foundation\Application|Factory|Application
     */
    public function edit(Category $category): View|\Illuminate\Foundation\Application|Factory|Application
    {
        $items = Category::all();
        return view('admin.category.edit', compact('category', 'items'));
    }

    /**
     * Update the specified resource in storage.
     * @param CategoryCatalogRequest $request
     * @param Category $category
     * @return Application|\Illuminate\Foundation\Application|RedirectResponse|Redirector
     */
    public function update(CategoryCatalogRequest $request, Category $category): \Illuminate\Foundation\Application|Redirector|RedirectResponse|Application
    {
        $data = $request->except(['_token', '_method']);
        $data['image'] = $this->imageSaver->upload($request, $category, 'category');
        $category->update($data);
        session()->flash('success', 'Категория была успешно изменена');
        return redirect(route('admin.category.show', ['category' => $category->id]));
    }

    /**
     * Remove the specified resource from storage.
     * @param Category $category
     * @return Application|\Illuminate\Foundation\Application|RedirectResponse|Redirector
     */
    public function destroy(Category $category): \Illuminate\Foundation\Application|Redirector|RedirectResponse|Application
    {
        if ($category->children) {
            $errors[] = 'Нельзя удалить категорию с дочерними категориями';
        }
        if ($category->products->count()) {
            $errors[] = 'Нельзя удалить категорию, которая содержит товары';
        }
        if (!empty($errors)) {
            return back()->withErrors($errors);
        }
        $category->delete();
        session()->flash('success', 'Категория каталога успешно удалена');
        return redirect(route('admin.category.index'));
    }
}
