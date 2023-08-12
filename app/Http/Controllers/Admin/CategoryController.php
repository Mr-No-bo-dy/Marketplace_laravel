<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
   /**
   * Display a listing of the Categories.
   */
   public function index()
   {
      $categories = Category::all();

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
      $categoryModel = new Category();

      $postData = $request->post();
      $setCategoryData = [
         'name' => ucfirst($postData['name']),
         'description' => $postData['description'],
         'created_at' => date('Y-m-d H:i:s'),
         'updated_at' => date('Y-m-d H:i:s'),
      ];
      $categoryModel->storeCategory($setCategoryData);

      return redirect()->route('admin.category');
   }

   /**
   * Display Category update form
   */
   public function edit($idCategory)
   {
      $category = Category::find($idCategory);

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
      $categoryModel = new Category();

      $postData = $request->post();
      $setCategoryData = [
         'name' => ucfirst($postData['name']),
         'description' => $postData['description'],
         'updated_at' => date('Y-m-d H:i:s'),
      ];
      $idCategory = $request->post('id_category');
      $categoryModel->updateCategory($idCategory, $setCategoryData);

      return redirect()->route('admin.category');
   }

   /**
   * Delete Category
   */
   public function destroy(Request $request): RedirectResponse
   {
      $categoryModel = new Category();

      $idCategory = $request->post('id_category');
      $categoryModel->deleteCategory($idCategory);

      return redirect()->route('admin.category');
   }
}
