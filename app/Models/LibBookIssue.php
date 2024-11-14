<?php

namespace App\Models;

use App\Models\User;
use App\Models\LibBook;
use App\Models\Student;
use App\Services\DateService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LibBookIssue extends Model
{
    use HasFactory;

    protected $table = 'lib_book_issue';

    protected $fillable = [
        'book_id',
        'student_id',
        'staff_user_id',
        'issue_date',
        'return_status',
        'reciever_id',
        'return_staff_user_id',
        'return_date',
        'return_comments',
        'status'
    ];

    public function createLibBookIssue($data){
        $dateFormatService = new DateService;
        return LibBook::create([
                'book_id' => $data['book_id'],
                'user_id' => $data['user_id'],
                'issuer_id' => $data['issuer_id'],
                'issue_date' => $dateFormatService->formatDate($data['issue_date']),
                'return_status' => $data['return_status'],
            ]);
        }

    public function book()
    {
        return $this->belongsTo(LibBook::class, 'book_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_user_id');
    }
    public function returnStaff()
    {
        return $this->belongsTo(User::class, 'return_staff_user_id');
    }
}
