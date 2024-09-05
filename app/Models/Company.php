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
        'subscription_date', 
        'updated_by', 
        'timezone',
        'address',
        'city',
        'postcode',
        'radius',
        'latitude',
        'longitude',
    ];
}
