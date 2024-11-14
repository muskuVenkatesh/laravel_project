<?php

namespace App\Interfaces;

interface LibBookReviewInterface
{
    public function createLibBookReview(array $data);
    public function getAll($validatedData,$search = null, $sortBy = 'book_id', $sortOrder = 'asc', $perPage = 15);
    public function getBookReview($id);
    public function updateLibBookReview($id, array $data);
    public function deleteReview($id);
}
