<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FeesPayType extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'fees_pay_types';
    protected $fillable = [
        'name',
        'status',
    ];
}
