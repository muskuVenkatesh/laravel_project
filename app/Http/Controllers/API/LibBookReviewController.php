<?php

namespace App\Http\Controllers\API;
use App\Models\LibBook;
use Illuminate\Http\Request;
use App\Models\LibBookReview;
use App\Http\Controllers\Controller;
use App\Exceptions\DataNotFoundException;
use App\Interfaces\LibBookReviewInterface;
use App\Http\Requests\StoreLibBookReviewRequest;
use App\Http\Requests\UpdateLibBookReviewRequest;


class LibBookReviewController extends Controller
{
    protected $libBookRepositoryreview;

    public function __construct(LibBookReviewInterface $libBookRepositoryreview) {
        $this->libBookRepositoryreview = $libBookRepositoryreview;
    }

    public function storeLibBookReview(StoreLibBookReviewRequest $request)
    {
        $bookId = $request->validated()['book_id'];
        $book = LibBook::find($bookId);

        if (!$book) {
            throw new DataNotFoundException('Book Not Found');
        }

        if ($book->status === '0') {
            return response()->json(['message' => 'Cannot create review for a book with inactive status.'], 403);
        }
        $existingReview = LibBookReview::where('book_id', $bookId)->first();
        if ($existingReview) {
            return response()->json(['message' => 'Book review already exists.'], 409);
        }
        $bookReview = $this->libBookRepositoryreview->createLibBookReview($request->validated());
        return response()->noContent();
    }

    public function GetAllBookReviews(Request $request)
    {
        $validatedData = $request->validate([
            'branch_id' => 'required|exists:branches,id',
        ]);

        $allowedSortColumns = ['book_id', 'review', 'created_at'];
        $sortBy = $request->input('sort_by', 'book_id');
        if (!in_array($sortBy, $allowedSortColumns)) {
            $sortBy = 'book_id';
        }

        $sortOrder = $request->input('sort_order', 'asc');
        $perPage = $request->input('per_page', 15);
        $search = $request->input('search', null);

        $booksReview = $this->libBookRepositoryreview->getAll($validatedData, $search, $sortBy, $sortOrder, $perPage);

        if (empty($booksReview['items'])) {
            throw new DataNotFoundException('Data Not Found');
        }

        return response()->json([
            'booksReview' => $booksReview['items'],
            'total' => $booksReview['total']
        ], 200);
    }

    public function showBookReview($id) {
        $book = $this->libBookRepositoryreview->getBookReview($id);
        if (!$book) {
            throw new DataNotFoundException('Book review not found');
        }
        return response()->json(['book'=>$book], 200);
    }

    public function updateBookReview($id,UpdateLibBookReviewRequest $request)
    {
        $book = $this->libBookRepositoryreview->updateLibBookReview($id, $request->validated());
        return response()->noContent();
    }

    public function deleteLibBookReview($id){
        if($this->libBookRepositoryreview->deleteReview($id)){
            return response()->json(['message' => 'Book deleted successfully.'], 200);
        }
        throw new DataNotFoundException('Data Not Found');
    }
}
