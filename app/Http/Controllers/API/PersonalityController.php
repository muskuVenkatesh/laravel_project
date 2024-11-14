<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\PersonalityRepository;
use App\Http\Requests\PersonalityRequest;
use App\Http\Requests\UpdatePersonalityTraitRequest;
use App\Interfaces\PersonalityInterface;
use App\Exceptions\DataNotFoundException;

class PersonalityController extends Controller
{
    protected $personalityinterface;

    public function __construct(PersonalityInterface $personalityinterface)
    {
        $this->personalityinterface = $personalityinterface;
    } 

    public function  CreatePersonality(PersonalityRequest $request)
    {
        $personality = $this->personalityinterface->CreatePersonality($request->validated());
        return response()->json([
            'message' => 'created successfully',
        ], 200);
    }

    public function GetAllPersonality(Request $request)
    {
        $personality = $this->personalityinterface->GetAllPersonality($request);
        if(empty($personality['data']) || empty($personality['total']))
        {
            throw new DataNotFoundException('Personality Traits not found.');
        }
        return response()->json([
            'data'  => $personality['data'],
            'total' => $personality['total']],
        200);
    }

    public function GetPersonalityById($id)
    {
        $personality = $this->personalityinterface->GetPersonalityById($id);
        if (!$personality) {
            return response()->json(['message' => 'Personality Traits not found'], 404);
        }
        return response()->json($personality, 200);
    }

    public function UpdatePersonality(UpdatePersonalityTraitRequest $request, $id)
    {
        $updatedPersonality = $this->personalityinterface->UpdatePersonality($id, $request->validated());
        if (!$updatedPersonality) {
            return response()->json(['message' => 'Personality Traits not found'], 404);
        }
        return response()->json([
            'data' => $updatedPersonality,
        ], 200);
    }

    public function DeletePersonalityTraits($id)
    {
        $Delete = $this->personalityinterface->DeletePersonalityTraits($id);
        if (!$Delete) {
            return response()->json(['message' => 'Personality Traits not found'], 404);
        }
        return response()->json(['message' => 'deleted successfully'], 200);
    }

}
