<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Admin\Category;
use App\Models\Admin\Producer;
use App\Models\Admin\Subcategory;
use App\Models\Site\Product;
use App\Models\Site\Seller;
use Illuminate\Http\Request;

// use Illuminate\Database\Eloquent\Collection;

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
      $products = Product::all();

      // $images = $productModel->getMedia();

      // $imgs = [];
      // foreach ($products as $product) {
      //    $imgs[] = $product->getMedia('products');
      // }
      // $path = $imgs[5][0]->getPath();
      // // dd($path);
      
      // $images = $productModel::last()->getMedia('products');

      // $all = [];
      // Product::chunk(10, function(Collection $products) {
      //    foreach ($products as $product) {
      //       $all[] = $product;
      //       dump($product);
      //    }
      // });
      // dd($all);

      return view('site.product.index', compact('products'));
   }

   /**
    * Display a listing of the Products from given Seller.
    */
   public function sellerProducts(Request $request)
   {
      $productModel = new Product();
      $sellerModel = new Seller();

      $idSeller = $request->session()->get('id_seller');
      $seller = $sellerModel->getOneSeller($idSeller);
      $products = $productModel->getSellerProducts($idSeller);
      // $products = $productModel::withMedia('products')->where('id_seller', $idSeller)->get();
      // $media = $productModel::all();
      // dd($media);

      return view('site.product.index', compact('seller', 'products'));
   }
   
   /**
    * Display Product creation form
    */
   public function create()
   {
      $producers = Producer::all(['id_producer', 'name']);
      $categories = Category::all(['id_category', 'name']);
      $subcategories = Subcategory::all(['id_subcategory', 'name']);

      return view('site.product.create', compact('producers', 'categories', 'subcategories'));
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
      $image = $request->file();

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

      $idNewProduct = $productModel->storeProduct($setProductData);
      $product = $productModel->find($idNewProduct);
      $product->addMedia($image['image'])
               ->toMediaCollection('products')
               ->save();

      return redirect()->route('product');
   }

   /**
    * Display Product update form
    */
   public function edit($idProduct)
   {
      $product = Product::find($idProduct);
      $producers = Producer::all(['id_producer', 'name']);
      $categories = Category::all(['id_category', 'name']);
      $subcategories = Subcategory::all(['id_subcategory', 'name']);

      return view('site.product.update', compact('product', 'producers', 'categories', 'subcategories'));
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
