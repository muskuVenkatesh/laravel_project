<?php

namespace App\Interfaces;
use Illuminate\Http\Request;

interface PDFInterface
{
    public function generatePDF(Request $request, $html, $filename);
    public function generateRecipetPDF(Request $request, $html, $filename);
}
