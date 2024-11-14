<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FeesTimeline extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'fees_timeline';
    protected $fillable = ['name', 'installments', 'status'];
}
