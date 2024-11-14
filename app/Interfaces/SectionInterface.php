<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface SectionInterface
{
    public function store($data);
    public function GetAll(Request $request, $limit);
    public function GetSection($id);
    public function updateSection($id,$data);
    public function destroy($id);
    public function getSectionByBranch($id);
    public function getSectionByClass($id);
    public function getSectionByClassIds(Request $request);

}
