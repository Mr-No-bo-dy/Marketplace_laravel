<?php

namespace App\Models\Site;

use App\Models\Admin\Marketplace;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class Seller extends Model
{
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
     * Setting relationship with DB table Marketplace.
     *
     * @return BelongsTo
     */
    public function marketplace(): BelongsTo
    {
        return $this->belongsTo(Marketplace::class, 'id_marketplace', 'id_marketplace');
    }

    /**
     * Setting relationship with DB table Products.
     *
     * @return HasMany
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'id_seller', 'id_seller');
    }

    /**
     * Setting relationship with DB table Orders.
     *
     * @return HasMany
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'id_seller', 'id_seller');
    }

    /**
     * Check if loggining user exists & not soft-deleted in DB table Sellers
     *
     * @param array $data
     * @return int|void
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
                        ->where('deleted_at', null)
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
     * Read all entities from DB table Sellers
     *
     * @return Collection
     */
    public function readAllSellers(): Collection
    {
        return DB::table($this->table)
                ->selectRaw('m.country, '.$this->table.'.*')
                ->join('marketplaces as m', $this->table.'.id_marketplace', '=', 'm.id_marketplace')
                ->get();
    }

    /**
     * Read selected entity from DB table Sellers with Marketplace's country
     *
     * @param int $idSeller
     * @return object
     */
    public function readSellerWithCountry(int $idSeller): object
    {
        return DB::table($this->table)
                ->selectRaw('m.country, '.$this->table.'.*')
                ->join('marketplaces as m', $this->table.'.id_marketplace', '=', 'm.id_marketplace')
                ->where($this->primaryKey, $idSeller)
                ->first();
    }

    /**
     * Read all entities' ids, names & surnames from DB table Sellers
     *
     * @return Collection
     */
    public function readSellersNames(): Collection
    {
        return self::all([$this->primaryKey, 'name', 'surname']);
    }

    /**
     * Read selected entity from DB table Sellers
     *
     * @param int $idSeller
     * @return object
     */
    public function readSeller(int $idSeller): object
    {
        return DB::table($this->table)
                ->where($this->primaryKey, $idSeller)
                ->first();
    }

    /**
     * Insert entity into DB table Sellers
     *
     * @param array $data
     * @return int
     */
    public function storeSeller(array $data): int
    {
        return DB::table($this->table)
                ->insertGetId($data);
    }

    /**
     * Insert Seller's password into DB table 'sellers_passwords'
     *
     * @param array $data
     */
    public function storeSellerPassword(array $data): void
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
    public function updateSeller(int $idSeller, array $data): void
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
    public function updateSellerPassword(int $idSeller, array $data): void
    {
        DB::table('sellers_passwords')
            ->where($this->primaryKey, $idSeller)
            ->update($data);
    }

    /**
     * Soft-Delete entity in DB table Sellers
     *
     * @param int $idSeller
     */
    public function deleteSeller(int $idSeller): void
    {
        DB::table('sellers_passwords')
            ->where($this->primaryKey, $idSeller)
            ->update(['deleted_at' => date('Y-m-d H:i:s')]);

        $seller = self::find($idSeller);
        if ($seller) {
            $seller->delete();
        }
    }

    /**
     * Soft-Delete entities in DB table Sellers from given Marketplace
     *
     * @param int $idMarketplace
     * @return array
     */
    public function deleteMarketplaceSellers(int $idMarketplace): array
    {
        $idsSellerStds = DB::table($this->table)
            ->select($this->primaryKey)
            ->where('id_marketplace', $idMarketplace)
            ->get();
        $idsSellers = [];
        foreach ($idsSellerStds as $std) {
            $idsSellers[] = $std->id_seller;
            $seller = self::find($std->id_seller);
            if ($seller) {
                $seller->delete();
            }
        }

        return $idsSellers;
    }

    /**
     * Restore entity in DB table Sellers
     *
     * @param int $idSeller
     */
    public function restoreSeller(int $idSeller): void
    {
        DB::table('sellers_passwords')
            ->where($this->primaryKey, $idSeller)
            ->update(['deleted_at' => null]);

        $seller = self::onlyTrashed()->find($idSeller);
        if ($seller) {
            $seller->restore();
        }
    }

    /**
     * Restore entities in DB table Sellers from given Marketplace
     *
     * @param int $idMarketplace
     * @return array
     */
    public function restoreMarketplaceSellers(int $idMarketplace): array
    {
        $idsSellerStds = DB::table($this->table)
            ->select($this->primaryKey)
            ->where('id_marketplace', $idMarketplace)
            ->get();
        $idsSellers = [];
        foreach ($idsSellerStds as $std) {
            $idsSellers[] = $std->id_seller;
            $seller = self::onlyTrashed()->find($std->id_seller);
            if ($seller) {
                $seller->restore();
            }
        }

        return $idsSellers;
    }
}
