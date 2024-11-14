<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalityTrait extends Model
{
    use HasFactory;

    protected $table = 'personality_traits';

    protected $fillable = [
        'name',
        'branch_id',
        'sequence_id',
        'status',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
