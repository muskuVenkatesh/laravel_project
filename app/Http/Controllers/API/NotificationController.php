<?php

namespace App\Http\Controllers\API;

use App\Models\Notification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Exceptions\DataNotFoundException;
use App\Interfaces\NotificationInterface;

class NotificationController extends Controller
{
    protected $notificationInterface;

    public function __construct(NotificationInterface $notificationInterface)
    {
        $this->notificationInterface = $notificationInterface;
    }

    public function getNotificationlogs(Request $request){
        $notification_type = $request->input('type_id');
        $notificationlog = $this->notificationInterface->getNotificationlogs($notification_type);
        if(!empty($notificationlog) && count($notificationlog)>0 )
        {
            return response()->json([
                'data' => $notificationlog
            ],200);
        }
        throw new DataNotFoundException('NotificationLog Records Not Found');
    }
}

