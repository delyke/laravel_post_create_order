<?php

declare(strict_types=1);

namespace Modules\Order\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Order\VO\Money;

class Order extends Model
{
    protected $fillable = [
        'total_kopecks',
    ];

    protected $casts = [
        'total_kopecks' => 'integer',
    ];

    /**
     * @return HasMany<OrderItem, $this>
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getTotal(): Money
    {
        return Money::fromKopecks($this->total_kopecks);
    }
}
