<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RestaurantStripeConfig extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'stripe_key',
        'stripe_secret',
        'stripe_webhook_secret',
        'created_by',
        'updated_by'
    ];
}
