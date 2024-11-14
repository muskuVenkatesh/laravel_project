<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class TransportRouteStop extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'route_id',
        'stop_data',
        'status',
    ];

    protected $casts = [
        'stop_data' => 'array', 
    ];
}
