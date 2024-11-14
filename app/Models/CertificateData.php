<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CertificateData extends Model
{
    use HasFactory;
    protected $table = 'certificate_data';

    protected $fillable = [
        'certificate_type',
        'school_id',
        'branch_id',
        'student_id',
        'cert_data',
        'status',
    ];

    protected $casts = [
        'cert_data' => 'array',
    ];
}
