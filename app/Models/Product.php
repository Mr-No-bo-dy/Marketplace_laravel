<?php

namespace App\Models;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Product extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, SoftDeletes;

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
     * Auto Soft-Delete & Restore of related records.
     */
    protected static function booted(): void
    {
        // Soft-Deletes
        static::deleting(function ($product) {
            $product->reviews()->delete();
        });

        // Restore
        static::restored(function ($product) {
            $product->reviews()->onlyTrashed()->restore();
        });
    }

    /**
     * Getting all Products based on filters.
     *
     * @param mixed $filters
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function readProducts(mixed $filters, int $perPage = 12): LengthAwarePaginator
    {
        $products = Product::with(['reviews', 'seller', 'seller.marketplace', 'media']);

        if (!empty($filters['id_producer'])) {
            $products->where('id_producer', $filters['id_producer']);
        }
        if (!empty($filters['id_category'])) {
            $products->where('id_category', $filters['id_category']);
        }
        if (!empty($filters['id_subcategory'])) {
            $products->where('id_subcategory', $filters['id_subcategory']);
        }
        if (!empty($filters['id_seller'])) {
            $products->where('id_seller', $filters['id_seller']);
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
        return Product::with(['producer:id_producer', 'category:id_category', 'subcategory:id_subcategory'])
            ->has('producer')
            ->has('category')
            ->has('subcategory')
            ->where('id_seller', $idSeller)
            ->withTrashed()
            ->get();
    }
}
