<?php
namespace App\Interfaces;

use App\Models\FeesDiscountType;
use Illuminate\Http\Request;

interface FeesDiscountTypeInterface
{
    public function getAll(Request $request);
    public function create($data);
    public function show($id);
    public function update($id, $data);
    public function delete($id);
}
