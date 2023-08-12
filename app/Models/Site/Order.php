<?php

namespace App\Models\Site;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    public function storeOrder(array $order, $orderDetails)
    {
        $idOrder = DB::table($this->table)
            ->insertGetId($order);

        DB::table('order_details')
            ->where($this->primaryKey, $idOrder)
            ->insert($orderDetails);

        return $idOrder;
    }
}
