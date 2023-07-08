<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Seller extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sellers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $primaryKey = 'id_seller';
    protected $fillable = [
        'id_marketplace',
        'name',
        'surname',
        'email',
        'phone',
    ];

    /**
     * Get all entities from DB table Sellers
     * 
     * @param int $idSeller
     * 
     * @return array $sellers
     */
    public function getAllSellers()
    {
        $sellers = DB::table($this->table, 's')
                        ->leftJoin('marketplaces', 'marketplaces.id_marketplace', '=', 's.id_marketplace')
                        ->select('marketplaces.country', 's.*')
                        ->get();

        return $sellers;
    }

    /**
     * Get one entity from DB table Sellers
     * 
     * @param int $idSeller
     * 
     * @return array $sellers
     */
    public function getOneSeller($idSeller)
    {
        $sellers = DB::table($this->table, 's')
                        ->leftJoin('marketplaces', 'marketplaces.id_marketplace', '=', 's.id_marketplace')
                        ->leftJoin('sellers_passwords', 'sellers_passwords.'.$this->primaryKey, '=', 's.'.$this->primaryKey)
                        ->select('marketplaces.country', 'marketplaces.country_code', 'sellers_passwords.password', 's.*')
                        ->where('s.'.$this->primaryKey, $idSeller)
                        ->get();

        return $sellers;
    }

    /**
     * Insert entity into DB table Sellers
     * 
     * @param array $data
     * 
     * @return int $idNewSeller
     */
    public function storeSeller(array $data)
    {
        $idNewSeller = DB::table($this->table)
                            ->insertGetId($data);

        return $idNewSeller;
    }

    /**
     * Insert Seller's password into DB table 'sellers_passwords'
     * 
     * @param array $data
     */
    public function storeSellerPassword(array $data)
    {
        DB::table('sellers_passwords')
            ->insert($data);
    }

    /**
     * Update entity into DB table Sellers
     * 
     * @param int $idSeller
     * @param array $data
     */
    public function updateSeller(int $idSeller, array $data)
    {
        DB::table($this->table)
            ->where($this->primaryKey, $idSeller)
            ->update($data);
    }

    /**
     * Update Seller's password into DB table 'sellers_passwords'
     * 
     * @param int $idSeller
     * @param array $data
     */
    public function updateSellerPassword(int $idSeller, array $data)
    {
        DB::table('sellers_passwords')
            ->where($this->primaryKey, $idSeller)
            ->update($data);
    }

    /**
     * Delete entity from DB table Sellers
     * 
     * @param int $idSeller
     */
    public function deleteSeller(int $idSeller)
    {
        DB::table('sellers_passwords')
            ->where($this->primaryKey, $idSeller)
            ->delete();
            
        DB::table($this->table)
            ->where($this->primaryKey, $idSeller)
            ->delete();
    }
}
