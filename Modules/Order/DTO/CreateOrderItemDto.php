<?php

declare(strict_types=1);

namespace Modules\Order\DTO;

readonly class CreateOrderItemDto
{
    public function __construct(
        public int $productId,
        public int $quantity,
        public int $pricePerUnitKopecks,
    ) {}
}
