<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Interfaces\FeesPayTypeInterface;
use App\Exceptions\DataNotFoundException;

class FeesPayTypeController extends Controller
{
    protected $feesPayTypeRepository;

    public function __construct(FeesPayTypeInterface $feesPayTypeRepository)
    {
        $this->feesPayTypeRepository = $feesPayTypeRepository;
    }

    public function store(Request $request)
    {
       $validData = $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $data = $this->feesPayTypeRepository->create($validData);
        return response()->json([
            'data' => $data
        ]);
    }

    public function show($id)
    {
        $data = $this->feesPayTypeRepository->show($id);
        return response()->json([
            'data' => $data
        ]);
    }

    public function getAll(Request $request)
    {
        $data = $this->feesPayTypeRepository->getAll($request);
        if(empty($data['data']) || empty($data['total'])){
            throw new DataNotFoundException('Reacord Not Found');
        }
        return response()->json([
            'data' => $data['data'],
            'total' => $data['total']
        ]);
    }
    public function update(Request $request, $id)
    {
        $validData = $request->validate([
            'name' => 'sometimes|string|max:255',
            'status' => 'nullable|present',
        ]);

        $data = $this->feesPayTypeRepository->update($id, $validData);
        return response()->json([
            'data' => $data
        ]);
    }

    public function destroy($id)
    {
       $data = $this->feesPayTypeRepository->delete($id);
        return response()->json([
            'data' => $data
        ]);
    }
}
