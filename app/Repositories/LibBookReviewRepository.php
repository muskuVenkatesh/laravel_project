<?php
namespace App\Repositories;
use App\Models\LibBookReview;
use App\Interfaces\LibBookReviewInterface;

class LibBookReviewRepository implements LibBookReviewInterface {
    protected $libbookreview;
    public function __construct(LibBookReview $libbookreview)
    {
        $this->libbookreview = $libbookreview;
    }

    public function createLibBookReview(array $data){
        return $this->libbookreview->createLibBookReview($data);
    }

    public function getAll($validatedData, $search = null, $sortBy = 'name', $sortOrder = 'asc', $perPage = 15) {
        $query = LibBookReview::query()
            ->select('lib_book_review.*')
            ->join('lib_book', 'lib_book_review.book_id', '=', 'lib_book.id')
            ->where('lib_book_review.status', 1)
            ->where('lib_book.branch_id', $validatedData['branch_id'])
            ->where('lib_book.status',1);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('lib_book_review.review', 'like', "%$search%")
                ->orWhere('rating', 'like', "%$search%");

            });
        }

        $query->orderBy($sortBy, $sortOrder);
        $books = $query->paginate($perPage);

        return [
            'items' => $books->items(),
            'total' => $books->total(),
        ];
    }
    
    public function getBookReview($id) {
        return $this->libbookreview->find($id);
    }

    public function updateLibBookReview($id, array $data){
        return $this->libbookreview->updateLibBookReview($id,$data);
    }

    public function deleteReview($id){
        $bookreview = $this->libbookreview->find($id);
        if ($bookreview) {
            $bookreview->status = 0;
            return $bookreview->save();
        }
    }
}
