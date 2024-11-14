<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface DistRepositoryInterface
{
    public function store($data);
    public function show($id);
    public function  getAll(Request $request, $limit);
    public function getDistrict($id);
    // public function update($id,$data);
    // public function destroy($id);
}
