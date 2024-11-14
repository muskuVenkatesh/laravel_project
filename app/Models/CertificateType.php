<?php

namespace App\Models;

use App\Models\CertificateTypesField;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CertificateType extends Model
{
    use HasFactory;
    protected $table = 'certificate_types';

    protected $fillable = [
        'certificate_type',
        'file_path',  
        'status',
    ];
    
    public function fields()
    {
        return $this->hasMany(CertificateTypesField::class, 'certificate_type_id');
    }
}
