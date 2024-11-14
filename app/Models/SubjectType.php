<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubjectType extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'subject_types';

    protected $fillable = [
        'name',
        'status',
    ];
}
