<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeworkSpecialInstruction extends Model
{
    use HasFactory;
    protected $table ='homework_special_instructions';
    protected $fillable = [
        'homework_id',
        'student_id',
        'message',
        'status'
    ];

    public static function storeHomeworkSpecialInstruction($homework_id, $data)
    {
        if(!empty($data['special_instruction_student']))
        {
            $student_id = is_array($data['special_instruction_student'])
            ? implode(', ', $data['special_instruction_student'])
            : $data['special_instruction_student'];

            HomeworkSpecialInstruction::create([
                'homework_id' => $homework_id,
                'student_id' => $student_id,
                'message' => $data['special_instruction_message']
            ]);
        }
        else{
            if (isset($data['indivedual_student']) && is_array($data['indivedual_student'])) {
                $specialInstructionMessages = isset($data['indivedual_message']) && is_array($data['indivedual_message'])
                    ? $data['indivedual_message']
                    : [];

                foreach ($data['indivedual_student'] as $key => $student_id) {
                    $message = isset($specialInstructionMessages[$key]) ? $specialInstructionMessages[$key] : '';
                    HomeworkSpecialInstruction::create([
                        'homework_id' => $homework_id,
                        'student_id' => $student_id,
                        'message' => $message
                    ]);
                }
            } else {
                $message = isset($data['indivedual_message'][0]) ? $data['indivedual_message'][0] : '';
                HomeworkSpecialInstruction::create([
                    'homework_id' => $homework_id,
                    'student_id' => isset($data['indivedual_student']) ? $data['indivedual_student'] : '',
                    'message' => $message
                ]);
            }
        }
    }
}
