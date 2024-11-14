<?php

namespace App\Repositories;

use App\Models\FeesPayType;
use App\Interfaces\FeesPayTypeInterface;
use Illuminate\Http\Request;

class FeesPayTypeRepository implements FeesPayTypeInterface
{
    public function getAll(Request $request)
    {
        $limit = $request->input('_limit');
        $allfeestype = FeesPayType::where('status', 1);
        $total = $allfeestype->count();

        if ($request->has('q')) {
            $search = $request->input('q');
            $allfeestype->where('name', 'like', "%{$search}%");
        }

        if ($request->has('_sort') && $request->has('_order')) {
            $sortBy = $request->input('_sort');
            $sortOrder = $request->input('_order');
            $allfeestype->orderBy($sortBy, $sortOrder);
        } else {
            $allfeestype->orderBy('created_at', 'asc');
        }
        if ($limit <= 0) {
            $allfeestypeData = $allfeestype->get();
        } else {
            $allfeestypeData = $allfeestype->paginate($limit);
            $allfeestypeData = $allfeestypeData->items();
        }
        return ['data'=>$allfeestypeData,'total'=>$total];
    }

    public function show($id)
    {
        return FeesPayType::findOrFail($id);
    }

    public function create($data)
    {
        $data = FeesPayType::create([
            'name' => $data['name']
        ]);
        return "created Successfully.";
    }

    public function update($id, $data)
    {
        $feesPayType = FeesPayType::find($id);
        if($feesPayType)
        {
            $feesPayType->update([
                'name' => $data['name'],
                'status' => $data['status'] ?? $feesPayType->status
            ]);
            return 'update Successfully.';
        }
        return "No Data Found.";
    }

    public function delete($id)
    {
        $feesPayType = FeesPayType::find($id);
        $feesPayType->status= 0;
        $feesPayType->save();
        $feesPayType->delete();
        return $feesPayType;
    }
}
