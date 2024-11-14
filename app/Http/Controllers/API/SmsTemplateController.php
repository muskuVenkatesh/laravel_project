<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Interfaces\SmsTemplateInterface;
use App\Exceptions\DataNotFoundException;
use App\Http\Requests\SmsTemplateEditRequest;
use App\Http\Requests\SmsTemplateCreateRequest;

class SmsTemplateController extends Controller
{
    protected $smstemplateinterface;

    public function __construct(SmsTemplateInterface $smstemplateinterface)
    {
        $this->smstemplateinterface = $smstemplateinterface;
    }

    public function Store(SmsTemplateCreateRequest $request)
    {
        $validatedData = $request->validated();
        $data = $this->smstemplateinterface->Store($validatedData);

        return response()->json([
            'data' => $data
        ], 201);
    }

    public function getSmsTemplates(Request $request)
    {
        $perPage = $request->input('_limit', 10);
        $data=$this->smstemplateinterface->getSmsTemplates($request, $perPage);
        if(empty($data['data']) || empty($data['total']))
        {
           throw new DataNotFoundException('No Template Data Found.');
        }
        return response()->json([
            'data' => $data['data'],
            'total' => $data['total']
        ], 201);
    }

    public function getSmsTemplate($id)
    {
        $data=$this->smstemplateinterface->getSmsTemplate($id);
        return response()->json([
            'data' => $data
        ], 201);
    }

    public function update(SmsTemplateEditRequest $request, $id)
    {
        $validatedData = $request->validated();
        $SmsTemplate = $this->smstemplateinterface->updatetemplate($id, $validatedData);
        return response()->json(['SmsTemplate'=>$SmsTemplate], 200);
    }

    public function delete($id)
    {
        $deleted = $this->smstemplateinterface->deletetemplate($id);
        if ($deleted) {
            return response()->json(['message' => 'SmsTemplate deleted successfully'], 200);
        } else {
            return response()->json(['message' => 'SmsTemplate not found or failed to delete'], 404);
        }
    }
}
