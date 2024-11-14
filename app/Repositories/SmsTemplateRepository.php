<?php

namespace App\Repositories;

use App\Models\SmsTemplate;
use App\Interfaces\SmsTemplateInterface;
use Illuminate\Http\Request;

class SmsTemplateRepository implements SmsTemplateInterface
{
    protected $smstemplatemodel;

    public function __construct(SmsTemplate $smstemplatemodel)
    {
        $this->smstemplatemodel = $smstemplatemodel;
    }

    public function store($data)
    {
        $this->smstemplatemodel->create($data);
        return "Create Successfully.";
    }

    public function getSmsTemplates(Request $request, $limit)
    {
        $query = $this->smstemplatemodel->where('status', 1);
        $total = $query->count();
        $search = $request->input('search');
        $sortBy = $request->input('sortBy', 'id');
        $sortOrder = $request->input('sortOrder', 'asc');
        $perPage = $request->input('perPage', $limit);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('sms_type', 'like', "%$search%")
                  ->orWhere('status', 'like', "%$search%");
            });
        }
        $query->orderBy($sortBy, $sortOrder);

        if ($perPage <= 0) {
            $smsData = $query->get();
        } else {
            $smsData = $query->paginate($perPage)->items();
        }

        return ['data' => $smsData, 'total' => $total];
    }

    public function getSmsTemplate($id){
        return $this->smstemplatemodel->findOrFail($id);
    }

    public function updatetemplate($id, $validatedData)
    {
        $smstemplate = $this->smstemplatemodel->updatetemplate($id, $validatedData);
        return $smstemplate;
    }

    public function deletetemplate($id)
    {
        $smsTemplate = SmsTemplate::find($id);
        if ($smsTemplate) {
            $smsTemplate->status = 0;
            $smsTemplate->save();
            $smsTemplate->delete();
        }
        return "Delete Sucessfully.";
    }
}
