<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Producer extends Model
{
    use SoftDeletes;

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
     * Read all entities from DB table Producers
     *
     * @return Collection
     */
    public function readAllProducers(): Collection
    {
        return DB::table($this->table)
                ->get();
    }

    /**
     * Read all entities' ids & names from DB table Producers
     *
     * @return Collection
     */
    public function readProducersNames(): Collection
    {
        return self::all([$this->primaryKey, 'name']);
    }

    /**
     * Read one entity from DB table Producers
     *
     * @param int $idProducer
     * @return object
     */
    public function readProducer(int $idProducer): object
    {
        return DB::table($this->table)
            ->where($this->primaryKey, $idProducer)
            ->first();
    }

    /**
     * Insert entity into DB table Producers
     *
     * @param array $data
     */
    public function storeProducer(array $data): void
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
    public function updateProducer(int $idProducer, array $data): void
    {
        DB::table($this->table)
            ->where($this->primaryKey, $idProducer)
            ->update($data);
    }

    /**
     * Soft-Delete entity in DB table Producers
     *
     * @param int $idProducer
     */
    public function deleteProducer(int $idProducer): void
    {
        $producer = self::find($idProducer);
        if ($producer) {
            $producer->delete();
        }
    }

    /**
     * Restore entity in DB table Producers
     *
     * @param int $idProducer
     */
    public function restoreMarketplace(int $idProducer): void
    {
        $producer = self::onlyTrashed()->find($idProducer);
        if ($producer) {
            $producer->restore();
        }
    }
}
