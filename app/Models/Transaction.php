<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_date',
        'total_amount',
        'payment_method',
        'status',
        'user_id',
        'car_id',
    ];

    protected $casts = [
        'transaction_date' => 'datetime',
        'total_amount' => 'integer',
        'payment_method' => 'string',
        'status' => 'string',
    ];

    public function setTransactionDateAttribute($value)
    {
        if ($value) {
            $this->attributes['transaction_date'] = Carbon::createFromFormat('d-m-Y H:i:s', $value)
                ->format('Y-m-d H:i:s');
        } else {
            $this->attributes['transaction_date'] = null;
        }
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function car()
    {
        return $this->belongsTo(Car::class);
    }
}
