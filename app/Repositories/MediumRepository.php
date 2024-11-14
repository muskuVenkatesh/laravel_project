<?php

namespace App\Repositories;

use App\Interfaces\MediumInterface;
use App\Models\Medium;
use Illuminate\Http\Request;

class MediumRepository implements MediumInterface
{
    public function __construct(Medium $medium)
    {
           $this->medium = $medium;
    }

    public function deleteMedium($data){
        $medium = $this->medium->find($data);
        return $medium;
    }

    public function updateMedium($data, $id)
    {
        $data = $this->medium->updateMedium($data, $id);
        return $data;
    }

    public function createMedium($data){
        $medium = $this->medium->createMedium($data);
        return $medium;
    }

    public function getMedium(Request $request, $limit)
    {
        $total = Medium::where('status',1)->withoutTrashed()->count();
        $allmedium = Medium::where('status',1)->withoutTrashed();
        $allmedium->where('status', 1);
        if ($request->has('q')) {
            $search = $request->input('q');
            $allmedium->where('name', 'like', "%{$search}%");
        }

        if ($request->has('_sort') && $request->has('_order')) {
            $sortBy = $request->input('_sort');
            $sortOrder = $request->input('_order');
            $allmedium->orderBy($sortBy, $sortOrder);
        } else {
            $allmedium->orderBy('created_at', 'asc');
        }

        if ($limit <= 0) {
            $allmediumData = $allmedium->get();
        } else {
            $allmediumData = $allmedium->paginate($limit);
            $allmediumData = $allmediumData->items();
        }
        return ['data'=>$allmediumData,'total'=>$total];

    }
}
