<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface PersonalityInterface 
{
    public function CreatePersonality($data);
    public function GetAllPersonality(Request $request);
    public function GetPersonalityById($id);
    public function UpdatePersonality($id, $data);
    public function DeletePersonalityTraits($id);
    
}    