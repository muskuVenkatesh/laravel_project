<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface FeesTimelineInterface
{
    public function getById($id);
    public function getAll(Request $request);
    public function create($data);
    public function update($id, $data);
    public function delete($id);
}