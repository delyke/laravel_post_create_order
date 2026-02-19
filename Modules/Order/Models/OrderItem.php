<?php

declare(strict_types=1);

namespace Modules\Order\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price_per_unit_kopecks',
        'subtotal_kopecks',
    ];

    protected $casts = [
        'product_id' => 'integer',
        'quantity' => 'integer',
        'price_per_unit_kopecks' => 'integer',
        'subtotal_kopecks' => 'integer',
    ];

    /**
     * @return BelongsTo<Order, $this>
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
