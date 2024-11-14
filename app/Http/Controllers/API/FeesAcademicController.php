<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Exceptions\DataNotFoundException;
use App\Interfaces\FeesAcademicInterface;
use App\Http\Requests\StoreFeesAcademicRequest;
use App\Http\Requests\UpdateFeesAcademicRequest;

class FeesAcademicController extends Controller
{
    protected $feesAcademicRepository;

    public function __construct(FeesAcademicInterface $feesAcademicRepository)
    {
        $this->feesAcademicRepository = $feesAcademicRepository;
    }

    public function store(StoreFeesAcademicRequest $request)
    {
        $feesAcademic = $this->feesAcademicRepository->create($request->validated());
        return response()->json($feesAcademic);
    }

    public function getAll(Request $request)
    {
        $data = $this->feesAcademicRepository->getAll($request);
        if (empty($data['data']) || $data['total'] === 0) {
            throw new DataNotFoundException('No  Data Found For Absent Attendance Count');
        }
        return response()->json([
            'data' => $data['data'],
            'total' => $data['total']
        ], 200);
    }

    public function show($id)
    {
        $data = $this->feesAcademicRepository->show($id);
        return response()->json([
            'data' => $data
        ]);
    }

    public function update(UpdateFeesAcademicRequest $request, $id)
    {
        $data = $this->feesAcademicRepository->update($id, $request->validated());
        return response()->json($data);
    }

    public function destroy($id)
    {
        $data = $this->feesAcademicRepository->delete($id);
        if ($data) {
            return response()->json([
                'message' => 'Record successfully deleted.'
            ], 200);
        } else {
            throw new DataNotFoundException('Reacord Not Found');
        }
    }
}
