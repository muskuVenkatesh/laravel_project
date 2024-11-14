<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\NotificationType;

class NotificationTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $type = [
            [
                'type' => 'Email',
                'status' => 1
            ],
            [
                'type' => 'App',
                'status' => 1
            ],
            [
                'type' => 'WhatsApp',
                'status' => 1
            ],
            [
                'type' => 'SMS',
                'status' => 1
            ]
        ];

        NotificationType::insert($type);
    }
}
