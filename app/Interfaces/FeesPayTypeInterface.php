<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface FeesPayTypeInterface
{
    public function getAll(Request $request);
    public function show($id);
    public function create($data);
    public function update($id, $data);
    public function delete($id);
}
