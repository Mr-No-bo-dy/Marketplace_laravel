<?php

namespace App\Models\Site;

use App\Models\Site\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderDetails extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'order_details';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $primaryKey = 'id_order_details';
    protected $fillable = [
        'id_order',
        'id_product',
        'count',
        'total',
    ];

    /**
    * Setting relationship with DB table Orders.
     *
     * @return BelongsTo
    */
    public function orders(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'id_order', 'id_order');
    }

    /**
     * Storing order's data into DB table OrderDetails.
     *
     * @param array $orderDetails
     */
    public function storeOrderDetails(array $orderDetails): void
    {
        DB::table($this->table)
            ->insert($orderDetails);
    }
}
