<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AcademicDetail extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'academic_years', 'start_date', 'end_date', 'academic_description',
    ];



}
