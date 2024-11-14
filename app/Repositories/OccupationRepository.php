<?php

namespace App\Repositories;

use App\Interfaces\OccupationInterface;
use App\Models\Occupation;
use Illuminate\Http\Request;

class OccupationRepository implements OccupationInterface
{
    protected $occupation;

    public function __construct(Occupation $occupation)
    {
        $this->occupation = $occupation;
    }

    public function deleteOccupations($id)
    {
        $data = Occupation::find($id);
        if ($data) {
            $data->status = 0;
            $data->save();
            $data->delete();
            return $data;
        }
    }
    public function updateOccupations($data, $id)
    {
        $occupation = Occupation::find($id);
        if ($occupation) {
            $occupation->update($data);
            return $occupation;
        }
    }

    public function storeOccupations($data)
    {
        $occupation = Occupation::create([
            'name' => $data['name'],
        ]);
        return $occupation;
    }

    public function getOccupations($search, $sortBy, $sortOrder, $perPage)
    {
        $query = Occupation::query()->where('status', 1)->withoutTrashed();
        $total = Occupation::where('status', 1)->withoutTrashed()->count();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('status', 'like', "%$search%");
            });
        }
        $query->orderBy($sortBy, $sortOrder);
        $allOccupationData = $query->paginate($perPage);
        $allOccupationData = $allOccupationData->items();
        return ['data' => $allOccupationData, 'total' => $total];
    }
}
