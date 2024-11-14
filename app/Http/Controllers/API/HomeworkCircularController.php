<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCircularRequest;
use App\Interfaces\homeWorkCircularInterface;

class HomeworkCircularController extends Controller
{
    protected $circularRepository;

    public function __construct(homeWorkCircularInterface $homeworkcircularInterface)
    {
        $this->homeworkcircularInterface = $homeworkcircularInterface;
    }

    public function CreateCircular(CreateCircularRequest $request)
    {
        $data = $request->validated();
        $circular = $this->homeworkcircularInterface->createCircular($data);
        return response()->json([
            'success' => true,
            'message' => 'Data inserted successfully',
            'data' => $circular
        ], 201);
    }
}
