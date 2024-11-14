<?php

namespace App\Models;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class State extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'states';

    protected $fillable = [
        'name'
    ];

    public function createState($data){
        $state = State::create([
            'name' => $data['name'],
        ]);
        return $state;
    }

    public function updateState($id,$data){
        $state = State::findOrFail($id);
        $state->update([
            'name' => $data['name'],
        ]);
        return $state;
    }



}
