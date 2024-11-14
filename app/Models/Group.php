<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Group extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'groups';
    protected $fillable = [
        'branch_id',
        'name',
    ];

    public static function StoreGroups($data)
    {
        $data = Group::create([
            'branch_id' => $data['branch_id'],
            'name' => $data['name'],
        ]);
        return $data;
    }

    public static function UpdateGroup($data, $id)
    {
        $group = Group::find($id);
        $group->update([
            'branch_id' => $data['branch_id'],
            'name' => $data['name'],
        ]);
        return $group;
    }
}
