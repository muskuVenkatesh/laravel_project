<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeworkDetails extends Model
{
    use HasFactory;

    protected $table ='homework_details';
    protected $fillable = [
        'homework_id',
        'subject_id',
        'homework_data',
        'classwork_data',
        'books_carry',
        'homework_file',
        'status'
    ];

    public static function storeHomeworkDetails($homework_id, $data)
    {
        foreach ($data['work_data'] as $subject_id => $work)
        {
            $filePath = null;

            if (isset($work['file'])) {
                $file = $work['file'];
                $filePath = $file->store('homework_files', 'public');
            }
            HomeworkDetails::create([
                'homework_id' => $homework_id,
                'subject_id' => $subject_id,
                'homework_data' => $work['HW'] ?? null,
                'classwork_data' => $work['CW'] ?? null,
                'books_carry' => $work['BCR'] ?? null,
                'homework_file' => $filePath,
                'status' => 1
            ]);
        }
    }
}
