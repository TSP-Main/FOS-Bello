<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 
        'email', 
        'status', 
        'expiry_date', 
        'updated_by', 
        'timezone',
        'address',
        'city',
        'postcode',
        'radius',
        'latitude',
        'longitude',
        'free_shipping_amount',
        'currency',
        'currency_symbol',
        'package',
        'plan',
        'customer_stripe_id',
        'payment_method_id',
        'is_enable'
    ];

    public function getFormattedExpiryDateAttribute()
    {
        return \Carbon\Carbon::parse($this->attributes['expiry_date'])->format('d-m-Y');
    }

    public function getFormattedAcceptedDateAttribute()
    {
        return \Carbon\Carbon::parse($this->attributes['accepted_date'])->format('d-m-Y');
    }
}
