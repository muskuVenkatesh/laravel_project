<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Exceptions\DataNotFoundException;
use App\Interfaces\FeesDiscountTypeInterface;
use App\Http\Requests\StoreFeesDiscountTypeRequest;
use App\Http\Requests\UpdateFeesDiscountTypeRequest;

class FeesDiscountTypeController extends Controller
{
    protected $feesDiscountTypeRepository;

    public function __construct(FeesDiscountTypeInterface $feesDiscountTypeRepository)
    {
        $this->feesDiscountTypeRepository = $feesDiscountTypeRepository;
    }

    public function store(StoreFeesDiscountTypeRequest $request)
    {
        $feesDiscountType = $this->feesDiscountTypeRepository->create($request->validated());
        return response()->json($feesDiscountType);
    }

    public function getAll(Request $request)
    {
        $data = $this->feesDiscountTypeRepository->getAll($request);
        if(empty($data['data']) || empty($data['total']))
        {
            throw new DataNotFoundException('Reacords Not Found');
        }
        return response()->json([
            'data' => $data['data'],
            'total' => $data['total']
        ],200);
    }
    public function show($id)
    {
        $data = $this->feesDiscountTypeRepository->show($id);
        if (!$data) {
            throw new DataNotFoundException('Reacord Not Found');
        }
        return response()->json([
            'data' => $data
        ]);
    }

    public function update(UpdateFeesDiscountTypeRequest $request, $id)
    {
        $data = $this->feesDiscountTypeRepository->update($id, $request->validated());

        return response()->json($data);
    }

    public function destroy($id)
    {
        $data = $this->feesDiscountTypeRepository->delete($id);
        return response()->json($data);
    }
}
