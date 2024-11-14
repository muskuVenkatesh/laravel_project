<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\NotificationCreated;
use App\Listeners\SendAttendanceEmail;

use App\Events\HomeworkNotification;
use App\Listeners\SendHomeworkEmail;

use App\Events\SGNotificationCreated;
use App\Listeners\SGNotificationEmail;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        NotificationCreated::class => [
            SendAttendanceEmail::class,
        ],
        HomeworkNotification::class => [
            SendHomeworkEmail::class,
        ],

        SGNotificationCreated::class => [
            SGNotificationEmail::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}
