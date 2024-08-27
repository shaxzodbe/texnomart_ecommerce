<?php

namespace App\Repositories;

use App\Enums\OrderStatusEnum;
use App\Models\Order;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class OrderRepository implements OrderRepositoryInterface
{
    protected Order $model;

    public function __construct(Order $order)
    {
        $this->model = $order;
    }

    public function createOrder(array $data): Order
    {
        return $this->model->create($data);
    }

    public function updateOrder(int $orderId, array $data): bool
    {
        $order = $this->findById($orderId);

        if (! $order) {
            return false;
        }

        return $order->update($data);
    }

    public function findById(int $id): ?Order
    {
        return $this->model->find($id);
    }

    public function updateOrderStatus(int $orderId, string $status): bool
    {
        $order = $this->findById($orderId);

        if (! $order) {
            return false;
        }

        $order->status = $status;

        return $order->save();
    }

    public function cancelOrder(int $orderId): bool
    {
        $order = $this->findById($orderId);

        $order->status = OrderStatusEnum::CANCELLED->value;

        return $order->save();
    }

    public function deleteOrder(int $orderId): bool
    {
        $order = $this->findById($orderId);

        if (! $order) {
            return false;
        }

        return $order->delete();
    }

    public function getAllOrders(
        array $filters = [],
        int $perPage = 15
    ): LengthAwarePaginator {
        $query = $this->model->newQuery();

        if (isset($filters['name'])) {
            $query->where('name', 'like', '%'.$filters['name'].'%');
        }

        return $query->paginate($perPage);
    }
}
