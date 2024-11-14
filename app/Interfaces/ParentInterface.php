<?php

namespace App\Interfaces;

interface ParentInterface
{

    public function store($data);
    public function Getall($validatedData, $search = null, $sortBy = 'first_name', $sortOrder = 'asc', $perPage = 15);
    public function show($id);
    public function updateParent($id, $data);
    public function destroy($id);
    public function getStudentByparent($Parent_id);


}
