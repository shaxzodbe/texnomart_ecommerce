<?php

namespace Tests\Feature\Http\Controllers\Api\Admin\V1;

use App\Enums\OrderStatusEnum;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Response;

test('update order status', function ($status, $expectedMessage) {
    $admin = User::factory()->admin()->create();
    $user = User::factory()->create(['phone' => '998949289094']);
    $order = Order::factory()->create([
        'user_id' => $user->id,
        'status' => OrderStatusEnum::PENDING->value,
    ]);

    $response = $this->actingAs($admin)
        ->postJson("/api/admin/orders/{$order->id}/status", ['status' => $status]
        );

    $response->assertStatus(Response::HTTP_OK)
        ->assertJson([
            'data' => ['message' => $expectedMessage],
        ]);
})->with([
    [
        OrderStatusEnum::COMPLETED->value,
        'Order status updated and SMS sent to the user.',
    ],
]);
test('delete order', function () {
    $admin = User::factory()->admin()->create();
    $order = Order::factory()->create();

    $response = $this->actingAs($admin)
        ->deleteJson("/api/admin/orders/{$order->id}");

    $response->assertStatus(Response::HTTP_OK)
        ->assertJson([
            'data' => ['message' => 'Order deleted successfully.'],
        ]);
});
