<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Interfaces\DistRepositoryInterface;

class DistrictController extends Controller
{
    protected $distrepo;

    public function __construct(DistRepositoryInterface $distrepo)
    {
        $this->distrepo = $distrepo;
    }

    public function crateDistrict(Request $request){

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'state_id' => 'required|exists:states,id',
        ]);

        $state = $this->distrepo->store($data);
        return response()->noContent();
    }
    public function getDistricts(Request $request){

        $perPage = $request->input('_limit', 10);
        $states = $this->distrepo->getAll($request, $perPage);
        if(!empty($states['data']))
        {
            return response()->json([
                'districts' => $states['data'],
                'total' => $states['total']
            ], 200);
        }else{
            throw new DataNotFoundException('No Data Found For Districts');
        }
    }

    public function updateDistrict($id,Request $request){
        $data = $request->validate(['name' => 'required|string|max:255',
            'state_id' => 'required|exists:states,id',]);
        $state = $this->distrepo->update($id ,$data);
        return response()->noContent();
    }

    public function deleteDistrict($id){

        $state = $this->distrepo->destroy($id);
        return response()->noContent();
    }

    public function getDistrict($id)
    {
        $districts = $this->distrepo->getDistrict($id);
        if(!empty($districts)){
            return response()->json([
                'data' => $districts
            ], 200);
        }
        else{
            return response()->json([
                'data' => "No Data Found."
            ], 404);
        }

    }
}
