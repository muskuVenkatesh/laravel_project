<?php

namespace App\Http\Controllers\API;

use App\Models\Section;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\SectionRepository;
use App\Exceptions\DataNotFoundException;
use App\Http\Requests\StoreSectionRequest;
use App\Http\Requests\UpdateSectionRequest;

class SectionController extends Controller
{

    public function __construct(SectionRepository $sectionrepository)
    {
        $this->sectionrepository = $sectionrepository;
    }

    public function StoreSection(StoreSectionRequest $request)
    {
        $section = $this->sectionrepository->store($request->validated());
        return response()->json($section, 201);
    }

    public function GetAllSections(Request $request)
    {
        $perPage = $request->input('_limit', 10);
        $allsection = $this->sectionrepository->GetAll($request, $perPage);
        if(empty($allsection['data']) || empty($allsection['total']))
        {
            throw new DataNotFoundException('No Sections Data Found.');
        }
        else  {
            return response()->json([
                'data' => $allsection['data'],
                'total' => $allsection['total']
            ], 200);
        }
    }

    public function GetSectionById($id)
    {
        $sections = $this->sectionrepository->GetSection($id);
        return response()->json(['section'=>$sections],200);
    }

    public function update(UpdateSectionRequest $request, $id)
    {
        $section = $this->sectionrepository->updateSection($id, $request->validated());
        return response()->json(['section'=>$section], 200);
    }

    public function DestroySection($id)
    {
        $this->sectionrepository->destroy($id);
        return response()->noContent();
    }

    public function getSectionsByBranch($branchId)
    {
        $sections = $this->sectionrepository->getSectionByBranch($branchId);
        if (count($sections) === 0) {
            throw new DataNotFoundException('No sections found for this branch');
        }
        $sections = $sections->map(function ($section) {
            $section['class_name'] = $section->class->name;
            $section->makeHidden('class');
            return $section;
        });
        return response()->json(['sections' => $sections],200);
    }

    public function getSectionByClass($classId)
    {
        $sections = $this->sectionrepository->getSectionByClass($classId);
        $sections = $sections->map(function ($section)  use($sections){
            $section['class_name'] = $section->class->name;
            $section->makeHidden('class');
            return $section;
        });
        if (count($sections) === 0) {
            throw new DataNotFoundException('No sections found for this Class');
        }
        return response()->json(['sections' => $sections],200);
   }

   public function getSectionByClassIds(Request $request)
   {
        $sections = $this->sectionrepository->getSectionByClassIds($request);
        return response()->json(['sections' => $sections],200);
   }
}


