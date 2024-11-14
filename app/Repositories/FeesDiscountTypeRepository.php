<?php
namespace App\Repositories;

use App\Models\FeesDiscountType;
use App\Interfaces\FeesDiscountTypeInterface;
use Illuminate\Http\Request;

class FeesDiscountTypeRepository implements FeesDiscountTypeInterface
{
    public function getAll(Request $request)
    {
        $limit = $request->input('_limit',10);
        $allfeesdisounttype = FeesDiscountType::where('status', 1);
        $total = $allfeesdisounttype->count();

        if ($request->has('q')) {
            $search = $request->input('q');
            $allfeesdisounttype->where('name', 'like', "%{$search}%");
        }

        if ($request->has('_sort') && $request->has('_order')) {
            $sortBy = $request->input('_sort');
            $sortOrder = $request->input('_order');
            $allfeesdisounttype->orderBy($sortBy, $sortOrder);
        } else {
            $allfeesdisounttype->orderBy('created_at', 'asc');
        }
        if ($limit <= 0) {
            $allfeesdisounttypeData = $allfeesdisounttype->get();
        } else {
            $allfeesdisounttypeData = $allfeesdisounttype->paginate($limit);
            $allfeesdisounttypeData = $allfeesdisounttypeData->items();
        }
        return ['data'=>$allfeesdisounttypeData,'total'=>$total];
    }

    public function create($data)
    {
        FeesDiscountType::create([
            'name' => $data['name'],
            'amount' => $data['amount']
        ]);
        return "Create Suucessfully.";
    }

    public function show($id)
    {
        $data = FeesDiscountType::find($id);
        return $data;
    }
    public function update($id,$data)
    {
        $feesDiscountType = FeesDiscountType::find($id);
        if($feesDiscountType)
        {
            $feesDiscountType->update([
                'name' => $data['name'],
                'amount' => $data['amount'],
                'status' => $data['status']
            ]);
            return "Update Successfully";
        }
        return "Data Not Found";
    }

    public function delete($id)
    {
        $feesDiscountType = FeesDiscountType::find($id);
        $feesDiscountType->delete();
        $feesDiscountType->status = 0;
        $feesDiscountType->save();
        return $feesDiscountType;
    }
}
