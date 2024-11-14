<?php

namespace App\Interfaces;
use Illuminate\Http\Request;
interface IdInterface
{
    public function CreateId($data);
    public function GetAllId(Request $request, int $limit, $id_type);
    public function getIdCardById($id);
    public function updateIdCard($id,$data);
    public function softDeleteIdCard($id);
}
