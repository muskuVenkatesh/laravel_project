<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Schools extends Model
{
    use HasFactory;
    use SoftDeletes;
    // protected $table = ''
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'address',
        'school_code',
        'affialiation_no',
        'state',
        'status',
        'dist',
        'pin',
        'city',
    ];

    public function branches()
    {
        return $this->hasMany(Branches::class, 'school_id');
    }

    public static function createSchool($validatedData)
    {

       $school = Schools::create([
            'name' => $validatedData['name'],
            'address' => $validatedData['address'],
            'school_code' => $validatedData['school_code'],
            'affialiation_no' =>$validatedData['affialiation_no'] ?? null,
            'dist' => $validatedData['dist'],
            'city' => $validatedData['city'],
            'state' => $validatedData['state'],
            'pin' => $validatedData['pin'],
       ]);

        return $school->id;
    }

    public static function updateSchool($id, $validatedData)
    {

        $school = Schools::find($id);
        if ($school) {
            $school->update([
                'name' => $validatedData['name'],
                'address' => $validatedData['address'],
                'school_code' => $validatedData['school_code'],
                'dist' => $validatedData['dist'],
                'city' => $validatedData['city'],
                'state' => $validatedData['state'],
                'pin' => $validatedData['pin'],
            ]);
            return $school;
        }
        return null;
    }




}
