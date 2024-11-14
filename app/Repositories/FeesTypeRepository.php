<?php

namespace App\Repositories;
use App\Models\FeesType;
use App\Interfaces\FeesTypeInterface;
use Illuminate\Http\Request;

class FeesTypeRepository implements FeesTypeInterface
{
    public function getById($id)
    {

        return FeesType::find($id);
    }

    public function create($data)
    {
        FeesType::create($data);
        return "Create Successfully.";
    }

    public function getAll(Request $request)
    {
        $limit = $request->input('_limit');
        $allfeetype = FeesType::where('status', 1);
        $total = $allfeetype->count();

        if ($request->has('q')) {
            $search = $request->input('q');
            $allfeetype->where('name', 'like', "%{$search}%");
        }

        if ($request->has('_sort') && $request->has('_order')) {
            $sortBy = $request->input('_sort');
            $sortOrder = $request->input('_order');
            $allfeetype->orderBy($sortBy, $sortOrder);
        } else {
            $allfeetype->orderBy('created_at', 'asc');
        }

        if ($limit <= 0) {
            $allfeetypeData = $allfeetype->get();
        } else {
            $allfeetypeData = $allfeetype->paginate($limit);
            $allfeetypeData = $allfeetypeData->items();
        }
        return ['data'=>$allfeetypeData,'total'=>$total];
    }

    public function update($id, $data)
    {
        $feesType = FeesType::find($id);
        if ($feesType) {
            $feesType->update($data);
            return "Update Successfully.";
        }
        return "Data Not Found";
    }

    public function delete($id)
    {
        $feesType = FeesType::find($id);
        $feesType->status = 0;
        $feesType->save();
        $feesType->delete();
        return $feesType;
    }
}
