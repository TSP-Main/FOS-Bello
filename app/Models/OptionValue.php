<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OptionValue extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'option_id',
        'name',
        'price',
        'created_by',
        'updated_by',
    ];

    public function option()
    {
        return $this->belongsTo(Option::class);
    }
}
