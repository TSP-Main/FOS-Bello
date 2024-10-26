<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApiTokenLog extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['company_id', 'reason', 'new_token'];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
