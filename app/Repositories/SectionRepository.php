<?php

namespace App\Repositories;

use App\Models\Section;
use Illuminate\Http\Request;
use App\Interfaces\SectionInterface;

class SectionRepository implements SectionInterface
{
    /**
     * Create a new class instance.
     */
    protected $section;
    public function __construct(Section $section)
    {
        $this->section = $section;
    }

    public function store($data){
        return Section::create($data);

    }

    public function GetAll(Request $request, $limit)
    {
        $class_id=$request->input('class_id');
        $query = Section::query()->where('sections.status', 1)
        ->where('sections.class_id', $class_id)
        ->join('classes', 'classes.id', '=', 'sections.class_id')
        ->select('sections.*', 'classes.name as class_name')
        ->withoutTrashed();
        $total = $query->count();

        if ($request->has('q')) {
            $search = $request->input('q');
            $query->where('sections.name', 'like', "%{$search}%");
        }
        if ($request->has('_sort') && $request->has('_order')) {
            $sortBy = $request->input('_sort');
            $sortOrder = $request->input('_order');
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->orderBy('sections.created_at', 'asc');
        }
        if ($limit <= 0) {
            $sections = $query->get();
        } else {
            $sections = $query->paginate($limit);
            $sections = $sections->getCollection();
        }
        if ($limit > 0) {
            return [
                'data' => $sections,
                'total' => $total,
            ];
        }
        return ['data' => $sections, 'total' => $total];
    }

    public function GetSection($id){
        return Section::findOrFail($id);

    }

    public function updateSection($id,$data)
    {
        $section = $this->section->findOrFail($id);
        $section->update($data);
        return $section;
    }

    public function destroy($id){
        $section = $this->section->findOrFail($id);
        $section->delete();
        $section->status = '0';
        $section->save();
        return $section;
    }

    public function getSectionByBranch($branchId){
        $sections = Section::whereHas('class', function ($query) use ($branchId) {
            $query->where('branch_id', $branchId);
        })->get();

        return $sections;
    }

    public function getSectionByClass($classId){
        return Section::with('class')
        ->where('class_id', $classId)
        ->get();
         return $sections;
    }

    public function getSectionByClassIds(Request $request)
    {
        $classIds = $request->input('class_id');
        return Section::whereIn('sections.class_id', $classIds)
        ->leftJoin('classes', 'classes.id', '=', 'sections.class_id')
        ->select('sections.*', 'classes.name as class_name')
        ->orderBy('sections.class_id', 'asc')
        ->get();
         return $sections;
    }
}
