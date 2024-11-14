<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Services\DateService;
class UserDetails extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'user_details';

    protected $fillable = [
        'user_id',
        'branch_id',
        'date_of_birth',
        'gender',
        'blood_group',
        'religion',
        'cast',
        'image',
        'mother_tongue',
        'aadhaar_card_no',
        'pan_card_no',
        'address',
        'city',
        'state',
        'country',
        'pin',
        'tmp_address',
        'temp_city',
        'temp_state',
        'temp_pin',
        'temp_country',
    ];

    public static function createUserDetails($data, $user_id)
    {
         $dateService = new DateService();
         $imagePath = null;
        if (isset($data['image']) && $data['image']->isValid()) {
            $imagePath = $data['image']->store('images', 'public');
        }
            UserDetails::create([
                'user_id' => $user_id,
                'branch_id' => $data['branch_id'],
                'date_of_birth' => $dateService->formatDate($data['date_of_birth']) ?? null,
                'gender' =>  $data['gender'] ?? null,
                'blood_group' => $data['blood_group'] ?? null,
                'religion' => $data['religion'] ?? null,
                'cast' => $data['cast'] ?? null,
                'image' => $imagePath ?? null,
                'mother_tongue' => $data['mother_tongue'] ?? null,
                'aadhaar_card_no' => $data['aadhaar_card_no'] ?? null,
                'pan_card_no' => $data['pan_card_no'] ?? null,
                'address' => $data['address'] ?? null,
                'city' => $data['city'] ?? null,
                'state' => $data['state'] ?? null,
                'country' => $data['country'] ?? null,
                'pin' => $data['pin'] ?? null,
                'tmp_address' => $data['tmp_address'] ?? null,
                'temp_city' => $data['temp_city'] ?? null,
                'temp_state' => $data['temp_state'] ?? null,
                'temp_pin' => $data['temp_pin'] ?? null,
                'temp_country' => $data['temp_country'] ?? null,
            ]);
    }
    public static function createUserDetailsForStudent($data, $user_id)
    {
        $dateService = new DateService();
         $imagePath = null;
        if (isset($data['stimage']) && $data['stimage']->isValid()) {
            $imagePath = $data['stimage']->store('stimages', 'public');
        }
            UserDetails::create([
                'branch_id' => $data['branch_id'],
                'user_id' => $user_id,
                'date_of_birth' => $dateService->formatDate($data['stdate_of_birth']) ?? null,
                'gender' =>  $data['stgender'] ?? null,
                'blood_group' => $data['stblood_group'] ?? null,
                'religion' => $data['streligion'] ?? null,
                'cast' => $data['stcast'] ?? null,
                'image' => $imagePath  ?? null,
                'aadhaar_card_no' => $data['staadhaar_card_no'] ?? null,
                'pan_card_no' => $data['stpan_card_no'] ?? null,
                'address' => $data['staddress'],
                'city' => $data['stcity'],
                'state' => $data['ststate'],
                'country' => $data['stcountry'] ?? null,
                'pin' => $data['stpin'],
                'tmp_address' => $data['sttmp_address'] ?? null,
                'temp_city' => $data['sttemp_city'] ?? null,
                'temp_state' => $data['sttemp_state'] ?? null,
                'temp_pin' => $data['sttemp_pin'] ?? null,
                'temp_country' => $data['sttemp_country'] ?? null,
            ]);
    }

    public static function updateUserDetails($user_id, $data)
    {
        $dateService = new DateService();
        $userDetails = UserDetails::where('user_id', $user_id)->first();
        $imagePath = $userDetails->image ?? "";
        if (isset($data['image']) && $data['image']->isValid()) {
            $imagePath1 = $data['image']->store('images', 'public');
        }
        $userDetails->update([
            'date_of_birth' => $dateService->formatDate($data['date_of_birth']) ?? $userDetails->date_of_birth,
            'gender' => $data['gender'],
            'blood_group' => $data['blood_group'] ?? null,
            'religion' => $data['religion'] ?? null,
            'cast' => $data['cast'] ?? null,
            'image' => $imagePath1 ?? $imagePath,
            'mother_tongue' => $data['mother_tongue'] ?? null,
            'aadhaar_card_no' => $data['aadhaar_card_no'] ?? null,
            'pan_card_no' => $data['pan_card_no'] ?? null,
            'address' => $data['address'],
            'city' => $data['city'],
            'state' => $data['state'],
            'country' => $data['country'] ?? null,
            'pin' => $data['pin'],
            'tmp_address' => $data['tmp_address'] ?? null,
            'temp_city' => $data['temp_city'] ?? null,
            'temp_state' => $data['temp_state'] ?? null,
            'temp_pin' => $data['temp_pin'] ?? null,
            'temp_country' => $data['temp_country'] ?? null,
        ]);
    }
}

