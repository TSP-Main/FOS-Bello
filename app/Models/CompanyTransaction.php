<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyTransaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id', 'package', 'plan', 'amount', 'status', 'stripe_payment_intent_id', 'created_at' , 'updated_at' 
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
