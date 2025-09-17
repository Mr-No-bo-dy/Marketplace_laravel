<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Services\ProductPresenter;
use App\Models\Category;
use App\Models\Producer;
use App\Models\Product;
use App\Models\Subcategory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

class ProductController extends Controller
{
    private ProductPresenter $productPresenter;

    public function __construct(ProductPresenter $productPresenter)
    {
        $this->productPresenter = $productPresenter;
    }

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
        $filters = $this->productPresenter->getFilters($request);

        // Getting Products based on filters & Preparing Products for view
        $products = $productModel->readProducts($filters, 12);
        $products->getCollection()->transform(function ($product) {
            return $this->productPresenter->formatProduct($product);
        });

        extract($this->productPresenter->getComponents($filters));

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
     * @return View|RedirectResponse
     */
    public function show(int $idProduct): View|RedirectResponse
    {
        $product = Product::with(['reviews', 'seller', 'seller.marketplace', 'media'])->findOrFail($idProduct);
        $this->productPresenter->formatProduct($product);

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
            $this->saveImages($request, $product, $request->file('images'));
        }

        return redirect()->route('product.my_products');
    }

    /**
     * Display Product update form
     *
     * @param int $idProduct
     * @return View|RedirectResponse
     */
    public function edit(int $idProduct): View|RedirectResponse
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
     * @param Product $product
     * @return RedirectResponse
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function update(ProductRequest $request, Product $product): RedirectResponse
    {
        // Update Product
        $product->fill($request->validated());
        if ($product->isDirty()) {
            $product->save();
        }

        // Update Media
        if ($request->hasFile('images')) {
            $this->saveImages($request, $product, $request->file('images'));
        }

        return redirect()->route('product.my_products');
    }

    /**
     * @param ProductRequest $request
     * @param Product $product
     * @param array $photos
     * @return void
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function saveImages(ProductRequest $request, Product $product, array $photos): void
    {
        // Optional Delete old media
        if ($request->has('delete_media')) {
            foreach ($product->media as $media) {
                $media->delete($media->id);
            }
        }
        // Save new Media
        foreach ($photos as $image) {
            $product->addMedia($image)->toMediaCollection('products');
        }
    }

    /**
     * Delete Product
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function destroy(Request $request): RedirectResponse
    {
        $idProduct = $request->validate(['id_product' => ['bail', 'required', 'integer', 'min:1', 'max:999999999']])['id_product'];
        Product::findOrFail($idProduct)->delete();

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
        $idProduct = $request->validate(['id_product' => ['bail', 'required', 'integer', 'min:1', 'max:999999999']])['id_product'];
        Product::onlyTrashed()->findOrFail($idProduct)->restore();

        return back();
    }
}
