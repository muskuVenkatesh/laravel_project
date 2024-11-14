<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CurrencyTypes extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table ='currency_types';
    protected $fillable = [
        'name',
        'symbol',
        'status'
    ];

}
