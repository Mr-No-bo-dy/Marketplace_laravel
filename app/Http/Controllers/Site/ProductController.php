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
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\HtmlString;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

class ProductController extends Controller
{
    use Components, Products;

    /**
    * Display a listing of the Products.
     *
     * @param Request $request
     * @return View
    */
    public function index(Request $request): View
    {
        $productModel = new Product();

        // Using the filters for Products
        $filters = $this->getFilters($request);

        // Getting Products based on filters
        $products = $productModel->readProducts($filters, 4);

        // Preparing Product for view
        foreach ($products as $product) {
            $this->formatProduct($product);
        }

        // Getting additional data
        $producers = Producer::all(['id_producer', 'name']);
        $categories = Category::all(['id_category', 'name']);
        $subcategories = Subcategory::all(['id_subcategory', 'name']);
        $sellers = Seller::all(['id_seller', 'name', 'surname']);

        $producersSelect = new HtmlString($this->customSelectData($producers, 'producer', $filters));
        $categoriesSelect = new HtmlString($this->customSelectData($categories, 'category', $filters));
        $subcategoriesSelect = new HtmlString($this->customSelectData($subcategories, 'subcategory', $filters));
        $sellersSelect = new HtmlString($this->customSelectData($sellers, 'seller', $filters));

        return view('site.products.index', compact('products', 'producersSelect', 'categoriesSelect', 'subcategoriesSelect', 'sellersSelect', 'filters'));
    }

    /**
     * Display a listing of the Products from given Seller.
     *
     * @param Request $request
     * @return View
     */
    public function sellerProducts(Request $request): View
    {
        $productModel = new Product();

        $idSeller = $request->session()->get('id_seller');
        $products = $productModel->readSellerProducts($idSeller);

        return view('site.seller.products', compact('products'));
    }

    /**
     * Display one chosen Product.
     *
     * @param int $idProduct
     * @return View
     */
    public function show(int $idProduct): View
    {
        $product = Product::findOrFail($idProduct);
        $this->formatProduct($product);

        return view('site.products.show', compact('product'));
    }

    /**
     * Display Product creation form
     *
     * @param Request $request
     * @return View
     */
    public function create(Request $request): View
    {
        $idSeller = $request->session()->get('id_seller');
        $producers = Producer::all(['id_producer', 'name']);
        $categories = Category::all(['id_category', 'name']);
        $subcategories = Subcategory::all(['id_subcategory', 'name']);

        return view('site.products.create', compact('idSeller', 'producers', 'categories', 'subcategories'));
    }

    /**
     * Create Product
     *
     * @param ProductRequest $request
     * @return RedirectResponse
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function store(ProductRequest $request): RedirectResponse
    {
        // Create Product
        $product = Product::create($request->validated());

        // Save Media
        if ($request->hasFile('images')) {
            $images = $request->file('images');
            foreach ($images as $image) {
                $product->addMedia($image)->toMediaCollection('products');
            }
        }

        return redirect()->route('product.my_products');
    }

    /**
     * Display Product update form
     *
     * @param int $idProduct
     * @return View
     */
    public function edit(int $idProduct): View
    {
        $product = Product::findOrFail($idProduct);
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
        // Update Product
        $idProduct = $request->validate(['id_product' => ['bail', 'integer', 'min: 1', 'max:9223372036854775807']])['id_product'];
        $product = Product::findOrFail($idProduct);
        $product->fill($request->validated());
        if ($product->isDirty()) {
            $product->save();
        }

        // Update Media
        if ($request->hasFile('images')) {
            // Optional Delete old media
            if ($request->has('delete_media')) {
                $medias = $product->media;
                foreach ($medias as $media) {
                    $media->delete($media->id);
                }
            }
            // Save new Media
            $images = $request->file('images');
            foreach ($images as $image) {
                $product->addMedia($image)->toMediaCollection('products');
            }
        }

        return redirect()->route('product.my_products');
    }

    /**
     * Delete Product
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function destroy(Request $request): RedirectResponse
    {
        if ($request->has('deleteProduct')) {
            $idProduct = $request->validate(['id_product' => ['bail', 'integer', 'min: 1', 'max:9223372036854775807']])['id_product'];
            Product::findOrFail($idProduct)->delete();
        }

        return back();
    }

    /**
     * Restore Product
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function restore(Request $request): RedirectResponse
    {
        if ($request->has('restoreProduct')) {
            $idProduct = $request->validate(['id_product' => ['bail', 'integer', 'min: 1', 'max:9223372036854775807']])['id_product'];
            Product::onlyTrashed()->findOrFail($idProduct)->restore();
        }

        return back();
    }
}
