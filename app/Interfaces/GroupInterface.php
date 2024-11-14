<?php

namespace App\Interfaces;
use Illuminate\Http\Request;

interface GroupInterface
{
    public function StoreGroups($data);
    public function getGroups(Request $request, $perPage, $branchId);
    public function UpdateGroup($data, $id);
    public function deleteGroup($id);
}
