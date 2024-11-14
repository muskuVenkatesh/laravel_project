<?php

namespace App\Repositories;

use App\Interfaces\DepartmentInterface;
use App\Models\Departments;
use Illuminate\Http\Request;

class DepartmentRepository implements DepartmentInterface
{
    public function __construct(Departments $department)
    {
           $this->department = $department;
    }

    public function deleteDepartment($data){
        $department = $this->department->find($data);
        return $department;
    }

    public function updateDepartment($data, $id)
    {
        $data = $this->department->updateDepartment($data, $id);
        return $data;
    }

    public function createDepartment($data){
        $department = $this->department->createDepartment($data);
        return $department;
    }

    public function getDepartment(Request $request, $limit)
    {
        $total = Departments::where('status', 1)
        ->withoutTrashed()
        ->count();
        $alldepartment = Departments::where('status', 1)
        ->withoutTrashed();
        if ($request->has('q')) {
            $search = $request->input('q');
            $alldepartment->where('name', 'like', "%{$search}%");
        }

        if ($request->has('_sort') && $request->has('_order')) {
            $sortBy = $request->input('_sort');
            $sortOrder = $request->input('_order');
            $alldepartment->orderBy($sortBy, $sortOrder);
        } else {
            $alldepartment->orderBy('created_at', 'asc');
        }

        if ($limit <= 0) {
            $alldepartmentData = $alldepartment->get();
        } else {
            $alldepartmentData = $alldepartment->paginate($limit);
            $alldepartmentData = $alldepartmentData->items();
        }
        return ['data'=>$alldepartmentData,'total'=>$total];
    }
}
