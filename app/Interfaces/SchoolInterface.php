<?php

namespace App\Interfaces;
use Illuminate\Http\Request;

interface SchoolInterface
{
  public function CreateSchool($data);
  public function GetSchool($data);
  public function GetAllSchool(Request $request, $perPage);
  public function DeleteSchool($data);
  public function GetCurrencyType();
  public function UpdateSchool($schoolId,$data);



}
