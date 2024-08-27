<?php

namespace App\Enums;

use App\Traits\HasEnumValues;

enum OrderStatusEnum: string
{
    use HasEnumValues;

    case PENDING = 'pending';
    case PROCESSING = 'processing';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';
}
