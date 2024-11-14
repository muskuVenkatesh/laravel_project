<?php

namespace App\Repositories;

use App\Models\State;
use Illuminate\Http\Request;
use App\Interfaces\StateRepositoryInterface;

class StateRepository implements StateRepositoryInterface
{
    /**
     * Create a new class instance.
     */
    protected $state;
    public function __construct(State $state)
    {
           $this->state = $state;
    }
    public function store($data){
           $state = $this->state->createState($data);
           return $state;
    }

    public function show($id){
       return  State::findOrFail($id)->toArray();

    }

    public function  getAll(Request $request, $limit)
    {
        $total = State::where('status', 1)->withoutTrashed()->count();
        $query = State::query()->where('status', 1)->withoutTrashed();

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

        $states = $query->get();
        return ['data' => $states, 'total' => $total];
    }

    public function update($id,$data){

        $state = $this->state->updateState($id,$data);
        return $state;
    }

    public function destroy($id){
        $state = State::findOrFail($id);
        $state->delete();
        $state->status = 0;
        $state->save();
        return $state;
    }
}

