<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface StateRepositoryInterface
{
    public function store($data);
    public function show($id);
    public function  getAll(Request $request, $limit);
    public function update($id,$data);
    public function destroy($id);
}
