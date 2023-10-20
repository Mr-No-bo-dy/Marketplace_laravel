<?php

namespace App\Models\Site;

use App\Models\Admin\Category;
use App\Models\Admin\Producer;
use App\Models\Admin\Subcategory;
use App\Models\Site\Seller;
use App\Models\Site\Review;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Product extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia;

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
     * Read all entities from DB table Products with given Seller
     *
     * @param int $idSeller
     * @return Collection
     */
    public function readSellerProducts(int $idSeller): Collection
    {
        return self::query()
                    ->withTrashed()
                    ->where('id_seller', $idSeller)
                    ->get();
    }

    /**
     * Soft-Delete entity in DB table Products
     *
     * @param int $idProduct
     */
    public function deleteProduct(int $idProduct): void
    {
        $product = self::find($idProduct);
        if ($product) {
            $product->delete();
        }
    }

    /**
     * Soft-Delete entities in DB table Products from given Seller
     *
     * @param array $idsSeller
     */
    public function deleteSellersProducts(array $idsSeller): void
    {
        $idsProductStds = DB::table($this->table)
            ->select($this->primaryKey)
            ->whereIn('id_seller', $idsSeller)
            ->get();
        foreach ($idsProductStds as $std) {
            $product = self::find($std->id_product);
            if ($product) {
                $product->delete();
            }
        }
    }

    /**
     * Soft-Delete entities in DB table Products by given Subcategory
     *
     * @param int $idSubcategory
     */
    public function deleteSubcategoryProducts(int $idSubcategory): void
    {
        $idsProductStds = DB::table($this->table)
                        ->select($this->primaryKey)
                        ->where('id_subcategory', $idSubcategory)
                        ->get();
        foreach ($idsProductStds as $std) {
            $product = self::find($std->id_product);
            if ($product) {
                $product->delete();
            }
        }
    }

    /**
     * Soft-Delete entities in DB table Products by given Category
     *
     * @param int $idCategory
     */
    public function deleteCategoryProducts(int $idCategory): void
    {
        $idsProductStds = DB::table($this->table)
                        ->select($this->primaryKey)
                        ->where('id_category', $idCategory)
                        ->get();
        foreach ($idsProductStds as $std) {
            $product = self::find($std->id_product);
            if ($product) {
                $product->delete();
            }
        }
    }

    /**
     * Soft-Delete entities in DB table Products from given Producer
     *
     * @param int $idProducer
     */
    public function deleteProducerProducts(int $idProducer): void
    {
        $idsProductStds = DB::table($this->table)
                        ->select($this->primaryKey)
                        ->where('id_producer', $idProducer)
                        ->get();
        foreach ($idsProductStds as $std) {
            $product = self::find($std->id_product);
            if ($product) {
                $product->delete();
            }
        }
    }

    /**
     * Restore entity in DB table Products
     *
     * @param int $idProduct
     */
    public function restoreProduct(int $idProduct): void
    {
        $product = self::onlyTrashed()->find($idProduct);
        if ($product) {
            $product->restore();
        }
    }

    /**
     * Restore entities in DB table Products from given Seller
     *
     * @param array $idsSeller
     */
    public function restoreSellerProducts(array $idsSeller): void
    {
        $idsProductStds = DB::table($this->table)
            ->select($this->primaryKey)
            ->whereIn('id_seller', $idsSeller)
            ->get();
        foreach ($idsProductStds as $std) {
            $product = self::onlyTrashed()->find($std->id_product);
            if ($product) {
                $product->restore();
            }
        }
    }

    /**
     * Restore entities in DB table Products by given Subcategory
     *
     * @param int $idSubcategory
     */
    public function restoreSubcategoryProducts(int $idSubcategory): void
    {
        $idsProductStds = DB::table($this->table)
                        ->select($this->primaryKey)
                        ->where('id_subcategory', $idSubcategory)
                        ->get();
        foreach ($idsProductStds as $std) {
            $product = self::onlyTrashed()->find($std->id_product);
            if ($product) {
                $product->restore();
            }
        }
    }

    /**
     * Restore entities in DB table Products by given Category
     *
     * @param int $idCategory
     */
    public function restoreCategoryProducts(int $idCategory): void
    {
        $idsProductStds = DB::table($this->table)
                        ->select($this->primaryKey)
                        ->where('id_category', $idCategory)
                        ->get();
        foreach ($idsProductStds as $std) {
            $product = self::onlyTrashed()->find($std->id_product);
            if ($product) {
                $product->restore();
            }
        }
    }

    /**
     * Restore entities in DB table Products from given Producer
     *
     * @param int $idProducer
     */
    public function restoreProducerProducts(int $idProducer): void
    {
        $idsProductStds = DB::table($this->table)
            ->select($this->primaryKey)
            ->where('id_producer', $idProducer)
            ->get();
        foreach ($idsProductStds as $std) {
            $product = self::onlyTrashed()->find($std->id_product);
            if ($product) {
                $product->restore();
            }
        }
    }
}
