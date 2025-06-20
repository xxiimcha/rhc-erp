<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Franchise extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_code',
        'branch',
        'region',
        'location',
        'variant_id',
        'franchise_date',
        'end_of_contract',
        'franchisee_name',
        'email_address',
        'contact_number',
        'birthday',
        'home_address',
    ];

    public function staff()
    {
        return $this->hasMany(FranchiseStaff::class);
    }

    public function variant()
    {
        return $this->belongsTo(Variant::class);
    }
}
