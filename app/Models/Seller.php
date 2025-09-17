<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class Seller extends Model
{
    use HasFactory, SoftDeletes;

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
     * Auto Soft-Delete & Restore of related records.
     */
    protected static function booted(): void
    {
        // Soft-Deletes
        static::deleting(function ($seller) {
            $seller->products()->delete();
        });
        // NOT necessity, just example
        static::deleting(function ($seller) {
            DB::table('sellers_passwords')
                ->where('id_seller', $seller->id_seller)
                ->update(['deleted_at' => now()]);
        });

        // Restore
        static::restored(function ($seller) {
            $seller->products()->onlyTrashed()->restore();
        });
        // NOT necessity, just example
        static::restoring(function ($seller) {
            DB::table('sellers_passwords')
                ->where('id_seller', $seller->id_seller)
                ->update(['deleted_at' => null]);
        });
    }

    /**
     * Check if loggining user exists & not soft-deleted in DB table Sellers
     *
     * @param array $data
     * @return int|null
     */
    public function authSeller(array $data): int|null
    {
        $fields = ['phone', 'email'];
        $seller = Seller::select('id_seller', 'id_marketplace', 'name', 'surname', 'email', 'phone')
            ->where(function ($query) use ($fields, $data) {
                foreach ($fields as $field) {
                    $query->orWhere($field, $data['login']);
                }
            })
            ->first();

        if ($seller) {
            $sellerPassword = DB::table('sellers_passwords')
                ->where('id_seller', $seller->id_seller)
                ->first();

            if ($sellerPassword && Hash::check($data['password'], $sellerPassword->password)) {
                return $seller->id_seller;
            }
        }

        return null;
    }

    /**
     * Check if Seller has entered correct Password
     *
     * @param int $idSeller
     * @param string $password
     * @return boolean
     */
    public function passwordCheck(int $idSeller, string $password): bool
    {
        $result = false;

        $hashedDBPassword = DB::table('sellers_passwords')
                            ->where('id_seller', $idSeller)
                            ->pluck('password')
                            ->firstOrFail();

        if (Hash::check($password, $hashedDBPassword)) {
            $result = true;
        }

        return $result;
    }

    /**
     * Read all entities from DB table Sellers
     *
     * @return Collection
     */
    public function readAllSellers(): Collection
    {
        return Seller::select('m.country', $this->table . '.*')
                    ->join('marketplaces as m', $this->table . '.id_marketplace', '=', 'm.id_marketplace')
                    ->orderBy('id_seller')
                    ->withTrashed()
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
        return Seller::select('m.country', $this->table . '.*')
                    ->join('marketplaces as m', $this->table . '.id_marketplace', '=', 'm.id_marketplace')
                    ->where($this->primaryKey, $idSeller)
                    ->firstOrFail();
    }

    /**
     * Insert Seller's password into DB table 'sellers_passwords'
     *
     * @param int $idNewSeller
     * @param string $password
     */
    public function storeSellerPassword(int $idNewSeller, string $password): void
    {
        DB::table('sellers_passwords')
            ->insert([
                'id_seller' => $idNewSeller,
                'password' => Hash::make($password),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
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
}
