<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'categories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $primaryKey = 'id_category';
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Setting relationship with DB table Products.
     *
     * @return HasMany
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'id_category', 'id_category');
    }

    /**
     * Setting relationship with DB table Subcategories.
     *
     * @return HasMany
     */
    public function subcategories(): HasMany
    {
        return $this->hasMany(Subcategory::class, 'id_category', 'id_category');
    }

    /**
     * Auto Soft-Delete & Restore of related subcategories and products.
     */
    protected static function booted(): void
    {
        // Force- and Soft-Deletes
        static::deleting(function ($category) {
            if ($category->isForceDeleting()) {
                $category->products()->forceDelete();
                $category->subcategories()->forceDelete();
            } else {
                $category->products()->delete();
                $category->subcategories()->delete();
            }
        });

        // Restore
        static::restored(function ($category) {
            $category->subcategories()->onlyTrashed()->restore();
            $category->products()->onlyTrashed()->restore();
        });
    }
}
