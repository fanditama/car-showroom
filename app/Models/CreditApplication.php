<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreditApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_date',
        'income',
        'status',
        'user_id',
        'car_id',
    ];

    protected $casts = [
        'application_date' => 'datetime',
        'income' => 'decimal:2',
        'status' => 'string',
    ];

    public function setApplicationDateAttribute($value)
    {
        if ($value) {
            $this->attributes['application_date'] = \Carbon\Carbon::createFromFormat('d-m-Y H:i:s', $value)
                ->format('Y-m-d H:i:s');
        } else {
            $this->attributes['application_date'] = null;
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
