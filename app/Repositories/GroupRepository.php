<?php

namespace App\Repositories;

use App\Interfaces\GroupInterface;
use App\Models\Group;
use Illuminate\Http\Request;

class GroupRepository implements GroupInterface
{
    public function __construct(Group $group)
    {
           $this->group = $group;
    }

    public function deleteGroup($id)
    {
        $data = Group::find($id);
        $data->delete();
        $data->status = 0;
        $data->save();
        return $data;
    }

    public function UpdateGroup($data, $id)
    {
        $data = $this->group->UpdateGroup($data, $id);
        return $data;
    }

    public function StoreGroups($data){
        $group = $this->group->StoreGroups($data);
        return $group;
    }

    public function getGroups(Request $request, $limit, $branchId)
    {

        $total = Group::where('status',1)->where('branch_id', $branchId)->withoutTrashed()->count();
        $allgroup = Group::where('status',1)->where('branch_id', $branchId)->withoutTrashed();
        $allgroup->where('status', 1);
        if ($request->has('q')) {
            $search = $request->input('q');
            $allgroup->where('name', 'like', "%{$search}%");
        }
        if ($request->has('_sort') && $request->has('_order')) {
            $sortBy = $request->input('_sort');
            $sortOrder = $request->input('_order');
            $allgroup->orderBy($sortBy, $sortOrder);
        } else {
            $allgroup->orderBy('created_at', 'asc');
        }
        if ($limit <= 0) {
            $allgroupData = $allgroup->get();
        } else {
            $allgroupData = $allgroup->paginate($limit);
            $allgroupData = $allgroupData->items();
        }
        return ['data'=>$allgroupData,'total'=>$total];
    }
}
