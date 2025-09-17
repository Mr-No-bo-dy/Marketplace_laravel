<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Marketplace extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'marketplaces';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $primaryKey = 'id_marketplace';
    protected $fillable = [
        'country_code',
        'country',
        'currency',
    ];

    /**
     * Setting relationship with DB table Sellers.
     *
     * @return HasMany
     */
    public function sellers(): HasMany
    {
        return $this->hasMany(Seller::class, 'id_marketplace', 'id_marketplace');
    }

    /**
     * Auto Soft-Delete & Restore of related sellers and sellers' products.
     */
    protected static function booted(): void
    {
        static::deleting(function ($marketplace) {
            $marketplace->sellers()->each(function ($seller) {
                $seller->delete();      // Triggers Seller::deleting -> deletes Products
                session()->forget('id_seller');
            });
        });

        static::restored(function ($marketplace) {
            $marketplace->sellers()->onlyTrashed()->each(function ($seller) {
                $seller->restore();     // Triggers Seller::restored -> restores Products
            });
        });
    }

    /**
     * Get currency symbol based on Marketplace (country)
     *
     * @return string
     */
    public function getCurrency(): string
    {
        return config('currencies.' . $this->currency) ?? $this->currency;
    }
}
