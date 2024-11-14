<?php

namespace App\Interfaces;

interface HomeworkInterface
{
    public function storeHomework($data);
    public function getHomeworks($branchId, $classId, $sectionId, $date);
    public function getHomework($id);
}
