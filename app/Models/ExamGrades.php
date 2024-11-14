<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamGrades extends Model
{
    use HasFactory;
    protected $table = 'exam_grades'; 

    protected $fillable = [
        'branch_id',
        'class_id',
        'max_marks',
        'min_marks',
        'name',
        'status',
    ];
}
