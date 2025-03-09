<?php

namespace App\Enums;

enum PaymentMethodTransaction: string
{
    case transferbank = 'transfer_bank';
    case creditcard = 'credit_card';
    case cash = 'cash';
}
