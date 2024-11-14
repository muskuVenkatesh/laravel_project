<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ExamReportLock;

class ExamReportLockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $subjectTypes = [
            ['name' => 'Immediate After Submit', 'value'=>'0'],
            ['name' => 'After 24 Hours', 'value'=>'1'],
            ['name' => '2 Days', 'value'=>'2'],
            ['name' => '5 Days', 'value'=>'5'],
            ['name' => 'After a Week', 'value'=>'7'],
        ];
        ExamReportLock::insert($subjectTypes);
    }
}
