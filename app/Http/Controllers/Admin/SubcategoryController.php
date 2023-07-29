<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Category;
use App\Models\Admin\Subcategory;
use Illuminate\Http\Request;

class SubcategoryController extends Controller
{
   /**
   * Display a listing of the Subcategories.
   */
   public function index()
   {
      // $subcategoryModel = new Subcategory();

      // $subcategories = $subcategoryModel->getAllSubcategories();

      $subcategories = Subcategory::all();
      $categories = Category::all();

      foreach ($subcategories as $subcategory) {
         foreach ($categories as $category) {
            if ($subcategory['id_category'] === $category['id_category']) {
               $subcategory['category'] = $category['name'];
            }
         }
      }

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
    * @param object \Illuminate\Http\Request $request
    */
   public function store(Request $request)
   {
      $subcategoryModel = new Subcategory();

      $postData = $request->post();
      $setSubcategoryData = [
         'id_category' => $postData['id_category'],
         'name' => ucfirst($postData['name']),
         'description' => $postData['description'],
         'created_at' => date('y.m.d H:i:s', strtotime('+3 hour')),
         'updated_at' => date('y.m.d H:i:s', strtotime('+3 hour')),
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
    * @param object \Illuminate\Http\Request $request
    */
   public function update(Request $request)
   {
      $subcategoryModel = new Subcategory();

      $postData = $request->post();
      $setCategoryData = [
         'id_category' => $postData['id_category'],
         'name' => ucfirst($postData['name']),
         'description' => $postData['description'],
         'updated_at' => date('y.m.d H:i:s', strtotime('+3 hour')),
      ];
      $idSubcategory = $request->post('id_subcategory');
      $subcategoryModel->updateSubCategory($idSubcategory, $setCategoryData);

      return redirect()->route('admin.subcategory');
   }

   /**
   * Delete Subcategory
   */
   public function destroy(Request $request)
   {
      $subcategoryModel = new Subcategory();

      $idSubcategory = $request->post('id_subcategory');
      $subcategoryModel->deleteSubCategory($idSubcategory);

      return redirect()->route('admin.subcategory');
   }
}
