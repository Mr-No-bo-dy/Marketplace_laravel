<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Category extends Model
{
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
   * Insert entity into DB table Categories
   * 
   * @param array $data
   */
   public function storeCategory(array $data)
   {
      DB::table($this->table)
         ->insert($data);
   }

   /**
   * Insert entity into DB table Categories
   * 
   * @param int $idCategory
   * @param array $data
   */
   public function updateCategory(int $idCategory, array $data)
   {
      DB::table($this->table)
         ->where($this->primaryKey, $idCategory)
         ->update($data);
   }

   /**
   * Delete entity from DB table Categories
   * 
   * @param int $idCategory
   */
   public function deleteCategory(int $idCategory)
   {
      DB::table($this->table)
         ->where($this->primaryKey, $idCategory)
         ->delete();
   }
}
