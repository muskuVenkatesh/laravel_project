<?php

namespace App\Interfaces;
use Illuminate\Http\Request;

interface MediumInterface
{
    public function deleteMedium($id);
    public function updateMedium($data, $id);
    public function createMedium($data);
    public function getMedium(Request $request, $perPage);
}
