<?php

declare(strict_types=1);

namespace Modules\Order\Exceptions;

use DomainException;

class InvalidOrderAmountException extends DomainException
{
    public static function totalMustBePositive(): self
    {
        return new self('Order total must be greater than zero.');
    }
}
