<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    protected $fillable = ['brand', 'model', 'year', 'price', 'type', 'description', 'image_url'];

    protected $casts = [
        'brand' => 'string',
        'model' => 'string',
        'year' => 'integer',
        'price' => 'decimal:2',
        'type' => 'string',
        'description' => 'string',
        'image_url' => 'string',
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
