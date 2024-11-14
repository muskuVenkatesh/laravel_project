<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;



class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'roleid',
        'status'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

// Rest omitted for brevity

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function parents()
    {
        return $this->hasMany(Parents::class, 'user_id');
    }

    public static function createUsers($data)
    {
       $user = User::create([
          'name' => $data['first_name'] ?? $data['pfirst_name'],
          'email' => $data['email'],
          'roleid' => $data['role_id'] ?? 6,
          'phone' => $data['phone'] ?? null,
          'status' => 1,
          'password' =>  Hash::make('password'),
        ]);

        return $user->id;

    }

    public static function updateUsers($data, $id)
    {
        $user = User::find($id);
        $user->update([
            'name' => $data['first_name'],
            'email' => $data['email'],
            'roleid' => $data['role_id'] ?? 6,
            'status' => 1,
            'password' =>  Hash::make('password'),
          ]);
    }
}

