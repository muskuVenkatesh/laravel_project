<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Qualifications extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'qualifications';
    protected $fillable = [
        'name',
    ];

    public function parents()
    {
        return $this->hasMany(Parents::class, 'educaation');
    }

    public function parentsMotherEducation()
    {
        return $this->hasMany(Parents::class, 'mother_education');
    }

    public static function StoreQualifications($data)
    {
        Qualifications::create([
            'name' => $data['name'],
        ]);
    }

    public static function UpdateQualifications($data, $id)
    {
        $qualification = Qualifications::find($id);

        if ($qualification) {
            $qualification->update([
                'name' => $data['name'],
            ]);

            return $qualification;
        }
        return "No Data Found.";
    }
}
