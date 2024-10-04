<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;

    public function getFormattedExpiryDateAttribute()
    {
        return \Carbon\Carbon::parse($this->attributes['expiry'])->format('d-m-Y');
    }
}
