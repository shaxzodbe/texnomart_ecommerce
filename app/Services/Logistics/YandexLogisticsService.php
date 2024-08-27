<?php

namespace App\Services\Logistics;

use App\Interfaces\Logistics\LogisticsInterface;

class YandexLogisticsService implements LogisticsInterface
{
    public function calculateDeliveryPrice(array $orderData): float
    {
        return 200.00;
    }
}
