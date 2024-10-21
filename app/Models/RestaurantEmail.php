<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RestaurantEmail extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'mailer',
        'host',
        'port',
        'username',
        'password',
        'encryption',
        'address',
        'name',
        'created_by',
        'updated_by'
    ];
}
