<?php

namespace App\Repositories;

use App\Interfaces\ClassesInterface;
use App\Models\Classes;
use Illuminate\Http\Request;
use App\Models\Branches;

class ClassesRepository implements ClassesInterface
{
    public function __construct(Classes $classes)
    {
           $this->classes = $classes;
    }

    public function deleteClass($data){
        $classes = $this->classes->find($data);
        return $classes;
    }

    public function updateClass($data, $id)
    {
        $data = $this->classes->updateClass($data, $id);
        return $data;
    }

    public function createClass($data){
        $classes = $this->classes->createClass($data);
        return $classes;
    }

    public function getClass(Request $request, $limit)
    {
        $branchId = $request->input('branch_id');
        $allclass = Classes::where('status', 1)->where('branch_id', $branchId)->withoutTrashed();
        $total = $allclass->count();

        if ($request->has('q')) {
            $search = $request->input('q');
            $allclass->where('name', 'like', "%{$search}%");
        }

        if ($request->has('_sort') && $request->has('_order')) {
            $sortBy = $request->input('_sort');
            $sortOrder = $request->input('_order');
            $allclass->orderBy($sortBy, $sortOrder);
        } else {
            $allclass->orderBy('created_at', 'asc');
        }

        if ($limit <= 0) {
            $allclassData = $allclass->get();
        } else {
            $allclassData = $allclass->paginate($limit);
            $allclassData = $allclassData->items();
        }
        return ['data'=>$allclassData,'total'=>$total];

    }

    public function getClassesByBranch($branchId){
        $branch = Branches::with('classes')->find($branchId);
        return $branch;
    }
}
