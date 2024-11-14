<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Exceptions\DataNotFoundException;
use App\Interfaces\StateRepositoryInterface;

class StateController extends Controller
{
    protected $staterepo;

    public function __construct(StateRepositoryInterface $staterepo)
    {
        $this->staterepo = $staterepo;
    }

    public function crateState(Request $request){
        $data = $request->validate(['name' => 'required|string']);
        $state = $this->staterepo->store($data);
        return response()->noContent();
    }

    public function getState($id){
        $state = $this->staterepo->show($id);
        return response()->json(['state'=>$state]);
    }

    public function getStates(Request $request){

        $perPage = $request->input('_limit', 10);
        $states = $this->staterepo->getAll($request, $perPage);
        if(empty($states['data']) || empty($states['total']))
        {
            throw new DataNotFoundException('No Staff Data Found.');
        }
        return response()->json([
            'states' => $states['data'],
            'total' => $states['total']
        ], 200);

    }

    public function updateState($id,Request $request){
        $data = $request->validate(['name' => 'required|string']);
        $state = $this->staterepo->update($id ,$data);
        return response()->noContent();
    }

    public function deleteState($id){

        $state = $this->staterepo->destroy($id);
        return response()->noContent();
    }
}
