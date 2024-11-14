<?php

namespace App\Repositories;

use App\Models\Dist;
use Illuminate\Http\Request;
use App\Interfaces\DistRepositoryInterface;

class DistRepository implements DistRepositoryInterface
{
    /**
     * Create a new class instance.
     */
    protected $dist;
    public function __construct(Dist $dist)
    {
       $this->dist = $dist;
    }

    public function store($data){
        $district = $this->dist->createDistrict($data);
        return $district;
 }

    public function show($id){
        return  Dist::findOrFail($id)->toArray();

    }

    public function  getAll(Request $request, $limit){
        $total = Dist::where('status', 1)->withoutTrashed()->count();
        $query = Dist::query()->where('status', 1)->withoutTrashed();

        if ($request->has('q')) {
            $search = $request->input('q');
            $query->where('name', 'like', "%{$search}%");
        }

        if ($request->has('_sort') && $request->has('_order')) {
            $sortBy = $request->input('_sort');
            $sortOrder = $request->input('_order');
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->orderBy('created_at', 'asc');
        }

        if ($limit <= 0) {
            $states = $query->get();
        } else {
            $states = $query->paginate($limit);
            $states = $states->getCollection();
        }

        return ['data' => $states, 'total' => $total];
    }

    public function destroy($id){
        $district = Dist::findOrFail($id);
        $district->delete();
        $district->status = 0;
        $district->save();
        return $district;
    }

    public function update($id,$data){

        $district = $this->dist->updateState($id,$data);
        return $district;
    }

    public function getDistrict($id)
    {
        $district = $this->dist->where('state_id', $id)->get();
        return $district;
    }
}
