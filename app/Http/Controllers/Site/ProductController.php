<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Admin\Category;
use App\Models\Admin\Producer;
use App\Models\Admin\Subcategory;
use App\Models\Site\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
   /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
   protected $idSeller;

   /**
    * Display a listing of the Products.
    */
   public function index()
   {
      $productModel = new Product();

      $products = $productModel->all();

      return view('site.product.index', ['products' => $products]);
   }
   
   /**
    * Display Product creation form
    */
   public function create(Request $request)
   {
      $producerModel = new Producer();
      $categoryModel = new Category();
      $subcategoryModel = new Subcategory();

      $allProducers = $producerModel->all(['id_producer', 'name']);
      $allCategories = $categoryModel->all(['id_category', 'name']);
      $allSubcategories = $subcategoryModel->all(['id_subcategory', 'name']);

      $content = [
         'producers' => $allProducers,
         'categories' => $allCategories,
         'subcategories' => $allSubcategories,
      ];

      return view('site.product.create', $content);
   }

   /**
    * Create Product
    * 
    * @param object \Illuminate\Http\Request $request
    */
   public function store(Request $request)
   {
      $productModel = new Product();

      $postData = $request->post();
      $setProductData = [
         'id_producer' => $postData['id_producer'],
         'id_category' => $postData['id_category'],
         'id_subcategory' => $postData['id_subcategory'],
         'id_seller' => $request->session()->get('id_seller'),
         'name' => ucfirst($postData['name']),
         'description' => ucfirst($postData['description']),
         'price' => $postData['price'],
         'amount' => $postData['amount'],
         'created_at' => date('y.m.d H:i:s', strtotime('+3 hour')),
         'updated_at' => date('y.m.d H:i:s', strtotime('+3 hour')),
      ];
      $productModel->storeProduct($setProductData);

      return redirect()->route('product');
   }

   /**
    * Display Product update form
    */
   public function edit($idProduct, Request $request)
   {
      $productModel = new Product();
      $producerModel = new Producer();
      $categoryModel = new Category();
      $subcategoryModel = new Subcategory();

      $product = $productModel->find($idProduct);
      $allProducers = $producerModel->all(['id_producer', 'name']);
      $allCategories = $categoryModel->all(['id_category', 'name']);
      $allSubcategories = $subcategoryModel->all(['id_subcategory', 'name']);

      $content = [
         'product' => $product,
         'producers' => $allProducers,
         'categories' => $allCategories,
         'subcategories' => $allSubcategories,
      ];

      return view('site.product.update', $content);
   }

   /**
    * Update Product
    * 
    * @param object \Illuminate\Http\Request $request
    */
   public function update(Request $request)
   {
      $productModel = new Product();

      $postData = $request->post();
      $setProductData = [
         'id_producer' => $postData['id_producer'],
         'id_category' => $postData['id_category'],
         'id_subcategory' => $postData['id_subcategory'],
         'name' => ucfirst($postData['name']),
         'description' => ucfirst($postData['description']),
         'price' => $postData['price'],
         'amount' => $postData['amount'],
         'updated_at' => date('y.m.d H:i:s', strtotime('+3 hour')),
      ];
      $idProduct = $request->post('id_product');
      $productModel->updateProduct($idProduct, $setProductData);

      return redirect()->route('product');
   }

   /**
    * Delete Product
    */
   public function destroy(Request $request)
   {
      $productModel = new Product();

      $idProduct = $request->post('id_product');
      $productModel->deleteProduct($idProduct);

      return redirect()->route('product');
   }
}
