<?php

namespace App\Models;

use App\Models\BranchSubject;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subjects extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table ='subjects';
    protected $fillable = [
        'name',
    ];

    public function branchSubjects()
    {
        return $this->hasMany(BranchSubject::class,'subject_id');
    }

    public static function createSubject($validatedData)
    {
       $data = Subjects::create([
            'name' => $validatedData['name'],
        ]);

        return $data;
    }

    public static function updateSubject($validatedData, $id)
    {
       $data = Subjects::find($id);
       $data->update([
            'name' => $validatedData['name'],
        ]);

        return $data;
    }
}
