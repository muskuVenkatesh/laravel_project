<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Medium extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table ='medium';
    protected $fillable = [
        'branch_id',
        'name',
    ];

    public static function createMedium($validatedData)
    {
       $data = Medium::create([
            'branch_id' => $validatedData['branch_id'],
            'name' => $validatedData['name'],
        ]);

        return $data;
    }

    public static function updateMedium($validatedData, $id)
    {
       $data = Medium::find($id);
       $data->update([
            'branch_id' => $validatedData['branch_id'],
            'name' => $validatedData['name'],
        ]);

        return $data;
    }
}
