<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'accMngr_id',
        'client_name',
        'industry_sector',
        'controlling_ministry',
        'ministry_contact',
        'key_client_contact_name',
        'key_client_contact_designation',
        'key_projects_or_sales_activity',
        'account_servicing_persons_initials',
    ];

    public function accountManager()
    {
        return $this->belongsTo(User::class, 'accMngr_id');
    }
}
