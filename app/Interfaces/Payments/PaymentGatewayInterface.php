<?php

namespace App\Interfaces\Payments;

interface PaymentGatewayInterface
{
    public function generatePaymentUrl(array $orderData): string;
}
