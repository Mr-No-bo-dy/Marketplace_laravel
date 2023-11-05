<?php

namespace App\Models\Site;

use App\Http\Resource\Traits\Products;
use App\Models\Admin\Category;
use App\Models\Admin\Producer;
use App\Models\Admin\Subcategory;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Product extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia, Products;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'products';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $primaryKey = 'id_product';
    protected $fillable = [
        'id_producer',
        'id_category',
        'id_subcategory',
        'id_seller',
        'name',
        'description',
        'price',
        'amount',
    ];

    /**
     * Setting relationship with DB table Producer.
     *
     * @return BelongsTo
     */
    public function producer(): BelongsTo
    {
        return $this->belongsTo(Producer::class, 'id_producer', 'id_producer');
    }

    /**
     * Setting relationship with DB table Category.
     *
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'id_category', 'id_category');
    }

    /**
     * Setting relationship with DB table Subcategory.
     *
     * @return BelongsTo
     */
    public function subcategory(): BelongsTo
    {
        return $this->belongsTo(Subcategory::class, 'id_subcategory', 'id_subcategory');
    }

    /**
     * Setting relationship with DB table Seller.
     *
     * @return BelongsTo
     */
    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class, 'id_seller', 'id_seller');
    }

    /**
     * Setting relationship with DB table Orders.
     *
     * @return HasMany
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'id_product', 'id_product');
    }

    /**
     * Setting relationship with DB table Reviews.
     *
     * @return HasMany
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'id_product', 'id_product');
    }

    /**
     * Getting all Products based on filters.
     *
     * @param mixed $filters
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function readProducts(mixed $filters, int $perPage = 5): LengthAwarePaginator
    {
        $products = Product::query();

        if (!empty($filters['id_producer'])) {
            $products->where('id_producer',$filters['id_producer']);
        }
        if (!empty($filters['id_category'])) {
            $products->where('id_category',$filters['id_category']);
        }
        if (!empty($filters['id_subcategory'])) {
            $products->where('id_subcategory',$filters['id_subcategory']);
        }
        if (!empty($filters['id_seller'])) {
            $products->where('id_seller',$filters['id_seller']);
        }
        if (!empty($filters['name'])) {
            $products->where('name','like', '%' . $filters['name'] . '%');
        }
        if (!empty($filters['price']['min'])) {
            $products->where('price', '>=', $filters['price']['min']);
        }
        if (!empty($filters['price']['max'])) {
            $products->where('price', '<=', $filters['price']['max']);
        }

        return $products->paginate($perPage);
    }

    /**
     * Read all entities from DB table Products with given Seller
     *
     * @param int $idSeller
     * @return Collection
     */
    public function readSellerProducts(int $idSeller): Collection
    {
        return Product::query()
                    ->where('id_seller', $idSeller)
                    ->withTrashed()
                    ->get();
    }

    /**
     * Read one entity from DB table Products
     *
     * @param int $idProduct
     * @return object
     */
    public function readProduct(int $idProduct): object
    {
        return Product::findOrFail($idProduct);
    }


    /**
     * Read Product's 'id_marketplace' by given Product
     *
     * @param int $idProduct
     * @return object
     */
    public function readSellerProductMarket(int $idProduct): object
    {
        return Product::select('s.id_marketplace')
                    ->join('sellers as s', $this->table . '.id_seller', '=', 's.id_seller')
                    ->where($this->table . '.id_product', $idProduct)
                    ->first();
    }

    /**
     * Soft-Delete entity in DB table Products
     *
     * @param int $idProduct
     */
    public function deleteProduct(int $idProduct): void
    {
        Product::findOrFail($idProduct)
                ->delete();
    }

    /**
     * Soft-Delete entities in DB table Products from given Seller
     *
     * @param array $idsSeller
     */
    public function deleteSellersProducts(array $idsSeller): void
    {
        $idsProductStds = Product::select($this->primaryKey)
                                ->whereIn('id_seller', $idsSeller)
                                ->get();
        foreach ($idsProductStds as $std) {
            Product::findOrFail($std->id_product)
                    ->delete();
        }
    }

    /**
     * Soft-Delete entities in DB table Products by given Subcategory
     *
     * @param int $idSubcategory
     */
    public function deleteSubcategoryProducts(int $idSubcategory): void
    {
        $idsProductStds = Product::select($this->primaryKey)
                                ->where('id_subcategory', $idSubcategory)
                                ->get();
        foreach ($idsProductStds as $std) {
            Product::findOrFail($std->id_product)
                    ->delete();
        }
    }

    /**
     * Soft-Delete entities in DB table Products by given Category
     *
     * @param int $idCategory
     */
    public function deleteCategoryProducts(int $idCategory): void
    {
        $idsProductStds = Product::select($this->primaryKey)
                                ->where('id_category', $idCategory)
                                ->get();
        foreach ($idsProductStds as $std) {
            Product::findOrFail($std->id_product)
                    ->delete();
        }
    }

    /**
     * Soft-Delete entities in DB table Products from given Producer
     *
     * @param int $idProducer
     */
    public function deleteProducerProducts(int $idProducer): void
    {
        $idsProductStds = Product::select($this->primaryKey)
                                ->where('id_producer', $idProducer)
                                ->get();
        foreach ($idsProductStds as $std) {
            Product::findOrFail($std->id_product)
                    ->delete();
        }
    }

    /**
     * Restore entity in DB table Products
     *
     * @param int $idProduct
     */
    public function restoreProduct(int $idProduct): void
    {
        Product::onlyTrashed()
                ->findOrFail($idProduct)
                ->restore();
    }

    /**
     * Restore entities in DB table Products from given Seller
     *
     * @param array $idsSeller
     */
    public function restoreSellerProducts(array $idsSeller): void
    {
        $idsProductStds = Product::select($this->primaryKey)
                                ->whereIn('id_seller', $idsSeller)
                                ->get();
        foreach ($idsProductStds as $std) {
            Product::onlyTrashed()
                    ->findOrFail($std->id_product)
                    ->restore();
        }
    }

    /**
     * Restore entities in DB table Products by given Subcategory
     *
     * @param int $idSubcategory
     */
    public function restoreSubcategoryProducts(int $idSubcategory): void
    {
        $idsProductStds = Product::select($this->primaryKey)
                                ->where('id_subcategory', $idSubcategory)
                                ->get();
        foreach ($idsProductStds as $std) {
            Product::onlyTrashed()
                    ->findOrFail($std->id_product)
                    ->restore();
        }
    }

    /**
     * Restore entities in DB table Products by given Category
     *
     * @param int $idCategory
     */
    public function restoreCategoryProducts(int $idCategory): void
    {
        $idsProductStds = Product::select($this->primaryKey)
                                ->where('id_category', $idCategory)
                                ->get();
        foreach ($idsProductStds as $std) {
            Product::onlyTrashed()
                    ->findOrFail($std->id_product)
                    ->restore();
        }
    }

    /**
     * Restore entities in DB table Products from given Producer
     *
     * @param int $idProducer
     */
    public function restoreProducerProducts(int $idProducer): void
    {
        $idsProductStds = Product::select($this->primaryKey)
                                ->where('id_producer', $idProducer)
                                ->get();
        foreach ($idsProductStds as $std) {
            Product::onlyTrashed()
                    ->find($std->id_product)
                    ->restore();
        }
    }
}
