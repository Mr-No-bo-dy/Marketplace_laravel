<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Category;
use App\Models\Admin\Subcategory;
use App\Models\Site\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SubcategoryController extends Controller
{
    /**
     * Display a listing of the Subcategories.
     */
    public function index()
    {
        $subcategoryModel = new Subcategory();

        $subcategories = $subcategoryModel->readAllSubcategories();

        return view('admin.subcategories.index', compact('subcategories'));
    }

    /**
     * Display Subcategory creation form
     */
    public function create()
    {
        $categoryModel = new Category();

        $categories = $categoryModel->readCategoriesNames();

        return view('admin.subcategories.create', compact('categories'));
    }

    /**
     * Create Subcategory
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        if ($request->has('createSubcategory')) {
            $subcategoryModel = new Subcategory();

            $setSubcategoryData = [
                'id_category' => $request->post('id_category'),
                'name' => $request->post('name'),
                'description' => $request->post('description'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            $subcategoryModel->storeSubcategory($setSubcategoryData);

        }

        return redirect()->route('admin.subcategory');
    }

    /**
     * Display Subcategory update form
     *
     * @param int $idSubcategory
     */
    public function edit(int $idSubcategory)
    {
        $categoryModel = new Category();
        $subcategoryModel = new Subcategory();

        $categories = $categoryModel->readCategoriesNames();
        $subcategory = $subcategoryModel->readSubcategory($idSubcategory);

        return view('admin.subcategories.update', compact('categories', 'subcategory'));
    }

    /**
     * Update Subcategory
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(Request $request): RedirectResponse
    {
        if ($request->has('updateSubcategory')) {
            $subcategoryModel = new Subcategory();

            $setSubcategoryData = [
                'id_category' => $request->post('id_category'),
                'name' => $request->post('name'),
                'description' => $request->post('description'),
            ];
            $idSubcategory = $request->post('id_subcategory');
            $subcategoryModel->updateSubcategory($idSubcategory, $setSubcategoryData);
        }

        return redirect()->route('admin.subcategory');
    }

    /**
     * Delete Subcategory & all its Products
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function destroy(Request $request): RedirectResponse
    {
        if ($request->has('deleteSubcategory')) {
            $subcategoryModel = new Subcategory();
            $productModel = new Product();

            $idSubcategory = $request->post('id_subcategory');
            $productModel->deleteSubcategoryProducts($idSubcategory);
            $subcategoryModel->deleteSubcategory($idSubcategory);
        }

        return back();
    }

    /**
     * Restore Subcategory & all its Products
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function restore(Request $request): RedirectResponse
    {
        if ($request->has('restoreSubcategory')) {
            $subcategoryModel = new Subcategory();
            $productModel = new Product();

            $idSubcategory = $request->post('id_subcategory');
            $productModel->restoreSubcategoryProducts($idSubcategory);
            $subcategoryModel->restoreSubcategory($idSubcategory);
        }

        return back();
    }
}
