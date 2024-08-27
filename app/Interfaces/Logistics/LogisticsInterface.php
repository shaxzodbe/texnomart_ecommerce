<?php

namespace App\Interfaces\Logistics;

interface LogisticsInterface
{
    public function calculateDeliveryPrice(array $orderData): float;
}
