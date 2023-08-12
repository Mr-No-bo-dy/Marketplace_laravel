<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Admin\Category;
use App\Models\Admin\Producer;
use App\Models\Admin\Subcategory;
use App\Models\Site\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;


class ProductController extends Controller
{
   /**
   * Display a listing of the Products.
   */
   public function index(Request $request)
   {
//      $products = Product::all();
      $categories = Category::all();

       $filters = [
           'id_category' => $request->post('id_category'),
       ];
       $products = Product::where($filters);

      return view('site.products.index', compact('products', 'categories'));
   }

   /**
   * Display one chosen Product.
   */
   public function show($idProduct)
   {
      $product = Product::find($idProduct);

      return view('site.products.show', compact('product'));
   }

   /**
   * Display Product creation form
   */
   public function create()
   {
      $producers = Producer::all(['id_producer', 'name']);
      $categories = Category::all(['id_category', 'name']);
      $subcategories = Subcategory::all(['id_subcategory', 'name']);
      $idSeller = Session::get('id_seller');

      return view('site.products.create', compact('idSeller', 'producers', 'categories', 'subcategories'));
   }

    /**
     * Create Product
     *
     * @param ProductRequest $request
     * @return RedirectResponse
     */
//   public function store(Request $request): RedirectResponse
    public function store(ProductRequest $request): RedirectResponse
   {
      $productModel = new Product();

        $productModel->fill($request->validated());
      $productModel->save();

      $images = $request->file('images');

        foreach ($images as $image) {
            $productModel->addMedia($image)
                        ->toMediaCollection('products')
                       ->save();
      }

      return redirect()->route('seller.my_products');
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

      return view('site.products.update', compact('product', 'producers', 'categories', 'subcategories'));
   }

    /**
     * Update Product
     *
     * @param Request $request
     * @return RedirectResponse
     */
   public function update(Request $request): RedirectResponse
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
         'updated_at' => date('Y-m-d H:i:s'),
      ];
      $idProduct = $request->post('id_product');
      $productModel->updateProduct($idProduct, $setProductData);

      $image = $request->file();
      $product = $productModel->find($idProduct);
      $product->addMedia($image['image'])
               ->toMediaCollection('products')
               ->save();

      return redirect()->route('seller.my_products');
   }

   /**
   * Delete Product
   */
   public function destroy(Request $request): RedirectResponse
   {
      $productModel = new Product();

      $idProduct = $request->post('id_product');
      $productModel->deleteProduct($idProduct);

      return redirect()->route('seller.my_products');
   }
}
