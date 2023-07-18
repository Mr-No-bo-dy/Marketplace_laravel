<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
   /**
    * Display a listing of the Categories.
    */
   public function index()
   {
      $categoryModel = new Category();

      $categories = $categoryModel->all();

      return view('admin.categories.index', ['categories' => $categories]);
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
    * @param object \Illuminate\Http\Request $request
    */
   public function store(Request $request)
   {
      $categoryModel = new Category();

      $postData = $request->post();
      $setCategoryData = [
         'name' => ucfirst($postData['name']),
         'description' => $postData['description'],
         'created_at' => date('y.m.d H:i:s', strtotime('+3 hour')),
         'updated_at' => date('y.m.d H:i:s', strtotime('+3 hour')),
      ];
      $categoryModel->storeCategory($setCategoryData);

      return redirect()->route('admin.category');
   }

   /**
    * Display Category update form
    */
   public function edit($idCategory)
   {
      $categoryModel = new Category();

      $category = $categoryModel->find($idCategory);

      return view('admin.categories.update', ['category' => $category]);
   }

   /**
    * Update Category
    * 
    * @param object \Illuminate\Http\Request $request
    */
   public function update(Request $request)
   {
      $categoryModel = new Category();

      $postData = $request->post();
      $setCategoryData = [
         'name' => ucfirst($postData['name']),
         'description' => $postData['description'],
         'updated_at' => date('y.m.d H:i:s', strtotime('+3 hour')),
      ];
      $idCategory = $request->post('id_category');
      $categoryModel->updateCategory($idCategory, $setCategoryData);

      return redirect()->route('admin.category');
   }

   /**
    * Delete Category
    */
   public function destroy(Request $request)
   {
      $categoryModel = new Category();

      $idCategory = $request->post('id_category');
      $categoryModel->deleteCategory($idCategory);

      return redirect()->route('admin.category');
   }
}
