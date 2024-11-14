<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\DateService;

class FeesAcademicSetup extends Model
{
    use HasFactory;

    protected $table = 'fees_academic_setups';
    protected $fillable = [
        'school_id',
        'branch_id',
        'class_id',
        'section_id',
        'template_id',
        'parent_recipet',
        'academic_id',
        'amount',
        'discount',
        'discount_type',
        'pay_timeline',
        'pay_timeline_date',
        'status',
    ];

    public function idCardTemplate()
    {
        return $this->belongsTo(IdCardTemplate::class, 'template_id');
    }
    
    public function createAcademicSetup($data)
    {
        $dateService = new DateService();

        $newAcademicSetup = FeesAcademicSetup::create([
            'school_id' => $data['school_id'],
            'branch_id' => $data['branch_id'],
            'class_id' => $data['class_id'],
            'section_id' => $data['section_id'],
            'template_id' => $data['template_id'] ?? 1,
            'parent_recipet' => $data['parent_recipet'] ?? 1,
            'academic_id' => $data['academic_id'],
            'amount' => $data['amount'],
            'discount' => $data['discount'] ?? 0,
            'discount_type' => $data['discount_type'] ?? null,
            'pay_timeline' => $data['pay_timeline'],
            'pay_timeline_date' => $dateService->formatDate($data['pay_timeline_date']) ?? null,
        ]);
        return $newAcademicSetup->id;
    }

    public function updateAcademicPayments($data, $id)
    {
        $dateService = new DateService();

        $newAcademicSetup = FeesAcademicSetup::find($id);
        $newAcademicSetup->update([
            'amount' => $data['amount'],
            'discount' => $data['discount'] ?? 0,
            'discount_type' => $data['discount_type'] ?? null
        ]);
    }
}
