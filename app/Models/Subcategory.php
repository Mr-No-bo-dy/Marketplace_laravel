<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subcategory extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'subcategories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $primaryKey = 'id_subcategory';
    protected $fillable = [
        'id_category',
        'name',
        'description',
    ];

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
     * Setting relationship with DB table Products.
     *
     * @return HasMany
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'id_subcategory', 'id_subcategory');
    }

    /**
     * Auto Soft-Delete & Restore of related subcategories and products.
     */
    protected static function booted(): void
    {
        // Force- and Soft-Deletes
        static::deleting(function ($category) {
            $category->products()->delete();
        });

        // Restore
        static::restored(function ($category) {
            $category->products()->onlyTrashed()->restore();
        });
    }

    /**
     * Read all entities from DB table Subcategories
     *
     * @return Collection
     */
    public function readAllSubcategories(): Collection
    {
        return Subcategory::select('c.name as category', $this->table . '.*')
                            ->join('categories as c', $this->table . '.id_category', '=', 'c.id_category')
                            ->withTrashed()
                            ->get();
    }
}
