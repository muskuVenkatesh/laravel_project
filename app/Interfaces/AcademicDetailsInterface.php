<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface AcademicDetailsInterface
{
    public function store($data);
    public function getall(Request $request, $limit);
    public function showAcademic($id);
    public function update($id,$data);
    public function destroy($id);
}
