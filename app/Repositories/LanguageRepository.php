<?php

namespace App\Repositories;

use App\Interfaces\LanguageInterface;
use App\Models\Language;
use Illuminate\Http\Request;

class LanguageRepository implements LanguageInterface
{
    public function __construct(Language $language)
    {
           $this->language = $language;
    }

    public function updateLanguage($data, $id)
    {
        $data = $this->language->updateLanguage($data, $id);
        return $data;
    }

    public function createLanguage($data){
        $language = $this->language->createLanguage($data);
        return $language;
    }

    public function GetLanguages(Request $request, $limit)
    {
        $total = Language::count();

        $alllanguage = Language::query();
        $alllanguage->where('status', 1);
        if ($request->has('q')) {
            $search = $request->input('q');
            $alllanguage->where('name', 'like', "%{$search}%");
        }

        if ($request->has('_sort') && $request->has('_order')) {
            $sortBy = $request->input('_sort');
            $sortOrder = $request->input('_order');
            $alllanguage->orderBy($sortBy, $sortOrder);
        } else {
            $alllanguage->orderBy('created_at', 'asc');
        }

        if ($limit <= 0) {
            $alllanguageData = $alllanguage->get();
        } else {
            $alllanguageData = $alllanguage->paginate($limit);
            $alllanguageData = $alllanguageData->items();
        }
        return ['data'=>$alllanguageData,'total'=>$total];

    }
}
