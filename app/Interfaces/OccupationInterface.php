<?php

namespace App\Interfaces;

interface OccupationInterface
{
    public function storeOccupations($data);
    public function updateOccupations($data, $id);
    public function deleteOccupations($id);
    public function getOccupations($search, $sortBy, $sortOrder, $perPage);
}
