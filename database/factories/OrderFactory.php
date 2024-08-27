<?php

namespace Database\Factories;

use App\Enums\DeliveryStatusEnum;
use App\Enums\OrderStatusEnum;
use App\Enums\PaymentStatusEnum;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        $productPrice = $this->faker->randomFloat(2, 10, 100);
        $deliveryPrice = $this->faker->optional()->randomFloat(2, 5, 20);
        $totalPrice = $productPrice + ($deliveryPrice ?? 0);

        return [
            'user_id' => User::factory(),
            'product_price' => $productPrice,
            'delivery_price' => $deliveryPrice,
            'total_price' => $totalPrice,
            'status' => $this->faker->randomElement(OrderStatusEnum::getAllCases()),
            'payment_status' => $this->faker->randomElement(PaymentStatusEnum::getAllCases()),
            'delivery_status' => $this->faker->randomElement(DeliveryStatusEnum::getAllCases()),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
