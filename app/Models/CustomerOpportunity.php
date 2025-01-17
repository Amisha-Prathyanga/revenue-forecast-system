<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomerOpportunity extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'accMngr_id',
        'category_id',
        'sub_category_id',
        'date',
        'revenue',
        'foreign_costs',
        'local_costs',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function accountManager()
    {
        return $this->belongsTo(User::class, 'accMngr_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class);
    }
}
