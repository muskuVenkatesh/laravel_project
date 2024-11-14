<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Interfaces\StaffNotificationInterface;

class StaffNotificationController extends Controller
{
    protected $staffNotificationRepository;

    public function __construct(StaffNotificationInterface $staffNotificationRepository)
    {
        $this->staffNotificationRepository = $staffNotificationRepository;
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'type_id' => 'required',
            'notification_type' => 'required',
            'notification_data_id' => 'required',
            'staff_id' => 'required',
            'notification_message' => 'required',
        ]);
        $notification = $this->staffNotificationRepository->storeNotification($validatedData);
        return $notification;
    }
}
