<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Option extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'company_id',
        'is_required',
        'option_type',
        'created_by',
        'updated_by',
    ];

    public function option_values()
    {
        return $this->hasMany(OptionValue::class);
    }
}
