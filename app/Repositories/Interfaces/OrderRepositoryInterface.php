<?php

namespace App\Repositories\Interfaces;

use App\Models\Order;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface OrderRepositoryInterface
{
    public function findById(int $id): ?Order;

    public function getAllOrders(array $filters = [], int $perPage = 15): LengthAwarePaginator;

    public function createOrder(array $data): Order;

    public function updateOrder(int $orderId, array $data): bool;

    public function updateOrderStatus(int $orderId, string $status): bool;

    public function cancelOrder(int $orderId): bool;

    public function deleteOrder(int $orderId): bool;
}
