<?php

namespace App\Enums;

use App\Traits\HasEnumValues;

enum PaymentStatusEnum: string
{
    use HasEnumValues;

    case PENDING = 'pending';
    case PAID = 'paid';
    case FAILED = 'failed';
}
