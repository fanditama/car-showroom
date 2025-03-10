<?php

namespace App\Enums;

enum StatusCreditApplication: string
{
    case tertunda = 'tertunda';
    case disetujui = 'disetujui';
    case ditolak = 'ditolak';
}
