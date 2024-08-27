<?php

namespace App\Enums;

use App\Traits\HasEnumValues;

enum DeliveryStatusEnum: string
{
    use HasEnumValues;

    case PENDING = 'pending';
    case SHIPPED = 'shipped';
    case DELIVERED = 'delivered';
    case RETURNED = 'returned';
}
