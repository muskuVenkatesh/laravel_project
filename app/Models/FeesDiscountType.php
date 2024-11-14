<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FeesDiscountType extends Model
{
    use HasFactory;
    use SoftDeletes;

        protected $table = 'fees_discount_type';

        protected $fillable = [
            'name',
            'amount',
            'status',
        ];
}
