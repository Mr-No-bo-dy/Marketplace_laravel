<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Producer extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'producers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $primaryKey = 'id_producer';
    protected $fillable = [
        'name',
        'address',
        'contacts',
    ];

    /**
     * Setting relationship with DB table Products.
     *
     * @return HasMany
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'id_producer', 'id_producer');
    }

    /**
     * Auto Soft-Delete & Restore of related products.
     */
    protected static function booted(): void
    {
        // Force- and Soft-Deletes
        static::deleting(function ($producer) {
            $producer->products()->delete();
        });

        // Restore
        static::restored(function ($producer) {
            $producer->products()->onlyTrashed()->restore();
        });
    }
}
