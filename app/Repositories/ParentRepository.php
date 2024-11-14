<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Parents;
use App\Models\Student;
use App\Models\UserDetails;
use App\Interfaces\ParentInterface;

class ParentRepository implements ParentInterface
{
    /**
     * Create a new class instance.
     */
    protected $parent;
    protected $user;
    public function __construct(Parents $parent,User $user,UserDetails $userdetails)
    {
        $this->parent = $parent;
        $this->user = $user;
        $this->userdetails = $userdetails;
    }

    public function store($data){
        $user_id = $this->user->createUsers($data);
        $this->parent->createParent($data, $user_id);
        $this->userdetails->createUserDetails($data, $user_id);

    }

    public function Getall($validatedData, $search = null, $sortBy = 'first_name', $sortOrder = 'asc', $perPage = 15)
    {
        $query = $this->parent
        ->where('status', 1)
        ->where('branch_id', $validatedData['branch_id'])
        ->whereHas('students', function ($query) use ($validatedData) {
            $query->where('class_id', $validatedData['class_id'])
                ->where('section_id', $validatedData['section_id']);
        })
        ->withoutTrashed();
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%$search%");

            });
        }
        $query->orderBy($sortBy, $sortOrder);
        $parent = $query->paginate($perPage);
        return [
            'items' => $parent->items(),
            'total' => $parent->total(),
        ];
    }


    public function getStudentByParent($parent_id)
    {
        return Student::where('parent_id', $parent_id)->get();
    }

    public function show($id)
    {
        $parent = $this->parent->find($id);
        $userDetails = $this->userdetails->where('user_id', $parent->user_id)->first();
        $userEmail = $this->user->where('id', $parent->user_id)->value('email');
        $parentArray = $parent->toArray();
        $userDetailsArray = $userDetails ? $userDetails->toArray() : [];
        unset($userDetailsArray['id']);
        $userEmailArray = ['email' => $userEmail];
        $mergedData = array_merge($parentArray, $userDetailsArray, $userEmailArray);
        return $mergedData;
    }

    public function updateParent($id,$data){
        $user_id = $this->parent->find($id);

        $this->parent->updateParent($id, $data);

        $this->userdetails->updateUserDetails($user_id->user_id, $data);
    }

    public function destroy($id){
        $parent = Parents::findOrFail($id);
        $parent->delete();
        $parent->status = 0;
        $parent->save();
        return $parent;
    }
}
