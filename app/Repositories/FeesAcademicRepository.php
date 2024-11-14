<?php
namespace App\Repositories;

use App\Models\FeesAcademic;
use App\Interfaces\FeesAcademicInterface;
use Illuminate\Http\Request;

class FeesAcademicRepository implements FeesAcademicInterface
{

    public function getAll(Request $request)
    {
        $limit = $request->input('_limit',10);
        $allfeesacademic = FeesAcademic::where('status', 1);
        $total = $allfeesacademic->count();

        if ($request->has('q')) {
            $search = $request->input('q');
            $allfeesacademic->where('name', 'like', "%{$search}%");
        }

        if ($request->has('_sort') && $request->has('_order')) {
            $sortBy = $request->input('_sort');
            $sortOrder = $request->input('_order');
            $allfeesacademic->orderBy($sortBy, $sortOrder);
        } else {
            $allfeesacademic->orderBy('created_at', 'asc');
        }
        if ($limit <= 0) {
            $allfeesacademicData = $allfeesacademic->get();
        } else {
            $allfeesacademicData = $allfeesacademic->paginate($limit);
            $allfeesacademicData = $allfeesacademicData->items();
        }
        return ['data'=>$allfeesacademicData,'total'=>$total];
    }

    public function create($data)
    {
        FeesAcademic::create([
            "academic_id"=> $data['academic_id'],
            "fee_type"=> $data['fee_type'],
            "fees_amount"=> $data['fees_amount'],
        ]);
        return "Create Suucessfully.";
    }

    public function show($id)
    {
        $data = FeesAcademic::find($id);
        return $data;
    }

    public function update($id,$data)
    {
        $dataid = FeesAcademic::find($id);
        if($dataid)
        {
            $dataid->update([
                "academic_id"=> $data['academic_id'],
                "fee_type"=> $data['fee_type'],
                "fees_amount"=> $data['fees_amount'],
                'status' => $data['status'] ?? 1
            ]);
            return "Update Successfully.";
        }
        return "Data Not Found.";
    }

    public function delete($id)
    {
        $feesAcademic = FeesAcademic::find($id);
        if($feesAcademic)
        {
            $feesAcademic->update([
                'status' => 0
            ]);
            return "Deleted Successfully";
        }
        return "Data Not Found";
    }
}
