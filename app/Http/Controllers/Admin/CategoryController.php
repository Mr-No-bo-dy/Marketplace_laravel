<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Admin\Category;
use App\Models\Admin\Subcategory;
use App\Models\Site\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the Categories.
     *
     * @return View
     */
    public function index(): View
    {
        $categories = Category::withTrashed()->get();

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Display Category creation form
     *
     * @return View
     */
    public function create(): View
    {
        return view('admin.categories.create');
    }

    /**
     * Create Category
     *
     * @param CategoryRequest $request
     * @return RedirectResponse
     */
    public function store(CategoryRequest $request): RedirectResponse
    {
        Category::create($request->validated());

        return redirect()->route('admin.category');
    }

    /**
     * Display Category update form
     *
     * @param int $idCategory
     * @return View
     */
    public function edit(int $idCategory): View
    {
        $category = Category::findOrFail($idCategory);

        return view('admin.categories.update', compact('category'));
    }

    /**
     * Update Category
     *
     * @param CategoryRequest $request
     * @return RedirectResponse
     */
    public function update(CategoryRequest $request): RedirectResponse
    {
        $idCategory = $request->validate(['id_category' => ['bail', 'integer', 'min: 1', 'max:9223372036854775807']])['id_category'];
        $category = Category::findOrFail($idCategory);
        $category->fill($request->validated());
        if ($category->isDirty()) {
            $category->save();
        }

        return redirect()->route('admin.category');
    }

    /**
     * Delete Category, all its Subcategories & Products
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function destroy(Request $request): RedirectResponse
    {
        if ($request->has('deleteCategory')) {
            $subcategoryModel = new Subcategory();
            $productModel = new Product();

            $idCategory = $request->validate(['id_category' => ['bail', 'integer', 'min: 1', 'max:9223372036854775807']])['id_category'];
            $productModel->deleteCategoryProducts($idCategory);
            $subcategoryModel->deleteCategorySubcategories($idCategory);
            Category::findOrFail($idCategory)->delete();
        }

        return back();
    }

    /**
     * Restore Category, all its Subcategories & Products
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function restore(Request $request): RedirectResponse
    {
        if ($request->has('restoreCategory')) {
            $subcategoryModel = new Subcategory();
            $productModel = new Product();

            $idCategory = $request->validate(['id_category' => ['bail', 'integer', 'min: 1', 'max:9223372036854775807']])['id_category'];
            $productModel->restoreCategoryProducts($idCategory);
            $subcategoryModel->restoreCategorySubcategories($idCategory);
            Category::onlyTrashed()->findOrFail($idCategory)->restore();
        }

        return back();
    }
}
