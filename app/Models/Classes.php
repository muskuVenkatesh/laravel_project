<?php

namespace App\Models;

use App\Models\Classes;
use App\Models\Section;
use App\Models\Branches;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Classes extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table ='classes';
    protected $fillable = [
        'branch_id',
        'name',
    ];

    public function branch()
    {
        return $this->belongsTo(Branches::class);
    }

    public function sections()
    {
        return $this->hasMany(Section::class, 'class_id');
    }

    public function branchsubjects()
    {
        return $this->hasMany(BranchSubject::class, 'class_id');
    }

    public static function createClass($validatedData)
    {
       $data = Classes::create([
            'branch_id' => $validatedData['branch_id'],
            'name' => $validatedData['name'],
        ]);

        return $data;
    }

    public static function updateClass($validatedData, $id)
    {
       $data = Classes::find($id);
       $data->update([
            'branch_id' => $validatedData['branch_id'],
            'name' => $validatedData['name'],
            'status' => $validatedData['status'] ?? 1,
        ]);

        return $data;
    }
}
