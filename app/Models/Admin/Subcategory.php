<?php

namespace App\Models\Admin;

use App\Models\Admin\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
   * Setting relationship with DB table Category.
   */
   public function category(): BelongsTo
   {
      return $this->belongsTo(Category::class, 'id_category', 'id_category');
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
