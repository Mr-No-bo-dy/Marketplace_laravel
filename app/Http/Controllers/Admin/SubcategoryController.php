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
      $subcategoryModel = new Subcategory();
      
      $subcategories = $subcategoryModel->getAllSubcategories();

      return view('admin.subcategories.index', ['subcategories' => $subcategories]);
   }
   
   /**
    * Display Subcategory creation form
    */
   public function create()
   {
      $categoryModel = new Category();
      
      $allCategories = $categoryModel->all(['id_category', 'name']);

      return view('admin.subcategories.create', ['categories' => $allCategories]);
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
      $categoryModel = new Category();
      $subcategoryModel = new Subcategory();
      
      $allCategories = $categoryModel->all(['id_category', 'name']);
      $subcategory = $subcategoryModel->find($idSubcategory);
      $setSubcategoryData = [
         'categories' => $allCategories,
         'subcategory' => $subcategory,
      ];

      return view('admin.subcategories.update', $setSubcategoryData);
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
