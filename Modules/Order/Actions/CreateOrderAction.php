<?php

declare(strict_types=1);

namespace Modules\Order\Actions;

use Illuminate\Support\Facades\DB;
use Modules\Order\DTO\CreateOrderDto;
use Modules\Order\Exceptions\InvalidOrderAmountException;
use Modules\Order\Models\Order;
use Modules\Order\VO\Money;

class CreateOrderAction
{
    public function execute(CreateOrderDto $dto): Order
    {
        $total = Money::fromKopecks(0);

        $itemsData = [];

        foreach ($dto->items as $item) {
            $subtotal = Money::fromKopecks($item->pricePerUnitKopecks)->multiply($item->quantity);
            $total = $total->add($subtotal);

            $itemsData[] = [
                'product_id' => $item->productId,
                'quantity' => $item->quantity,
                'price_per_unit_kopecks' => $item->pricePerUnitKopecks,
                'subtotal_kopecks' => $subtotal->kopecks,
            ];
        }

        if (! $total->isPositive()) {
            throw InvalidOrderAmountException::totalMustBePositive();
        }

        return DB::transaction(function () use ($total, $itemsData): Order {
            $order = Order::create([
                'total_kopecks' => $total->kopecks,
            ]);

            $order->items()->createMany($itemsData);

            return $order->load('items');
        });
    }
}
