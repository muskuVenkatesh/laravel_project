<?php

namespace App\Models;

use App\Models\Branches;
use App\Models\Classes;
use App\Models\Section;
use Tymon\JWTAuth\Claims\Subject;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use DB;
class BranchSubject extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table ='branch_subjects';
    protected $fillable = [
        'branch_id',
        'class_id',
        'section_id',
        'subject_id',
        'subject_label',
        'subject_type',
        'subject_code',
        'status',
    ];

    public function subject()
    {
        return $this->belongsTo(Subjects::class, 'subject_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branches::class, 'branch_id');
    }

    public function classes()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }
    public function scection()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    public static function createBranchSubject($branchSubject)
    {
        return DB::table('branch_subjects')->insertGetId($branchSubject);
    }

}
