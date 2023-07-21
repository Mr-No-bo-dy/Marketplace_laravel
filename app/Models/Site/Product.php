<?php

namespace App\Models\Site;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
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
    * Insert entity into DB table Products
    * 
    * @param array $data
    * 
    * @return int $idProduct
    */
   public function storeProduct(array $data)
   {
      $idProduct = DB::table($this->table)
                        ->insertGetId($data);

      return $idProduct;
   }
 
   /**
    * Update entity into DB table Products
    * 
    * @param int $idProduct
    * @param array $data
    */
   public function updateProduct(int $idProduct, array $data)
   {
      DB::table($this->table)
         ->where($this->primaryKey, $idProduct)
         ->update($data);
   }

   /**
    * Delete entity from DB table Products
    * 
    * @param int $idProduct
    */
   public function deleteProduct(int $idProduct)
   {         
      DB::table($this->table)
         ->where($this->primaryKey, $idProduct)
         ->delete();
   }

}
