<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtpValidation extends Model
{
    use HasFactory;

    protected $table = 'otp_validations';
    protected $fillable = [
        'otp_token',
        'otp',
        'validate_at'
    ];

    public function Store($otp_token, $otp)
    {
        return OtpValidation::create([
            'otp_token' =>$otp_token,
            'otp' => $otp,
        ]);
    }
}
