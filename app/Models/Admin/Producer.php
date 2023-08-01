<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Producer extends Model
{
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
   * Insert entity into DB table Producers
   * 
   * @param array $data
   */
   public function storeProducer(array $data)
   {
      DB::table($this->table)
         ->insert($data);
   }

   /**
   * Insert entity into DB table Producers
   * 
   * @param int $idProducer
   * @param array $data
   */
   public function updateProducer(int $idProducer, array $data)
   {
      DB::table($this->table)
         ->where($this->primaryKey, $idProducer)
         ->update($data);
   }

   /**
   * Delete entity from DB table Producers
   * 
   * @param int $idProducer
   */
   public function deleteProducer(int $idProducer)
   {
      DB::table($this->table)
         ->where($this->primaryKey, $idProducer)
         ->delete();
   }
}
