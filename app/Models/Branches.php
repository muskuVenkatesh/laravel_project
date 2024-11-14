<?php

namespace App\Models;

use App\Models\Classes;
use App\Models\Parents;
use App\Models\Schools;
use App\Models\BranchSubject;
use App\Models\AcademicSchoolSetup;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Branches extends Model
{
    use SoftDeletes;
    use HasFactory;
    protected $fillable = [
        'school_id',
        'academic_id',
        'branch_name',
        'branch_code',
        'email',
        'phone',
        'address',
        'state',
        'city',
        'dist',
        'pin',
    ];

    public function school()
    {
        return $this->belongsTo(Schools::class, 'school_id');
    }

    public function parents()
    {
        return $this->hasMany(Parents::class, 'branch_id');
    }

    public function classes()
    {
        return $this->hasMany(Classes::class, 'branch_id');
    }
    public function branchsubjects()
    {

        return $this->hasMany(BranchSubject::class, 'branch_id');
    }

    public static function createBranch($validatedData,$school_id)
    {
        $branch = Branches::where('school_id',$school_id)
            ->where('branch_code',$validatedData['branch_code'])
            ->first();
        $branch = Branches::create([
            'branch_name' => $validatedData['branch_name'],
            'school_id' => $school_id,
            'academic_id' => $validatedData['academic_id'],
            'address' => $validatedData['branch_address'],
            'branch_code' => $validatedData['branch_code'],
            'dist' => $validatedData['branch_dist'],
            'city' => $validatedData['branch_city'],
            'state' => $validatedData['branch_state'],
            'pin' => $validatedData['branch_pin'],
            'phone' => $validatedData['branch_phone'],
            'email' => $validatedData['branch_email'],
            'report_card_template' => $validatedData['report_card_template'] ?? null,  // Handle missing key
            'receipt_template' => $validatedData['receipt_template'] ?? null,          // Handle missing key
            'id_card_template' => $validatedData['id_card_template'] ?? null,
        ]);

        AcademicSchoolSetup::createSetup($validatedData,$branch->id,$school_id);
        return $branch;
    }

    public static function updateBranch($validatedData, $school_id)
    {
        $branch = Branches::where('school_id', $school_id)->first();

        if ($branch) {
            $branch->update([
                'branch_name' => $validatedData['branch_name'] ?? $branch->branch_name,
                'academic_id' => $validatedData['academic_id'] ?? $branch->academic_id,
                'address' => $validatedData['branch_address'] ?? $branch->address,
                'branch_code' => $validatedData['branch_code'] ?? $branch->branch_code,
                'dist' => $validatedData['branch_dist'] ?? $branch->dist,
                'city' => $validatedData['branch_city'] ?? $branch->city,
                'state' => $validatedData['branch_state'] ?? $branch->state,
                'pin' => $validatedData['branch_pin'] ?? $branch->pin,
                'phone' => $validatedData['branch_phone'] ?? $branch->phone,
                'email' => $validatedData['branch_email'] ?? $branch->email,
            ]);

            return $branch;
        }
        return null;
    }


    public static function updateBranchById($validatedData, $branchId)
    {
        $branch = Branches::where('id',$branchId)->first();
        if ($branch) {
            $branch->update([
                'branch_name' => $validatedData['branch_name'],
                'address' => $validatedData['branch_address'],
                'branch_code' => $validatedData['branch_code'],
                'dist' => $validatedData['branch_dist'],
                'city' => $validatedData['branch_city'],
                'state' => $validatedData['branch_state'],
                'pin' => $validatedData['branch_pin'],
                'phone' => $validatedData['branch_phone'],
                'email' => $validatedData['branch_email'],
            ]);

            return $branch;
        }
        return null;
    }

    public static function getBranchCode($branch_id)
    {
        return Branches::where('id',$branch_id)->value('branch_code');
    }
}
