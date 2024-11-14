<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FeesType extends Model
{
    use SoftDeletes;
    protected $table = 'fees_types';
    protected $fillable = ['name', 'status'];
}
