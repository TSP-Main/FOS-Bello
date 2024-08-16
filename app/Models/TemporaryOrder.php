<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemporaryOrder extends Model
{
    use HasFactory;

    // Define the table associated with the model
    protected $table = 'temporary_orders';

    // Define the attributes that are mass assignable
    protected $fillable = [
        'company_id',
        'name',
        'email',
        'phone',
        'address',
        'total',
        'order_type',
        'payment_option',
        'status',
    ];

    // Define the relationships
    public function details()
    {
        return $this->hasMany(TemporaryOrderDetail::class, 'temporary_order_id');
    }
}
