<?php

namespace App\Interfaces;
use Illuminate\Http\Request;

interface ClassesInterface
{
    public function deleteClass($id);
    public function updateClass($data, $id);
    public function createClass($data);
    public function getClass(Request $request, $perPage);
    public function getClassesByBranch($id);

}
