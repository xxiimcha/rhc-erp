<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    // Define fillable fields for mass assignment
    protected $fillable = [
        'name',
        'date',
        'year',
        'description',
        'source',
    ];

    // Optionally specify table name (only if your table is not "holidays")
    // protected $table = 'holidays';

    // Automatically cast date fields to Carbon instances
    protected $casts = [
        'date' => 'date',
        'year' => 'integer',
    ];
}
