<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Approval extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'objectid', 
        'ds_approved', 
        'district_approved', 
        'national_approved'
    ];

    /**
     * Get the approval status as a human-readable string.
     */
    public function getApprovalStatusAttribute()
    {
        if ($this->national_approved) {
            return 'Approved by National Level';
        } elseif ($this->district_approved) {
            return 'Approved by District Level';
        } elseif ($this->ds_approved) {
            return 'Approved by DS Level';
        } else {
            return 'Pending Approval';
        }
    }
}
