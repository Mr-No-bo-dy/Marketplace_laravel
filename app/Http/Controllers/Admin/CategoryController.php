<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Category;
use App\Models\Admin\Subcategory;
use App\Models\Site\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the Categories.
     */
    public function index()
    {
        $categoryModel = new Category();

        $categories = $categoryModel->readAllCategories();

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Display Category creation form
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Create Category
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        if ($request->has('createCategory')) {
            $categoryModel = new Category();

            $setCategoryData = [
                'name' => ucfirst($request->post('name')),
                'description' => $request->post('description'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            $categoryModel->storeCategory($setCategoryData);
        }

        return redirect()->route('admin.category');
    }

    /**
     * Display Category update form
     *
     * @param int $idCategory
     */
    public function edit(int $idCategory)
    {
        $categoryModel = new Category();

        $category = $categoryModel->readCategory($idCategory);

        return view('admin.categories.update', compact('category'));
    }

    /**
     * Update Category
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(Request $request): RedirectResponse
    {
        if ($request->has('updateCategory')) {
            $categoryModel = new Category();

            $setCategoryData = [
                'name' => $request->post('name'),
                'description' => $request->post('description'),
            ];
            $idCategory = $request->post('id_category');
            $categoryModel->updateCategory($idCategory, $setCategoryData);
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
            $categoryModel = new Category();
            $subcategoryModel = new Subcategory();
            $productModel = new Product();

            $idCategory = $request->post('id_category');
            $productModel->deleteCategoryProducts($idCategory);
            $subcategoryModel->deleteCategorySubcategories($idCategory);
            $categoryModel->deleteCategory($idCategory);
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
            $categoryModel = new Category();
            $subcategoryModel = new Subcategory();
            $productModel = new Product();

            $idCategory = $request->post('id_category');
            $productModel->restoreCategoryProducts($idCategory);
            $subcategoryModel->restoreCategorySubcategories($idCategory);
            $categoryModel->restoreCategory($idCategory);
        }

        return back();
    }
}
