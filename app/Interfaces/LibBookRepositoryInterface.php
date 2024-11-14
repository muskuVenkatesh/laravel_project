<?php

namespace App\Interfaces;

interface LibBookRepositoryInterface
{
    public function getAll($validatedData, $search = null, $sortBy = 'name', $sortOrder = 'asc', $perPage = 15);
    public function find($id);
    public function createLibBook(array $data);
    public function update($id, array $data);
    public function delete($id);
}
