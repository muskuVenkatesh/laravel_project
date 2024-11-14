<?php

namespace App\Repositories;

use App\Models\IdCardTemplate;
use App\Interfaces\IdInterface;
use Illuminate\Http\Request;

class IdRepository implements IdInterface
{
    protected $idTemplate;

    public function __construct(IdCardTemplate $idTemplate)
    {
        $this->idTemplate = $idTemplate;
    }

    public function CreateId($data)
    {
        if (isset($data['file_path']) && $data['file_path']->isValid()) {

            $originalFilename = pathinfo($data['file_path']->getClientOriginalName(), PATHINFO_FILENAME);
            $timestamp = date('Ymd_His');
            $extension = $data['file_path']->getClientOriginalExtension();
            $fileName = $originalFilename . '_' . $timestamp . '.' . $extension;
            $imagePath = $data['file_path']->storeAs('images/idcard_templates', $fileName, 'public');
        }
        $html_file_path = ($data['id_type'] == 'report') ? 'pdf_templates\report_card_template.html' : (($data['id_type'] == 'recipet') ? 'pdf_templates\recipet_template.html' : 'pdf_templates\recipet_template_two.html');

        return $this->idTemplate->create([
            'id_type' => $data['id_type'],
            'name' => $data['name'],
            'file_path' => $imagePath,
            'html_file_path' => $html_file_path
        ]);
    }

    public function GetAllId(Request $request, int $limit, $id_type)
    {
        $allid = IdCardTemplate::select('id_card_templates.*')
        ->where('id_card_templates.status', 1);
        if($id_type != 'get-all')
        {
            $allid->whereRaw('LOWER(id_card_templates.id_type) = ?', [strtolower($id_type)]);
        }
        $total = $allid->count();
        if ($request->has('q')) {
            $search = $request->input('q');
            $allid->where('id_card_templates.name', 'like', "%{$search}%");
        }

        if ($request->has('_sort') && $request->has('_order')) {
            $sortBy = $request->input('_sort');
            $sortOrder = $request->input('_order');
            $allid->orderBy($sortBy, $sortOrder);
        } else {
            $allid->orderBy('id_card_templates.created_at', 'asc');
        }

        if ($limit <= 0) {
            $allidData = $allid->get();
        } else {
            $allidData = $allid->paginate($limit);
            $allidData = $allidData->items();
        }
        return ['data' => $allidData, 'total' => $total];
    }

    public function getIdCardById($id)
    {
        $idCard = IdCardTemplate::select('id_card_templates.*')
            ->where('id_card_templates.id', $id)
            ->first();
        return $idCard;
    }

    public function updateIdCard($id,$data)
    {
        $idCard = IdCardTemplate::find($id);
        if (!$idCard)
        {
            return null;
        }
        if (isset($data['file_path']) && $data['file_path'] instanceof \Illuminate\Http\UploadedFile) {
            $originalFilename = pathinfo($data['file_path']->getClientOriginalName(), PATHINFO_FILENAME);
            $timestamp = date('Ymd_His');
            $extension = $data['file_path']->getClientOriginalExtension();
            $fileName = $originalFilename . '_' . $timestamp . '.' . $extension;
            $imagePath = $data['file_path']->storeAs('images/idcard_templates', $fileName, 'public');;
        }
        $idCard->update([
           'id_type' => $data['id_type'],
           'name' => $data['name'],
           'file_path' => $imagePath ?? $idCard->file_path,
        ]);
        return $idCard;
    }

    public function softDeleteIdCard($id)
    {
        $idCard = $this->getIdCardById($id);
        if ($idCard) {
            $idCard->status = 0;
            $idCard->save();
            return $idCard;
        }
        return null;
    }
}

