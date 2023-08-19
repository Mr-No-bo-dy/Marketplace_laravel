<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Resource\Traits\Components;
use App\Http\Resource\Traits\Products;
use App\Models\Admin\Category;
use App\Models\Admin\Producer;
use App\Models\Admin\Subcategory;
use App\Models\Site\Product;
use App\Models\Site\Seller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\HtmlString;

class ProductController extends Controller
{
    use Components, Products;

    /**
    * Display a listing of the Products.
    */
    public function index(Request $request)
    {
        $producers = Producer::all();
        $categories = Category::all();
        $subcategories = Subcategory::all();
        $sellers = Seller::all();

        // Forming the filters for Products
        $filters = $this->getFilters($request);

        // Getting Products based on filters
        $products = $this->getProducts($request, 3);

        $producersSelect = new HtmlString($this->customSelectData($producers, 'producer', $filters));
        $categoriesSelect = new HtmlString($this->customSelectData($categories, 'category', $filters));
        $subcategoriesSelect = new HtmlString($this->customSelectData($subcategories, 'subcategory', $filters));
        $sellersSelect = new HtmlString($this->customSelectData($sellers, 'seller', $filters));

        return view('site.products.index', compact('products', 'producersSelect', 'categoriesSelect', 'subcategoriesSelect', 'sellersSelect', 'filters'));
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
    public function store(ProductRequest $request): RedirectResponse
    {
        $productModel = new Product();

        $productModel->fill($request->validated());

		if ($request->hasFile('images')) {
			$images = $request->file('images');
			foreach ($images as $image) {
				$productModel->addMedia($image)
					->toMediaCollection('products');
			}
		}
	    $productModel->save();

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
     * @param ProductRequest $request
     * @return RedirectResponse
     */
    public function update(ProductRequest $request): RedirectResponse
    {
        $productModel = new Product();

        $idProduct = $request->post('id_product');
        $postData = $request->post();
        unset($postData['_token'], $postData['delete_media']);
//	    $setProductData = [
//            'id_producer' => $postData['id_producer'],
//            'id_category' => $postData['id_category'],
//            'id_subcategory' => $postData['id_subcategory'],
//            'name' => ucfirst($postData['name']),
//            'description' => ucfirst($postData['description']),
//            'price' => $postData['price'],
//            'amount' => $postData['amount'],
//        ];
	    $productModel->fill($request->validated());
	    $productModel->where('id_product', $idProduct)->update($postData);

	    if ($request->hasFile('images')) {
		    $product = $productModel->find($idProduct);
		    // Optional Delete old media
			if ($request->post('delete_media')) {
				$medias = $product->media;
				foreach ($medias as $media) {
					$media->delete($media->id);
				}
			}

			// Save new Media
		    $images = $request->file('images');
		    foreach ($images as $image) {
			    $product->addMedia($image)
				    ->toMediaCollection('products');
		    }
	    }

        return redirect()->route('seller.my_products');
    }

    /**
     * Delete Product
     */
    public function destroy(Request $request): RedirectResponse
    {
        $productModel = new Product();

        $idProduct = $request->post('id_product');
	    $product = $productModel->find($idProduct);
	    $medias = $product->media;
	    foreach ($medias as $media) {
		    $media->delete($media->id);
        }
	    $productModel->deleteProduct($idProduct);

        return redirect()->route('seller.my_products');
    }
}
