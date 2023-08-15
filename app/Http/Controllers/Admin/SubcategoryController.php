<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Category;
use App\Models\Admin\Subcategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SubcategoryController extends Controller
{
    /**
     * Display a listing of the Subcategories.
     */
    public function index()
    {
        $subcategories = Subcategory::all();

        return view('admin.subcategories.index', compact('subcategories'));
    }

    /**
     * Display Subcategory creation form
     */
    public function create()
    {
        $categories = Category::all(['id_category', 'name']);

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
        $subcategoryModel = new Subcategory();

        $postData = $request->post();
        $setSubcategoryData = [
            'id_category' => $postData['id_category'],
            'name' => ucfirst($postData['name']),
            'description' => $postData['description'],
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        $subcategoryModel->storeSubcategory($setSubcategoryData);

        return redirect()->route('admin.subcategory');
    }

    /**
     * Display Subcategory update form
     */
    public function edit($idSubcategory)
    {
        $categories = Category::all(['id_category', 'name']);
        $subcategory = Subcategory::find($idSubcategory);

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
        $subcategoryModel = new Subcategory();

        $postData = $request->post();
        $setCategoryData = [
            'id_category' => $postData['id_category'],
            'name' => ucfirst($postData['name']),
            'description' => $postData['description'],
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        $idSubcategory = $request->post('id_subcategory');
        $subcategoryModel->updateSubCategory($idSubcategory, $setCategoryData);

        return redirect()->route('admin.subcategory');
    }

    /**
     * Delete Subcategory
     */
    public function destroy(Request $request): RedirectResponse
    {
        $subcategoryModel = new Subcategory();

        $idSubcategory = $request->post('id_subcategory');
        $subcategoryModel->deleteSubCategory($idSubcategory);

        return redirect()->route('admin.subcategory');
    }
}
