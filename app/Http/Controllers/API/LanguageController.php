<?php

namespace App\Http\Controllers\API;

use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Interfaces\LanguageInterface;
use App\Http\Requests\LanguageRequest;
use App\Exceptions\DataNotFoundException;
use App\Http\Requests\LanguageEditRequest;

class LanguageController extends Controller
{
    protected $languageInterface;

    public function __construct(LanguageInterface $languageInterface)
    {
        $this->languageInterface = $languageInterface;
    }

    public function GetLanguages(Request $request)
    {
        $perPage = $request->input('_limit', 10);
        $allsubject = $this->languageInterface->GetLanguages($request, $perPage);
        if(empty($allsubject['data']) || empty($allsubject['total']))
        {
            throw new DataNotFoundException('Language Reacords Not Found');
        }
        else  {
            return response()->json([
                'data' => $allsubject['data'],
                'total' => $allsubject['total']
            ], 200);
        }
    }

    public function createLanguage(LanguageRequest $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }
        try {

            $validatedData = $request->validated();
            $data = $this->languageInterface->createLanguage($validatedData);
            $token = JWTAuth::fromUser($user);
            return response()->json([
                'data' => $data,
                // 'token' => $token,
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred',
                'error' => $e->getMessage(),
            ], 500);
       }
    }

    public function getLanguageByID($id)
    {
        $data = Language::find($id);
        if (!$data) {
            return response()->json([
                'message' => 'No Data Found'
            ],404);
        }
        else
        { return response()->json([
                'data' => $data,
            ], 201);
        }
    }

    public function updateLanguage(LanguageEditRequest $request , $id)
    {
        //  if (!get_permission('update', Schools::class)) {
        //     return response()->json(['status' => 'access_denied'], 403);
        // }
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }
        try {
            $validatedData = $request->validated();
            $token = JWTAuth::fromUser($user);
            $language = Language::find($id);
            if (!$language) {
                return response()->json([
                    'message' => 'Language not found',
                ], 404);
            }
            $language = $this->languageInterface->updateLanguage($validatedData, $id);

            return response()->noContent();
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        }

    }

    public function deleteLanguage($id)
    {
        // if (!get_permission('delete', Schools::class)) {
        //     return response()->json(['status' => 'access_denied'], 403);
        // }
        if($id)
        {
            $language = Language::find($id);
            if (!$language) {
                return response()->json([
                    'message' => 'Language not found',
                ], 404);
            }
            $language->status = 0;
            $language->save();
            $language->delete();
            return response()->noContent();
        }
    }
}
