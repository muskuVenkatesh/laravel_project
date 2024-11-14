<?php

namespace App\Repositories;

use App\Models\FeesTimeline;
use App\Interfaces\FeesTimelineInterface;
use Illuminate\Http\Request;

class FeesTimelineRepository implements FeesTimelineInterface
{
    public function getById($id)
    {
        return FeesTimeline::find($id);
    }

    public function create($data)
    {
        FeesTimeline::create($data);
        return "Created Successfully.";
    }

    public function getAll(Request $request)
    {
        $limit = $request->input('_limit',10);
        $allfeetype = FeesTimeline::where('status', 1);
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
        return ['data' => $allfeetypeData, 'total' => $total];
    }

    public function update($id, $data)
    {
        $feesTimeline = FeesTimeline::find($id);
        if ($feesTimeline) {
            $feesTimeline->update($data);
            return "Update Successfully.";
        }
        return "Data Not Found.";
    }

    public function delete($id)
    {
        $feesTimeline = FeesTimeline::find($id);
        $feesTimeline->status = 0;
        $feesTimeline->save();
        $feesTimeline->delete();
        return $feesTimeline;
    }
}
