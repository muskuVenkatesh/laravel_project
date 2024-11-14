<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Dist extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'dists';

    protected $fillable = [
        'name',
        'dist_code',
        'state_id'
    ];



    public static function createDistrict($data){
        $dist = Dist::create([
            'name' => $data['district'],
            'dist_code' => $data['districtCode'],
            'state_id' =>$data['state_id']
        ]);
        return $dist;
    }

    public function updateState($id,$data){
        $dist = Dist::findOrFail($id);
        $dist->update([
            'name' => $data['name'],
            'state_id' =>$data['state_id']
        ]);
        return $dist;
    }
}
