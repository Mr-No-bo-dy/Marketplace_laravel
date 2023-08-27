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
    protected $table = 'comments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $primaryKey = 'id_comment';
    protected $fillable = [
        'id_client',
        'id_product',
        'comment',
        'rating',
    ];

    /**
     * Setting relationship with DB table Client.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'id_client', 'id_client');
    }

    /**
     * Setting relationship with DB table Product.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'id_client', 'id_client');
    }
}
