<?php

declare(strict_types=1);

namespace Modules\Order\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Order\Models\Order;
use Modules\Order\Models\OrderItem;
use Tests\TestCase;

class OrderStoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_order_with_items(): void
    {
        $payload = [
            'items' => [
                ['product_id' => 1, 'quantity' => 2, 'price_per_unit' => 1000],
                ['product_id' => 2, 'quantity' => 3, 'price_per_unit' => 500],
            ],
        ];

        $response = $this->postJson('/api/v1/orders', $payload);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'total_kopecks',
                    'items' => [
                        '*' => [
                            'id',
                            'product_id',
                            'quantity',
                            'price_per_unit_kopecks',
                            'subtotal_kopecks',
                        ],
                    ],
                    'created_at',
                    'updated_at',
                ],
            ])
            ->assertJson([
                'data' => [
                    'total_kopecks' => 3500,
                    'items' => [
                        ['product_id' => 1, 'quantity' => 2, 'price_per_unit_kopecks' => 1000, 'subtotal_kopecks' => 2000],
                        ['product_id' => 2, 'quantity' => 3, 'price_per_unit_kopecks' => 500, 'subtotal_kopecks' => 1500],
                    ],
                ],
            ]);

        $this->assertDatabaseCount('orders', 1);
        $this->assertDatabaseCount('order_items', 2);
        $this->assertDatabaseHas('orders', ['total_kopecks' => 3500]);
    }

    public function test_returns_validation_error_when_items_is_empty(): void
    {
        $payload = [
            'items' => [],
        ];

        $response = $this->postJson('/api/v1/orders', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['items']);
    }

    public function test_returns_validation_error_when_items_is_missing(): void
    {
        $response = $this->postJson('/api/v1/orders', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['items']);
    }

    public function test_returns_error_when_all_items_have_zero_price(): void
    {
        $payload = [
            'items' => [
                ['product_id' => 1, 'quantity' => 2, 'price_per_unit' => 0],
                ['product_id' => 2, 'quantity' => 3, 'price_per_unit' => 0],
            ],
        ];

        $response = $this->postJson('/api/v1/orders', $payload);

        $response->assertStatus(422)
            ->assertJson([
                'message' => 'Order total must be greater than zero.',
            ]);

        $this->assertDatabaseCount('orders', 0);
        $this->assertDatabaseCount('order_items', 0);
    }
}
