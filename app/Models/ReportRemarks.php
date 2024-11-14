<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportRemarks extends Model
{
    protected $table = 'report_remarks';
    protected $fillable = ['name', 'remarks_by','status'];
}
