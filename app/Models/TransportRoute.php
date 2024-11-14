<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Models\Branches;

class TransportRoute extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'branch_id',
        'start_point',
        'end_point',
        'start_latitude',
        'start_logitude',
        'end_latitude',
        'end_logitude',
        'distance',
        'status'
    ];

    public function branch()
    {
        return $this->belongsTo(Branches::class);
    }
}
