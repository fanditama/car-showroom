<?php

namespace App\Enums;

enum StatusTransaction: string
{
    case pending = 'pending';
    case processing = 'processing';
    case success = 'success';
    case cancel = 'cancel';
    case cancel = 'failed';
}
