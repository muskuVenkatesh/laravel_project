<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Departments extends Model
{
    use HasFactory;
    use SoftDeletes;


    protected $table ='departments';
    protected $fillable = [
        'name',
        'status',
    ];

    public static function createDepartment($validatedData)
    {
       $data = Departments::create([
            'name' => $validatedData['name'],
        ]);

        return $data;
    }

    public static function updateDepartment($validatedData, $id)
    {
       $data = Departments::find($id);
       $data->update([
            'name' => $validatedData['name'],
        ]);

        return $data;
    }
}
