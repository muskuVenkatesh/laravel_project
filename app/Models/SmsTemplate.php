<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SmsTemplate extends Model
{
    use SoftDeletes;
    protected $table = 'sms_templates';

    protected $fillable = [
        'sms_type',
        'sms',
        'status',
    ];
    public function Store($data)
    {
        return SmsTemplate::create([
            'sms_type' => $data['sms_type'],
            'sms' => $data['sms'],
        ]);
    }

    public function updatetemplate($id, $data)
    {
        $smsTemplate = SmsTemplate::findOrFail($id);

        $smsTemplate->update([
            'sms_type' => $data['sms_type'],
            'sms' => $data['sms'],
        ]);

        return "Update Successfully.";
    }
}
