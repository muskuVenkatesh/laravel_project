<?php

namespace App\Repositories;

use App\Interfaces\StaffInterface;
use App\Models\Staff;
use App\Models\UserDetails;
use App\Models\User;

class StaffRepository implements StaffInterface
{
    public function __construct(User $user,UserDetails $userdetails, Staff $staff)
    {
        $this->user = $user;
        $this->userdetails = $userdetails;
        $this->staff = $staff;
    }

    public function StoreStaff($data)
    {
       $user_id = $this->user->createUsers($data);
       $this->staff->createStaff($data, $user_id);
       $this->userdetails->createUserDetails($data, $user_id);
    }

    public function GetAllStaff($branchId, $search = null, $sortBy = null, $sortOrder = null, $perPage = 15)
    {
        $query = Staff::where('branch_id', $branchId)->where('staff.status', 1)
        ->join('users', 'users.id', '=', 'staff.user_id')
        ->join('roles', 'roles.id', '=', 'users.roleid')
        ->select('staff.*', 'roles.name as staff_catagory', 'roles.id as role_id')
        ->withoutTrashed();

        $total = $query->count();
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('staff.first_name', 'like', "%$search%")
                  ->orWhere('staff.email', 'like', "%$search%");
            });
        }

        $query->orderBy($sortBy, $sortOrder);
        $staff = $query->paginate($perPage);
        $allStaff = $staff->items();
        return ['data'=>$allStaff, 'total'=>$total];
    }

    public function GetStaffById($id)
    {
       $staff = $this->staff->where('staff.id', $id)
       ->join('users', 'users.id', '=', 'staff.user_id')
       ->join('roles', 'roles.id', '=', 'users.roleid')
       ->select('staff.*', 'roles.name as staff_catagory', 'roles.id as role_id')->first();

       $userDetails = $this->userdetails->where('user_id', $staff->user_id)->first();

       $role = $this->user->where("id", $staff->user_id)->value('roleid');

       $roleArray = ['role_id' => $role];

       $staffArray = $staff->toArray();

       $userDetailsArray = $userDetails ? $userDetails->toArray() : [];

       //Remove the  User_details->id
       unset($userDetailsArray['id']);

       //Using Array Merge function For mearge the data in a single Array[]..
       $mergedData = array_merge($staffArray, $userDetailsArray,$roleArray);

       return $mergedData;
    }

    public function UpdateStaff($data, $id)
    {
        $user_id = $this->staff->find($id);
        $user_id  = $user_id->user_id;

        $this->user->updateUsers($data,$user_id);
        $this->staff->updateStaff($data, $id);
        $this->userdetails->updateUserDetails($user_id, $data);
    }
    public function DestroyStaff($id)
    {
        $staff = $this->staff->find($id);
        $staff->delete();
        $staff->status = 0;
        $staff->save();
        return "Staff Delete Successfully";
    }
}
