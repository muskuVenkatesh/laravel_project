<?php

namespace App\Interfaces;

interface QualificationInterface
{
    public function StoreQualifications($data);
    public function UpdateQualifications($data, $id);
    public function deleteQualifications($data);
    public function getQualifications($search, $sortBy, $sortOrder, $perPage);
}
