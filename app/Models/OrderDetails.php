<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderDetails extends Model
{
    use HasFactory, SoftDeletes;

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
}
