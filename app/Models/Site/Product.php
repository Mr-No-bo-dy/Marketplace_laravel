<?php

namespace App\Models\Site;

use App\Models\Admin\Category;
use App\Models\Admin\Subcategory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Product extends Model implements HasMedia
{
    use InteractsWithMedia;

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
     * Setting relationship with DB table Category.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'id_category', 'id_category');
    }

    /**
     * Setting relationship with DB table Subcategory.
     */
    public function subcategory(): BelongsTo
    {
        return $this->belongsTo(Subcategory::class, 'id_subcategory', 'id_subcategory');
    }

    /**
     * Setting relationship with DB table Orders.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'id_product', 'id_product');
    }

//    /**
//     * Insert entity into DB table Products
//     *
//     * @param array $data
//     * @return int
//     */
//    public function storeProduct(array $data): int
//    {
//        return DB::table($this->table)
//            ->insertGetId($data);
//    }

//    /**
//     * Update entity into DB table Products
//     *
//     * @param int $idProduct
//     * @param array $data
//     */
//    public function updateProduct(int $idProduct, array $data): void
//    {
//        DB::table($this->table)
//            ->where($this->primaryKey, $idProduct)
//            ->update($data);
//    }

    /**
     * Delete entity from DB table Products
     *
     * @param int $idProduct
     */
    public function deleteProduct(int $idProduct): void
    {
        DB::table($this->table)
            ->where($this->primaryKey, $idProduct)
            ->delete();
    }
}
