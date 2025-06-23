<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicePricelist extends Model
{
    use HasFactory;

    protected $fillable = [
        'category',
        'service_name',
        'length_type',
        'price',
        'is_quoted',
    ];
}
