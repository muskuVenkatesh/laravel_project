<?php

namespace App\Interfaces;
use Illuminate\Http\Request;

interface LanguageInterface
{
    public function GetLanguages(Request $request, $perPage);
    public function createLanguage($data);
    public function updateLanguage($data,$id);
}
