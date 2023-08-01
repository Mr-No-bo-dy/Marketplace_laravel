<?php

namespace App\Models\Site;

use App\Models\Site\Product;
use App\Models\Admin\Marketplace;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Seller extends Model
{
   use HasFactory;
   use SoftDeletes;

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
   * The attributes that are NOT assignable.
   *
   * @var array<int, string>
   */
   protected $dates = [
      'created_at',
      'updated_at',
      'deleted_at',
   ];

   /**
   * Setting relationship with DB table Marketplace.
   */
   public function marketplace(): BelongsTo
   {
      return $this->belongsTo(Marketplace::class, 'id_marketplace', 'id_marketplace');
   }

   /**
   * Setting relationship with DB table Products.
   */
   public function products(): HasMany
   {
      return $this->hasMany(Product::class, 'id_seller', 'id_seller');
   }

   /**
   * Check if loggining user exists in DB table Sellers
   * 
   * @param array $data
   * 
   * @return int $seller->id_seller
   */
   public function authSeller(array $data)
   {
      $checkData = [
         'phone',
         'email',
      ];
      $seller = [];
      foreach ($checkData as $field) {
         $builder = DB::table($this->table)
                     ->select(['id_seller', 'id_marketplace', 'name', 'surname', 'email', 'phone'])
                     ->where($field, $data['login'])
                     ->get();
         foreach ($builder as $row) {
            $seller = $row;
         }
      }
      
      if (!empty($seller)) {
         $builder = DB::table('sellers_passwords')
                        ->select()
                        ->where('id_seller', $seller->id_seller)
                        ->get();
         foreach ($builder as $row) {
            if (Hash::check($data['password'], $row->password)) {
               return $seller->id_seller;
            }
         }
      }
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
         // ->delete();
         ->update(['deleted_at' => date('Y-m-d H:i:s')]);
         
         
      DB::table($this->table)
         ->where($this->primaryKey, $idSeller)
         ->delete();
   }
}
