<?php

namespace App\Models\Site;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'reviews';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $primaryKey = 'id_review';
    protected $fillable = [
        'id_client',
        'id_product',
        'comment',
        'rating',
        'status',
    ];

    /**
     * Setting relationship with DB table Seller.
     *
     * @return BelongsTo
     */
    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class, 'id_seller', 'id_seller');
    }

    /**
     * Setting relationship with DB table Client.
     *
     * @return BelongsTo
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'id_client', 'id_client');
    }

    /**
     * Setting relationship with DB table Product.
     *
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'id_product', 'id_product');
    }

    /**
     * Insert entity into DB table Reviews
     *
     * @param array $data
     */
    public function storeReview(array $data): void
    {
        DB::table($this->table)
            ->insert($data);
    }

    /**
     * Insert entity into DB table Reviews
     *
     * @param int $idReview
     * @param array $data
     */
    public function updateReview(int $idReview, array $data): void
    {
        DB::table($this->table)
            ->where($this->primaryKey, $idReview)
            ->update($data);
    }

    /**
     * Delete entity from DB table Reviews
     *
     * @param int $idReview
     */
    public function deleteReview(int $idReview): void
    {
        DB::table($this->table)
            ->where($this->primaryKey, $idReview)
            ->delete();
    }
}
