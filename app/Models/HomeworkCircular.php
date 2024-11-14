<?php

namespace App\Models;

use App\Models\Staff;
use App\Models\Parents;
use App\Models\Student;
use App\Models\Branches;
use App\Mail\CircularMail;
use App\Models\Notification;
use App\Models\NotificationLog;
use App\Models\NotificationType;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HomeworkCircular extends Model
{
    use HasFactory;
    protected $table = 'homework_circular';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'circular_type',
        'notification_type',
        'message',
        'file',
        'status',
    ];

    public static function createHomeworkCircular($validatedData)
    {
        $filepath = null;
        if (isset($validatedData['file']) && $validatedData['file']->isValid()) {
            $filepath = $validatedData['file']->store('file', 'public');
        }
        $circularData = [];
        $notificationLogs = [];
        $notifications = [];
        $allNotificationTypes = NotificationType::all()->keyBy('id');

        $circularTypes = $validatedData['circular_type'] ?? [];
        $notificationTypeIds = $validatedData['notification_type'] ?? [];

        foreach ($notificationTypeIds as $typeId) {
            if (!isset($allNotificationTypes[$typeId])) {
                return ['error' => 'One or more notification type records not found'];
            }
        }

        foreach ($circularTypes as $circularType) {
            switch ($circularType) {
                case 'Staff':
                    $staffList = Staff::all();
                    foreach ($staffList as $staff) {
                        foreach ($notificationTypeIds as $typeId) {
                            $notificationTypeName = $allNotificationTypes[$typeId]->type ?? 'unknown';
                            $data = [
                                'homework_id' => $validatedData['homework_id'] ?? null,
                                'student_id' => $validatedData['student_id'] ?? null,
                                'circular_type' => 'staff_' . $staff->id,
                                'notification_type' => $notificationTypeName,
                                'message' => $validatedData['message'] ?? '',
                                'file' => $filepath ?? '',
                            ];
                            $circularData[] = $data;
                            $staffData = [
                                'name' => $staff->first_name,
                                'email' => $staff->email,
                                'message' => $validatedData['message'] ?? '',
                                'file' => $filepath ?? '',
                            ];

                            $notification = Notification::create([
                                'type_id' => $typeId,
                                'notification_data_id' => $validatedData['homework_id'] ?? null,
                                'notification_type' => 'Circular',
                                'template_id' => 1,
                                'status' => 1,
                            ]);
                            $notifications[] = $notification->id;
                            $notificationLogs[] = [
                                'notification_id' => $notification->id,
                                'type_id' => $typeId,
                                'student_id' => null,
                                'send_to' => $staff->email,
                                'msg_sender' => 'system',
                                'msg_status' => 'sent',
                            ];
                            Mail::to($staff->email)->send(new CircularMail('staff', $staffData, $circularData));
                        }
                    }
                    break;

                    case 'Branch':
                    $branchList = Branches::all();
                    foreach ($branchList as $branch) {
                        foreach ($notificationTypeIds as $typeId) {
                            $notificationTypeName = $allNotificationTypes[$typeId]->type ?? 'unknown';
                            $data = [
                                'homework_id' => $validatedData['homework_id'] ?? null,
                                'student_id' => $validatedData['student_id'] ?? null,
                                'circular_type' => 'branch_' . $branch->id,
                                'notification_type' => $notificationTypeName,
                                'message' => $validatedData['message'] ?? '',
                                'file' => $filepath ?? '',
                            ];
                            $circularData[] = $data;
                            $branchData = [
                                'name' => $branch->branch_name,
                                'email' => $branch->email,
                                'message' => $validatedData['message'] ?? '',
                                'file' => $filepath ?? '',
                            ];
                            $notification = Notification::create([
                                'type_id' => $typeId,
                                'notification_data_id' => $validatedData['homework_id'] ?? null,
                                'notification_type' => 'Circular',
                                'template_id' => 1,
                                'status' => 1,
                            ]);
                            $notifications[] = $notification->id;
                            $notificationLogs[] = [
                                'notification_id' => $notification->id,
                                'type_id' => $typeId,
                                'student_id' => null,
                                'send_to' => $branch->email,
                                'msg_sender' => 'system',
                                'msg_status' => 'sent',
                            ];
                            Mail::to($branch->email)->send(new  CircularMail('branch', $branchData, $circularData));
                        }
                    }
                    break;
                    case 'Parent':
                        $parentList = Parents::all();
                        foreach ($parentList as $parent) {
                            $user = User::find($parent->user_id);
                            if ($user && $user->email) {
                                foreach ($notificationTypeIds as $typeId) {
                                    $notificationTypeName = $allNotificationTypes[$typeId]->type ?? 'unknown';
                                    $data = [
                                        'homework_id' => $validatedData['homework_id'] ?? null,
                                        'student_id' => $validatedData['student_id'] ?? null,
                                        'circular_type' => 'parent_' . $parent->id,
                                        'notification_type' => $notificationTypeName,
                                        'message' => $validatedData['message'] ?? '',
                                        'file' => $filepath ?? '',
                                    ];
                                    $circularData[] = $data;
                                    $parentData = [
                                        'name' => $parent->first_name,
                                        'email' => $user->email,
                                        'message' => $validatedData['message'] ?? '',
                                        'file' => $filepath ?? '',
                                    ];
                                    $notification = Notification::create([
                                        'type_id' => $typeId,
                                        'notification_data_id' => $validatedData['homework_id'] ?? null,
                                        'notification_type' => 'Circular',
                                        'template_id' => 1,
                                        'status' => 1,
                                    ]);
                                    $notifications[] = $notification->id;
                                    $notificationLogs[] = [
                                        'notification_id' => $notification->id,
                                        'type_id' => $typeId,
                                        'student_id' => null,
                                        'send_to' => $user->email,
                                        'msg_sender' => 'system',
                                        'msg_status' => 'sent',
                                    ];
                                    Mail::to([$user->email, $parent->mother_email])->send(new  CircularMail('parent', $parentData, $circularData));
                                }
                            }
                        }
                        break;
            }
        }
        HomeworkCircular::insert($circularData);
        NotificationLog::insert($notificationLogs);

        return $circularData;
    }
}
