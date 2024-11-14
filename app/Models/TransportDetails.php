<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\PilotDetails;

class TransportDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'student_id',
        'vehicle_id',
        'pilot_id',
        'transport',
        'status',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branches::class);
    }

    public function route(): BelongsTo
    {
        return $this->belongsTo(TransportRoute::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(TransportVehicleDetails::class);
    }

    public function pilot(): BelongsTo
    {
        return $this->belongsTo(PilotDetails::class);
    }

    public static function createTransport($validated)
    {
        $branchId = $validated['branch_id'];
        foreach ($validated['transport'] as $transport) {
            $pilotDetail = PilotDetails::where('route_id', $transport['route_id'])->first(['vehicle_id', 'id']);

            if ($pilotDetail) {
                foreach ($transport['student_ids'] as $studentId) {
                    $isSameDrop = 0;
                    $extraDropDetails = null;

                    if (is_array($transport['is_same_drop'])) {
                        $isSameDrop = 1;
                        $extraDropDetails = [
                            'route_id' => $transport['is_same_drop']['route_id'] ?? null,
                            'drop_point' => $transport['is_same_drop']['drop_point'] ?? null,
                        ];
                    } elseif (is_numeric($transport['is_same_drop'])) {
                        $isSameDrop = $transport['is_same_drop'];
                    }

                 $transportdetails = TransportDetails::create([
                        'branch_id' => $branchId,
                        'student_id' => $studentId,
                        'vehicle_id' => $pilotDetail->vehicle_id,
                        'pilot_id' => $pilotDetail->id,
                        'transport' => json_encode([
                            'route_id' => $transport['route_id'],
                            'pickup_point' => empty($transport['pickup_point']) ? null : $transport['pickup_point'],
                            'drop_point' => empty($transport['drop_point']) ? null : $transport['drop_point'],
                            'is_same_drop' => $isSameDrop,
                            'extra_drop_details' => $extraDropDetails
                        ])
                    ]);
                }
                return $pilotDetail->id;
            }
        }
        return null;
    }

    public function updateTransport(array $data): bool
    {
        return $this->update($data);
    }
}
