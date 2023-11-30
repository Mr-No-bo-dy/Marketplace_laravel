<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Marketplace extends Model
{
    use SoftDeletes;

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
     * Get currency symbol based on Marketplace (country)
     *
     * @param string $currency
     * @return string
     */
    public function getCurrency(string $currency): string
    {
        $currencies = [
            'UAH' => '₴',
            'USD' => '$',
            'GBP' => '£',
        ];

        return $currencies[$currency];
    }
}
