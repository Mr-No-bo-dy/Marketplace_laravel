<?php

namespace App\Models\Site;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    use SoftDeletes;

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
     * Soft-Delete entities in DB table Reviews from given Client
     *
     * @param int $idClient
     */
    public function deleteClientReviews(int $idClient): void
    {
        $idsReviews = Review::where('id_client', $idClient)
                            ->pluck($this->primaryKey)
                            ->all();

        foreach ($idsReviews as $id) {
            Review::findOrFail($id)
                    ->delete();
        }
    }

    /**
     * Restore entities in DB table Reviews from given Client
     *
     * @param int $idClient
     */
    public function restoreClientReviews(int $idClient): void
    {
        $idsReviews = Review::where('id_client', $idClient)
                            ->withTrashed()
                            ->pluck($this->primaryKey)
                            ->all();

        foreach ($idsReviews as $id) {
            Review::onlyTrashed()
                    ->find($id)
                    ->restore();
        }
    }
}
