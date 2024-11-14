<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamReportLock extends Model
{
    use HasFactory;
    protected $table = 'exam_report_locks';
    protected $fillable = [
        'name',
        'value',
        'status',
    ];  
}
