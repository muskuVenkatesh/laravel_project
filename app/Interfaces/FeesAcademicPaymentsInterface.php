<?php

namespace App\Interfaces;
use Illuminate\Http\Request;

interface FeesAcademicPaymentsInterface
{
    public function createAcademicPayments($validatedData);
    public function getAcademicPayments(Request $request);
    public function getAcademicPaymentsMethod(Request $request);
}
