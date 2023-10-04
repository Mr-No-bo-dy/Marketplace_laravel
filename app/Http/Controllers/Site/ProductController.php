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
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

class ProductController extends Controller
{
    use Components, Products;

    /**
    * Display a listing of the Products.
     *
     * @param Request $request
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

        // Calculating Product's Rating
        foreach ($products as $product) {
            $product->reviews = $product->reviews->where('status', 2);      // show only approved reviews

            // Calculating average rating
            $ratingSum = 0;
            $ratingCount = count($product->reviews);
            foreach ($product->reviews as $review) {
                    $ratingSum += $review->rating;
            }
            $avgRating = !empty($ratingCount) ? $ratingSum / $ratingCount : 0;
            $product->avgRating = number_format($avgRating, 2);

            // Formatting price
            $marketplace = $product->seller->marketplace;
            $product->priceFormatted = number_format($product->price, 0, '.', ' ')
                . ' '. $marketplace->getCurrency($marketplace->currency);
        }

        $producersSelect = new HtmlString($this->customSelectData($producers, 'producer', $filters));
        $categoriesSelect = new HtmlString($this->customSelectData($categories, 'category', $filters));
        $subcategoriesSelect = new HtmlString($this->customSelectData($subcategories, 'subcategory', $filters));
        $sellersSelect = new HtmlString($this->customSelectData($sellers, 'seller', $filters));

        return view('site.products.index', compact('products', 'producersSelect', 'categoriesSelect', 'subcategoriesSelect', 'sellersSelect', 'filters'));
    }

    /**
     * Display one chosen Product.
     *
     * @param int $idProduct
     */
    public function show(int $idProduct)
    {
        $product = Product::find($idProduct);

        $product->reviews = $product->reviews->where('status', 2);      // show only approved reviews

        // Calculating Product's Rating
        $ratingSum = 0;
        $ratingCount = count($product->reviews);
        foreach ($product->reviews as $review) {
            $ratingSum += $review->rating;
        }
        $avgRating = !empty($ratingCount) ? $ratingSum / $ratingCount : 0;
        $product->avgRating = number_format($avgRating, 2);

        // Formatting price
        $marketplace = $product->seller->marketplace;
        $product->priceFormatted = number_format($product->price, 0, '.', ' ')
            . ' '. $marketplace->getCurrency($marketplace->currency);

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
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function store(ProductRequest $request): RedirectResponse
    {
        if ($request->has('createProduct')) {
            $productModel = new Product();

            $productModel->fill($request->validated());
            $productModel->save();

            // Save Media
            if ($request->hasFile('images')) {
                $images = $request->file('images');
                foreach ($images as $image) {
                    $productModel->addMedia($image)
                        ->toMediaCollection('products');
                }
            }
        }

        return redirect()->route('seller.my_products');
    }

    /**
     * Display Product update form
     *
     * @param int $idProduct
     */
    public function edit(int $idProduct)
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
        if ($request->has('updateProduct')) {
            $productModel = new Product();

            $idProduct = $request->post('id_product');
            $setProductData = [
                'id_producer' => $request->post('id_producer'),
                'id_category' => $request->post('id_category'),
                'id_subcategory' => $request->post('id_subcategory'),
                'name' => ucfirst($request->post('name')),
                'description' => ucfirst($request->post('description')),
                'price' => $request->post('price'),
                'amount' => $request->post('amount'),
            ];
            $productModel->fill($request->validated());
            $productModel->where('id_product', $idProduct)->update($setProductData);

            // Update Media
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
        }

        return redirect()->route('seller.my_products');
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
            $idProduct = $request->post('id_product');
            $product = Product::find($idProduct);
            $medias = $product->media;
            foreach ($medias as $media) {
                $media->delete($media->id);
            }
            Product::destroy($idProduct);
        }

        return back();
    }
}
