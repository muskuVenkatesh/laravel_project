<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicSchoolSetup extends Model
{
    use HasFactory;
    protected $table ='academic_school_setups';
    protected $fillable = [
        'academic_id',
        'school_id',
        'branch_id',
    ];

    public static function createSetup($data, $branchId, $school_id)
    {
        AcademicSchoolSetup::create([
            'academic_id' => $data['academic_id'],
            'school_id' => $school_id,
            'branch_id' => $branchId,
        ]);
    }
}
