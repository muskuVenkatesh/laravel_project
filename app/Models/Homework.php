<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Models\HomeworkDetails;
use App\Models\HomeworkSpecialInstruction;

class Homework extends Model
{
    use HasFactory;

    protected $table ='homework';
    protected $fillable = [
        'branch_id',
        'class_id',
        'section_id',
        'homework_type',
        'date',
        'status'
    ];

    public static function storeHomework($data)
    {
        $homework_ids = Homework::create([
            'branch_id' => $data['branch_id'],
            'class_id' => $data['class_id'],
            'section_id' => $data['section_id'],
            'homework_type' => is_array($data['homework_type']) ? implode(',', $data['homework_type']) : $data['homework_type'],
            'date' => Carbon::createFromFormat('d/m/Y', $data['date'])->format('Y-m-d'),
        ]);
        $homework_id = $homework_ids->id;
        
        return $homework_id;
    }
}
