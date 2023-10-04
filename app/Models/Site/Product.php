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
use Illuminate\Support\Facades\DB;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

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
}
