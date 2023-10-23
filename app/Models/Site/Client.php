<?php

namespace App\Models\Site;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class Client extends Model
{
    use SoftDeletes;

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
     * Setting relationship with DB table Orders.
     *
     * @return HasMany
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'id_client', 'id_client');
    }

    /**
     * Check if loggining user exists& not soft-deleted in DB table Clients
     *
     * @param array $data
     * @return int|void
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
                        ->where('deleted_at', null)
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
     * Read all entities from DB table Clients
     *
     * @return Collection
     */
    public function readAllClients(): Collection
    {
        return DB::table($this->table)
                ->selectRaw('SUM(od.count) as total_count, SUM(od.total) as total_amount, '.$this->table.'.*')
                ->join('orders as o', $this->table.'.id_client', '=', 'o.id_client')
                ->join('order_details as od', 'o.id_order', '=', 'od.id_order')
                ->groupBy($this->table.'.id_client')
                ->get();
    }

    /**
     * Read one entity from DB table Clients
     *
     * @param int $idClient
     * @return object
     */
    public function readClient(int $idClient): object
    {
        return DB::table($this->table)
                ->where('id_client', $idClient)
                ->first();
    }

    /**
     * Insert entity into DB table Clients
     *
     * @param array $data
     * @return int
     */
    public function storeClient(array $data): int
    {
        return DB::table($this->table)
                ->insertGetId($data);
    }

    /**
     * Insert Client's password into DB table 'clients_passwords'
     *
     * @param array $data
     */
    public function storeClientPassword(array $data): void
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
    public function updateClient(int $idClient, array $data): void
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
    public function updateClientPassword(int $idClient, array $data): void
    {
        DB::table('clients_passwords')
            ->where($this->primaryKey, $idClient)
            ->update($data);
    }

    /**
     * Soft-Delete entity in DB table Clients
     *
     * @param int $idClient
     */
    public function deleteClient(int $idClient): void
    {
        DB::table('clients_passwords')
            ->where($this->primaryKey, $idClient)
            ->update(['deleted_at' => date('Y-m-d H:i:s')]);

        $client = self::find($idClient);
        if ($client) {
            $client->delete();
        }
    }

    /**
     * restore entity in DB table Clients
     *
     * @param int $idClient
     */
    public function restoreClient(int $idClient): void
    {
        DB::table('clients_passwords')
            ->where($this->primaryKey, $idClient)
            ->update(['deleted_at' => null]);

        $client = self::onlyTrashed()->find($idClient);
        if ($client) {
            $client->restore();
        }
    }
}
