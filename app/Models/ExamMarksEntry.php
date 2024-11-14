<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Student;



class ExamMarksEntry extends Model
{
    use HasFactory;

    protected $table ='exam_marks_entries';
    protected $fillable = [
        'branch_id',
        'student_id',
        'marks_data',
        'status',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id'); 
    }
}
