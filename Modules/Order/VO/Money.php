<?php

declare(strict_types=1);

namespace Modules\Order\VO;

readonly class Money
{
    private function __construct(
        public int $kopecks
    ) {}

    public static function fromKopecks(int $kopecks): self
    {
        return new self($kopecks);
    }

    public function add(Money $other): self
    {
        return new self($this->kopecks + $other->kopecks);
    }

    public function multiply(int $quantity): self
    {
        return new self($this->kopecks * $quantity);
    }

    public function isPositive(): bool
    {
        return $this->kopecks > 0;
    }

    public function isNegative(): bool
    {
        return $this->kopecks < 0;
    }
}
