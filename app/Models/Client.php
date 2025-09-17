<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class Client extends Model
{
    use HasFactory, SoftDeletes;

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
     * Setting relationship with DB table Orders.
     *
     * @return HasMany
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'id_client', 'id_client');
    }

    /**
     * Auto Soft-Delete & Restore of related records.
     */
    protected static function booted(): void
    {
        // Soft-Deletes
        static::deleting(function ($client) {
            $client->reviews()->delete();
        });
        // NOT necessity, just example
        static::deleting(function ($client) {
            DB::table('clients_passwords')
                ->where('id_client', $client->id_client)
                ->update(['deleted_at' => now()]);
        });

        // Restore
        static::restored(function ($client) {
            $client->reviews()->onlyTrashed()->restore();
        });
        // NOT necessity, just example
        static::restoring(function ($client) {
            DB::table('clients_passwords')
                ->where('id_client', $client->id_client)
                ->update(['deleted_at' => null]);
        });
    }

    /**
     * Check if loggining user exists& not soft-deleted in DB table Clients
     *
     * @param array $data
     * @return int|null
     */
    public function authClient(array $data): int|null
    {
        $fields = ['phone', 'email'];
        $client = Client::select('id_client', 'name', 'surname', 'email', 'phone')
            ->where(function ($query) use ($fields, $data) {
                foreach ($fields as $field) {
                    $query->orWhere($field, $data['login']);
                }
            })
            ->first();

        if ($client) {
            $clientPassword = DB::table('clients_passwords')
                ->where('id_client', $client->id_client)
                ->first();

            if ($clientPassword && Hash::check($data['password'], $clientPassword->password)) {
                return $client->id_client;
            }
        }

        return null;
    }

    /**
     * Check if Client has entered correct Password
     *
     * @param int $idClient
     * @param string $password
     * @return boolean
     */
    public function passwordCheck(int $idClient, string $password): bool
    {
        $result = false;

        $hashedDBPassword = DB::table('clients_passwords')
                            ->where('id_client', $idClient)
                            ->pluck('password')
                            ->first();

        if (Hash::check($password, $hashedDBPassword)) {
            $result = true;
        }

        return $result;
    }

    /**
     * Read all entities from DB table Clients that make at least 1 order
     *
     * @return Collection
     */
    public function readAllClients(): Collection
    {
        return Client::select(
                        Client::raw('SUM(od.count) as total_count'),
                        $this->table.'.*'
                    )
                    ->join('orders as o', $this->table.'.id_client', '=', 'o.id_client')
                    ->join('order_details as od', 'o.id_order', '=', 'od.id_order')
                    ->withTrashed()
                    ->groupBy($this->table . '.' . $this->primaryKey)
                    ->get();
    }

    /**
     * Insert Client's password into DB table 'clients_passwords'
     *
     * @param int $idNewClient
     * @param string $password
     */
    public function storeClientPassword(int $idNewClient, string $password): void
    {
        DB::table('clients_passwords')
            ->insert([
                'id_client' => $idNewClient,
                'password' => Hash::make($password),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
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
}
