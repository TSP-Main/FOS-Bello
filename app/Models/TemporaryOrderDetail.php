<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemporaryOrderDetail extends Model
{
    use HasFactory;

    // Define the table associated with the model
    protected $table = 'temporary_order_details';

    // Define the attributes that are mass assignable
    protected $fillable = [
        'temporary_order_id',
        'product_id',
        'product_title',
        'product_price',
        'quantity',
        'sub_total',
        'options',
    ];

    // Define the relationships
    public function temporaryOrder()
    {
        return $this->belongsTo(TemporaryOrder::class, 'temporary_order_id');
    }
}
