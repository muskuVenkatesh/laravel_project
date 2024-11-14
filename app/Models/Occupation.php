<?php

namespace App\Models;

use App\Models\Parents;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Occupation extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'status',
    ];

    public function occupation()
    {
        return $this->belongsTo(Parents::class, 'occupation');
    }


    public function parentmotherOccupation()
    {
        return $this->belongsTo(Parents::class, 'mother_occupation');
    }
}
