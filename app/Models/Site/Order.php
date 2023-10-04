<?php

namespace App\Models\Site;

use App\Models\Site\Client;
use App\Models\Site\Seller;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'orders';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $primaryKey = 'id_order';
    protected $fillable = [
        'id_client',
        'id_seller',
        'status',
        'date',
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
     * Setting relationship with DB table OrderDetails.
     *
     * @return HasOne
     */
    public function orderDetails(): HasOne
    {
        return $this->hasOne(OrderDetails::class, 'id_order', 'id_order');
    }
}
