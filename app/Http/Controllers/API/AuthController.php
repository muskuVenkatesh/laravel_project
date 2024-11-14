<?php

namespace App\Http\Controllers\API;

use App\Models\Role;
use App\Models\User;
use App\Models\Staff;
use App\Models\Parents;
use App\Models\Branches;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserLoginRequest;
use App\Exceptions\DataNotFoundException;
use App\Http\Requests\UserRegisterRequest;


class AuthController extends Controller
{

    public function register(UserRegisterRequest $request)
    {
        $validatedData = $request->validated();
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' =>  Hash::make($validatedData['password']),
            'roleid' => $request->roleid,
            'status' => 1
        ]);
        $token  = auth('api')->login($user);
        return $this->respondWithToken($token,$user);

    }

    public function login(UserLoginRequest $request)
    {
        $credentials = $request->validate([
            'login' => 'required',
            'password' => 'required',
        ]);
        if (filter_var($request->input('login'), FILTER_VALIDATE_EMAIL)) {
            $fieldType = 'email';
        } elseif (is_numeric($request->input('login'))) {
            $fieldType = 'phone';
        } else {
            $fieldType = 'username';
        }
        if (Auth::attempt([$fieldType => $credentials['login'], 'password' => $credentials['password']]))  {
            $user = Auth::user();
            if($user->status != 1)
            {
                return response()->json(['error' => 'User account is inactive'], 403);
            }
            else
            {
                $school_id = '';
                $role = Role::where('id', $user->roleid)->value('name');
                if($role == 'admin')
                {
                    $branchId = Staff::where('user_id', $user->id)->value('branch_id');
                    $school_id = Branches::where('id', $branchId)->value('school_id');
                }
                else if($role == 'management')
                {
                    $branchId = Staff::where('user_id', $user->id)->value('branch_id');
                    $school_id = Branches::where('id', $branchId)->value('school_id');
                }
                else if($role == 'parent')
                {
                    $branchId = Parents::where('user_id', $user->id)->value('branch_id');
                    $parentId = Parents::where('user_id', $user->id)->value('id');
                }
                $token = auth('api')->claims([
                    'user_id' => $user->id,
                    'user_name' => $user->name,
                    'user_email' => $user->email,
                    'user_roleid' => $user->roleid,
                    'school_id' => $school_id ?? null,
                    'branch_id' => $branchId ?? null,
                    'parent_id' => $parentId ?? null
                ])->login($user);
                return response()->json([
                    'access_token' => $token,
                ]);
            }
        }
        return response()->json(['error' => 'Invalid Cridentials'], 403);
    }


    public function logout()
    {
        auth()->logout();
        return response()->noContent();
    }

    public function refresh()
    {
        $newToken = auth('api')->refresh();
        $user = auth('api')->user();
        return $this->respondWithToken($user, $newToken);
    }


    protected function respondWithToken($token,$user)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'user' => $user
        ]);
    }

    public function getUserToken(UserLoginRequest $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token =  auth('api')->login($user);
            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => $user,
            ]);
        }
        return response()->json(['error' => 'Unauthorized'], 440);
    }

    public function checkToken(Request $request)
    {
        try {
            $token = $request->bearerToken();
            if (!$token) {
                return response()->json(['error' => 'Token not provided'], 400);
            }
            $decoded = JWTAuth::setToken($token)->toUser();
            $claims = JWTAuth::getPayload($token)->toArray();
            if (isset($claims['name'])) {
                $userName = $claims['name'];
            }
            return response()->json(['claims' => $claims]);

        } catch (JWTException $e) {
            return response()->json(['error' => 'Token is invalid'], 400);
        }
    }

    public function getUsers(Request $request)
    {
        $id = $request->input('user_id');
        $users = User::find($id);
        if (!$users) {
            throw new DataNotFoundException('Users not found');
        }
        if ($users->roleid != 1) {
            if (in_array($users->roleid, [3, 4, 5]))
            {
                $user_details = $this->getStaff($users->id);
                return response()->json([
                    'data' => $user_details
                ]);
            }
            else if ($users->roleid == 2)
            {
                $branch_id = $request->input('branch_id');
                $user_details = $this->getAdmin($users->id, $branch_id);
                return response()->json([
                     'data' => $user_details
                 ]);
            }
            else if ($users->roleid == 6)
            {
                $parent_details = $this->getParents($users->id);
                return response()->json([
                    'data' => $parent_details
                ]);
            }
        }
        $first_name = $users->name;
        $users->first_name = $first_name;
        unset($users->name);
        return response()->json(['data' => $users]);
    }

    public function getStaff($userId)
    {
        return Staff::where('staff.user_id', $userId)
        ->leftJoin('user_details', 'user_details.user_id', '=', 'staff.user_id')
        ->leftJoin('branches', 'branches.id', '=', 'staff.branch_id')
        ->leftJoin('qualifications', 'qualifications.id', '=', 'staff.qualification')
        ->leftJoin('branch_meta', function ($join) {
            $join->on('branch_meta.branch_id', '=', 'staff.branch_id')
                ->where('branch_meta.name', 'logo_file');
        })
        ->select(
            'branches.branch_name',
            'branch_meta.value as logo_file',
            'staff.*',
            'user_details.*',
            'qualifications.name as qualification_name'
        )
        ->first();
    }

    public function getParents($userId)
    {
        return Parents::where('parents.user_id', $userId)
        ->leftJoin('user_details', 'user_details.user_id', '=', 'parents.user_id')
        ->leftJoin('students', 'students.parent_id', '=', 'parents.id')
        ->leftJoin('branches', 'branches.id', '=', 'students.branch_id')
        ->leftJoin('branch_meta', function ($join) {
            $join->on('branch_meta.branch_id', '=', 'students.branch_id')
                ->where('branch_meta.name', 'logo_file');
        })
        ->select(
            'branches.branch_name',
            'branch_meta.value as logo_file',
            'parents.*',
            'user_details.*',
            'students.first_name',
            'students.last_name'
        )
        ->first();
    }

    public function getAdmin($userId)
    {
        return Staff::where('staff.user_id', $userId)
            ->leftJoin('user_details', 'user_details.user_id', '=', 'staff.user_id')
            ->leftJoin('branches', 'branches.id', '=', 'staff.branch_id')
            ->leftJoin('qualifications', 'qualifications.id', '=', 'staff.qualification')
            ->leftJoin('branch_meta', function ($join) {
                $join->on('branch_meta.branch_id', '=', 'branches.id')
                    ->where('branch_meta.name', 'logo_file');
            })
            ->select(
                'branches.branch_name',
                'branch_meta.value as logo_file',
                'staff.*',
                'user_details.*',
                'qualifications.name as qualification_name'
            )
            ->first();
    }
}
