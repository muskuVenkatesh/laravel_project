<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FeesAcademic extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'fees_academics';
    protected $fillable = [
        "academic_id",
        "fee_type",
        "fees_amount",
        "status"
    ];
}
