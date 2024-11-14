<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface SmsTemplateInterface
{
   public function Store($data);
   public function getSmsTemplates(Request $request, $limit);
   public function getSmsTemplate($id);
   public function updatetemplate($id, $validatedData);
   public function deletetemplate($id);  
}
