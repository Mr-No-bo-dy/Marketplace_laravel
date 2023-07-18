<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Subcategory extends Model
{
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
    * Get all entities from DB table Subcategories
    * 
    * @return array $Subcategories
    */
   public function getAllSubcategories()
   {
      $subcategories = DB::table($this->table, 'sc')
                     ->leftJoin('categories as c', 'c.id_category', '=', 'sc.id_category')
                     ->select('c.name as category', 'sc.*')
                     ->get();

      return $subcategories;
   }

   /**
    * Get one entity from DB table Subcategories
    * 
    * @param int $idSubcategory
    * 
    * @return array $subcategories
    */
   public function getOneSubcategory(int $idSubcategory)
   {
      $subcategories = DB::table($this->table, 'sc')
                     ->leftJoin('categories as c', 'c.id_category', '=', 'sc.id_category')
                     ->select('c.name as category', 'sc.*')
                     ->get();

      return $subcategories;
   }
   
   /**
    * Insert entity into DB table Subcategories
   * 
   * @param array $data
   */
   public function storeSubcategory(array $data)
   {
      DB::table($this->table)
         ->insert($data);
   }

   /**
    * Insert entity into DB table Subcategories
   * 
   * @param int $idSubcategory
   * @param array $data
   */
   public function updateSubcategory(int $idSubcategory, array $data)
   {
      DB::table($this->table)
         ->where($this->primaryKey, $idSubcategory)
         ->update($data);
   }

   /**
    * Delete entity from DB table Subcategories
   * 
   * @param int $idSubcategory
   */
   public function deleteSubcategory(int $idSubcategory)
   {
      DB::table($this->table)
         ->where($this->primaryKey, $idSubcategory)
         ->delete();
   }
}
