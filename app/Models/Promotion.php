<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Promotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'discount', 'start_date', 'end_date', 'car_id'
    ];

    protected $casts = [
        'title' => 'string',
        'description' => 'string',
        'discount' => 'integer',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function setStartDateAttribute($value)
    {
        if ($value) {
            $data = Carbon::createFromFormat('d-m-Y', $value)->format('Y-m-d');
            $this->attributes['start_date'] = $data;
        } else {
            $this->attributes['start_date'] = null;
        }
    }

    public function setEndDateAttribute($value)
    {
        if ($value) {
            $data = Carbon::createFromFormat('d-m-Y', $value)->format('Y-m-d');
            $this->attributes['end_date'] = $data;
        } else {
            $this->attributes['end_date'] = null;
        }
    }

    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }
}
