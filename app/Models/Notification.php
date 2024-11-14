<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Notification extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'type_id',
        'notification_data_id',
        'notification_type',
        'template_id',
        'status'
    ];

    public static function createNotification($notificationdata)
    {
        return DB::table('notifications')->insertGetId($notificationdata);
    }

    public static function getNotificationType($notification_data_id)
    {
        return Notification::where('notification_data_id', $notification_data_id)->value('type_id');
    }
}
