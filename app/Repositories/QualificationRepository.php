<?php

namespace App\Repositories;

use App\Interfaces\QualificationInterface;
use App\Models\Qualifications;
use Illuminate\Http\Request;

class QualificationRepository implements QualificationInterface
{
    public function __construct(Qualifications $qualification)
    {
           $this->qualification = $qualification;
    }

    public function deleteQualifications($id)
    {
        $data = Qualifications::find($id);
        $data->delete();
        $data->status = 0;
        $data->save();
        return $data;
    }

    public function UpdateQualifications($data, $id)
    {
        $data = $this->qualification->UpdateQualifications($data, $id);
        return $data;
    }

    public function StoreQualifications($data){
        $qualification = $this->qualification->StoreQualifications($data);
        return $qualification;
    }

    public function getQualifications($search, $sortBy, $sortOrder, $perPage)
    {
        $query = Qualifications::query()->where('status',1)->withoutTrashed();
        $total = Qualifications::where('status',1)->withoutTrashed()->count();
        $query->where('status', 1);
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('status', 'like', "%$search%");
            });
        }
        $query->orderBy($sortBy, $sortOrder);
        $allqualificationData = $query->paginate($perPage);
        $allqualificationData = $allqualificationData->items();
        return ['data'=>$allqualificationData,'total'=>$total];
    }
}
