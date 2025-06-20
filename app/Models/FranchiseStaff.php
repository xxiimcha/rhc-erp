<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FranchiseStaff extends Model
{
    use HasFactory;

    protected $fillable = [
        'franchise_id',
        'staff_name',
        'staff_designation',
    ];

    public function franchise()
    {
        return $this->belongsTo(Franchise::class);
    }
}
