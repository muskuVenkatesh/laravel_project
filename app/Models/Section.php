<?php

namespace App\Models;

use App\Models\Classes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Section extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'class_id',
        'name',
    ];

    public function branchsubjects()
    {
        return $this->hasMany(BranchSubject::class, 'section_id');
    }

    public function class()
    {
        return $this->belongsTo(Classes::class,'class_id');
    }



}
