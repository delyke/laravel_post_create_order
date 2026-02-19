<?php

declare(strict_types=1);

namespace Modules\Order\DTO;

use Illuminate\Http\Request;

readonly class CreateOrderDto
{
    /**
     * @param array<CreateOrderItemDto> $items
     */
    public function __construct(
        public array $items,
    ) {}

    public static function fromRequest(Request $request): self
    {
        $items = [];

        foreach ($request->input('items', []) as $item) {
            $items[] = new CreateOrderItemDto(
                productId: (int) $item['product_id'],
                quantity: (int) $item['quantity'],
                pricePerUnitKopecks: (int) $item['price_per_unit'],
            );
        }

        return new self($items);
    }
}
