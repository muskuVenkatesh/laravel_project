<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CertificateTypesField extends Model
{
    use HasFactory;
    protected $table = 'certificate_types_fields';

    protected $fillable = [
        'certificate_type_id',
        'field_label',
        'field_name',
        'field_type',
        'status',
    ];
    public function certificateType()
    {
        return $this->belongsTo(CertificateType::class, 'certificate_type_id');
    }
}
