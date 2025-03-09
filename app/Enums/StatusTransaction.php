<?php

namespace App\Enums;

enum StatusTransaction: string
{
    case pending = 'pending';
    case success = 'success';
    case cancel = 'cancel';
}
