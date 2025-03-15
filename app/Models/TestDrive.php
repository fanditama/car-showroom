<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TestDrive extends Model
{
    use HasFactory;
    
    protected $fillable = ['testdrive_date', 'status', 'user_id', 'car_id'];

    protected $casts = [
        'testdrive_date' => 'date',
        'status' => 'string',
    ];

    public function setTestDriveDateAttribute($value)
    {
        if ($value) {
            $data = Carbon::createFromFormat('d-m-Y', $value)->format('Y-m-d');
            $this->attributes['testdrive_date'] = $data;
        } else {
            $this->attributes['testdrive_date'] = null;
        }
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }
    
}
