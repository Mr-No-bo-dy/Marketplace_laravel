<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Marketplace extends Model
// class Marketplace extends Base
{
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
     * Insert entity into DB table Marketplaces
     *
     * @param array $data
     */
    public function storeMarketplace(array $data): void
    {
        DB::table($this->table)
            ->insert($data);
    }

    /**
     * Insert entity into DB table Marketplaces
     *
     * @param int $idMarketplace
     * @param array $data
     */
    public function updateMarketplace(int $idMarketplace, array $data): void
    {
        DB::table($this->table)
            ->where($this->primaryKey, $idMarketplace)
            ->update($data);
    }

    /**
     * Delete entity from DB table Marketplaces
     *
     * @param int $idMarketplace
     */
    public function deleteMarketplace(int $idMarketplace): void
    {
        DB::table($this->table)
            ->where($this->primaryKey, $idMarketplace)
            ->delete();
    }

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
