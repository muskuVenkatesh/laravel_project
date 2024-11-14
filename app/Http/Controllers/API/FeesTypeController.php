<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Interfaces\FeesTypeInterface;
use App\Exceptions\DataNotFoundException;
use App\Http\Requests\StoreFeesTypeRequest;
use App\Http\Requests\UpdateFeesTypeRequest;

class FeesTypeController extends Controller
{
    protected $feesTypeRepository;

    public function __construct(FeesTypeInterface $feesTypeRepository)
    {
        $this->feesTypeRepository = $feesTypeRepository;
    }

    public function show($id)
    {
        $feesType = $this->feesTypeRepository->getById($id);
        if (!$feesType) {
            return response()->json(['error' => 'FeesType not found'], 404);
        }
        return response()->json(['data' => $feesType], 200);
    }

    public function store(StoreFeesTypeRequest $request)
    {
        $data = $request->validated();
        $feesType = $this->feesTypeRepository->create($data);
        return response()->json($feesType, 201);
    }

    public function getAll(Request $request)
    {
        $feesType = $this->feesTypeRepository->getAll($request);
        if(empty($feesType['data']) || empty($feesType['total']))
        {
            throw new DataNotFoundException('Reacords Not Found');
        }else{
            return response()->json([
                'data' => $feesType['data'],
                'total' => $feesType['total']
            ],200);
        }
    }

    public function update(UpdateFeesTypeRequest $request, $id)
    {
        $data = $data = $request->validated();
        $feesType = $this->feesTypeRepository->update($id, $data);
        return response()->json($feesType);
    }

    public function destroy($id)
    {
        $deleted = $this->feesTypeRepository->delete($id);
        return response()->json(['message' => $deleted]);
    }
}
