<?php

namespace App\Http\Services;

use App\Models\Category;
use App\Models\Producer;
use App\Models\Product;
use App\Models\Seller;
use App\Models\Subcategory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\HtmlString;

class ProductPresenter
{
    /**
     * Getting selects with filters.
     *
     * @param array $filters
     * @return array
     */
    public function getComponents(mixed $filters): array
    {
        // Getting additional data
        $producers = Producer::all(['id_producer', 'name']);
        $categories = Category::all(['id_category', 'name']);
        $subcategories = Subcategory::all(['id_subcategory', 'name']);
        $sellers = Seller::all(['id_seller', 'name', 'surname']);

        $producersSelect = new HtmlString($this->customSelectData($producers, 'producer', $filters));
        $categoriesSelect = new HtmlString($this->customSelectData($categories, 'category', $filters));
        $subcategoriesSelect = new HtmlString($this->customSelectData($subcategories, 'subcategory', $filters));
        $sellersSelect = new HtmlString($this->customSelectData($sellers, 'seller', $filters));

        return [
            'producersSelect' => $producersSelect,
            'categoriesSelect' => $categoriesSelect,
            'subcategoriesSelect' => $subcategoriesSelect,
            'sellersSelect' => $sellersSelect,
        ];
    }

    /**
     * Forming select for view-page.
     *
     * @param mixed $entities
     * @param string $type
     * @param array $filters
     * @return View
     */
    public function customSelectData(mixed $entities, string $type, array $filters = []): View
    {
        if (!is_array($entities)) {
            $entities = $entities->toArray();
        }
        $idEntity = 'id_' . $type;
        $entities = array_merge([0 => [$idEntity => 0, 'name' => trans('products.all')]], $entities);

        return view('components.select', compact('entities', 'idEntity', 'type', 'filters'));
    }

    /**
     * Forming the filters for Products.
     *
     * @param Request $request
     * @return mixed
     */
    public function getFilters(Request $request): mixed
    {
        $filters = [
            'id_producer' => 0,
            'id_category' => 0,
            'id_subcategory' => 0,
            'id_seller' => 0,
            'name' => '',
            'price' => [
                'min' => '',
                'max' => '',
            ],
        ];

        if ($request->has('id_producer')) {
            $request->session()->put('filters.id_producer', $request->input('id_producer'));
        }
        if ($request->has('id_category')) {
            $request->session()->put('filters.id_category', $request->input('id_category'));
        }
        if ($request->has('id_subcategory')) {
            $request->session()->put('filters.id_subcategory', $request->input('id_subcategory'));
        }
        if ($request->has('id_seller')) {
            $request->session()->put('filters.id_seller', $request->input('id_seller'));
        }
        if ($request->has('name')) {
            $request->session()->put('filters.name', $request->input('name'));
        }
        if ($request->has('price')) {
            $request->session()->put('filters.price.min', $request->input('price.min'));
            $request->session()->put('filters.price.max', $request->input('price.max'));
        }

        if ($request->input('resetFilters')) {
            $request->session()->forget('filters');
        }

        if (!empty($request->session()->get('filters'))) {
            $filters = $request->session()->get('filters');
        }

        return $filters;
    }

    /**
     * Prepare Product for view.
     *
     * @param Product $product
     * @return Product
     */
    public function formatProduct(Product $product): Product
    {
        // Show only approved reviews
        $product->approvedReviews = $product->reviews->where('status', 2);

        // Calculating Product's Rating
        $ratingSum = 0;
        $ratingCount = count($product->approvedReviews);
        foreach ($product->approvedReviews as $review) {
            $ratingSum += $review->rating;
        }
        $avgRating = !empty($ratingCount) ? $ratingSum / $ratingCount : 0;
        $product->avgRating = number_format($avgRating, 2);

        // Formatting price
        $marketplace = $product->seller->marketplace;
        $product->priceFormatted = number_format($product->price, 0, '.', ' ')
            . ' '. $marketplace->getCurrency($marketplace->currency);

        // Image's URL
        $imageUrl = $product->getFirstMediaUrl('products') ? $product->getFirstMediaUrl('products'): asset('/images/_no_photo.svg');
        $product->img_url = $imageUrl;

        return $product;
    }
}
