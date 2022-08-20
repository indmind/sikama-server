<?php

namespace App\Enums;

enum OrderStatus: string
{
    // 'active', 'rejected', 'onprogress', 'finished'
    case Active = 'active';
    case Rejected = 'rejected';
    case OnProgress = 'onprogress';
    case Finished = 'finished';
}
