<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface StudentParentDetailsInterface
{
    public function getStudentParentDetails(Request $request);
}
