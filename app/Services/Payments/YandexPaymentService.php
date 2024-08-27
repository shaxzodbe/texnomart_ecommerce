<?php

namespace App\Services\Payments;

use App\Interfaces\Payments\PaymentGatewayInterface;

class YandexPaymentService implements PaymentGatewayInterface
{
    public function generatePaymentUrl(array $orderData): string
    {
        return 'https://yandex-payment.example.com/'.$orderData['id'];
    }
}
