<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Exceptions\DataNotFoundException;
use App\Interfaces\FeesTimelineInterface;
use App\Http\Requests\StoreFeesTimelineRequest;
use App\Http\Requests\UpdateFeesTimelineRequest;

class FeesTimelineController extends Controller
{
    protected $feesTimelineRepository;

    public function __construct(FeesTimelineInterface $feesTimelineRepository)
    {
        $this->feesTimelineRepository = $feesTimelineRepository;
    }

    public function show($id)
    {
        $data = $this->feesTimelineRepository->getById($id);
        return response()->json([
            'data' => $data
        ]);
    }

    public function store(StoreFeesTimelineRequest $request)
    {
        $data = $request->validated();
        $datas = $this->feesTimelineRepository->create($data);
        return response()->json($datas);
    }

    public function getAll(Request $request)
    {
        $feesType = $this->feesTimelineRepository->getAll($request);
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

    public function update(UpdateFeesTimelineRequest $request, $id)
    {
        $data = $request->validated();
        $updated = $this->feesTimelineRepository->update($id, $data);
        return response()->json(['message' => $updated], 200);
    }

    public function destroy($id)
    {
        $deleted = $this->feesTimelineRepository->delete($id);
        return response()->json(['message' => $deleted], 200);
    }
}
