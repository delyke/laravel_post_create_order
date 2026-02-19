<?php

declare(strict_types=1);

namespace Modules\Order\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Modules\Order\Actions\CreateOrderAction;
use Modules\Order\DTO\CreateOrderDto;
use Modules\Order\Http\Requests\OrderStoreRequest;
use Modules\Order\Http\Resources\OrderResource;
use Symfony\Component\HttpFoundation\Response;

class OrderStoreController
{
    public function __invoke(
        OrderStoreRequest $request,
        CreateOrderAction $action,
    ): JsonResponse {
        $dto = CreateOrderDto::fromRequest($request);
        $order = $action->execute($dto);

        return (new OrderResource($order))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }
}
