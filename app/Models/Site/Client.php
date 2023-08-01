<?php

namespace App\Models\Site;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Client extends Model
{
   use HasFactory;

   /**
   * The table associated with the model.
   *
   * @var string
   */
   protected $table = 'clients';

   /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
   protected $primaryKey = 'id_client';
   protected $fillable = [
      'name',
      'surname',
      'email',
      'phone',
   ];

   /**
   * Check if loggining user exists in DB table Clients
   * 
   * @param array $data
   * 
   * @return int $client->id_client
   */
   public function authClient(array $data)
   {
      $checkData = [
         'phone',
         'email',
      ];
      $client = [];
      foreach ($checkData as $field) {
         $builder = DB::table($this->table)
                     ->select(['id_client', 'name', 'surname', 'email', 'phone'])
                     ->where($field, $data['login'])
                     ->get();
         foreach ($builder as $row) {
            $client = $row;
         }
      }
      
      if (!empty($client)) {
         $builder = DB::table('clients_passwords')
                        ->select()
                        ->where('id_client', $client->id_client)
                        ->get();
         foreach ($builder as $row) {
            if (Hash::check($data['password'], $row->password)) {
               return $client->id_client;
            }
         }
      }
   }

   /**
   * Insert entity into DB table Clients
   * 
   * @param array $data
   * 
   * @return int $idNewClient
   */
   public function storeClient(array $data)
   {
      $idNewClient = DB::table($this->table)
                        ->insertGetId($data);

      return $idNewClient;
   }

   /**
   * Insert Client's password into DB table 'clients_passwords'
   * 
   * @param array $data
   */
   public function storeClientPassword(array $data)
   {
      DB::table('clients_passwords')
         ->insert($data);
   }

   /**
   * Update entity into DB table Clients
   * 
   * @param int $idClient
   * @param array $data
   */
   public function updateClient(int $idClient, array $data)
   {
      DB::table($this->table)
         ->where($this->primaryKey, $idClient)
         ->update($data);
   }

   /**
   * Update Client's password into DB table 'clients_passwords'
   * 
   * @param int $idClient
   * @param array $data
   */
   public function updateClientPassword(int $idClient, array $data)
   {
      DB::table('clients_passwords')
         ->where($this->primaryKey, $idClient)
         ->update($data);
   }

   /**
   * Delete entity from DB table Clients
   * 
   * @param int $idClient
   */
   public function deleteClient(int $idClient)
   {
      DB::table('clients_passwords')
         ->where($this->primaryKey, $idClient)
         ->delete();
         
      DB::table($this->table)
         ->where($this->primaryKey, $idClient)
         ->delete();
   }
}
