<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Exceptions\DataNotFoundException;
use App\Http\Requests\StoreLibBookRequest;
use App\Http\Requests\UpdateLibBookRequest;
use App\Interfaces\LibBookRepositoryInterface;


class LibBookController extends Controller
{
    protected $libBookRepository;

    public function __construct(LibBookRepositoryInterface $libBookRepository) {
        $this->libBookRepository = $libBookRepository;
    }

    public function storeLibBook(StoreLibBookRequest $request) {
        $book = $this->libBookRepository->createLibBook($request->validated());
        return response()->response()->json(['message'=>'Book Created Successfully'],201);
    }

    public function GetAll(Request $request) {
        $validatedData = $request->validate([
            'branch_id' => 'required|exists:branches,id',
        ]);

        $search = $request->input('search', null);
        $sortBy = $request->input('sort_by', 'id');
        $sortOrder = $request->input('sort_order', 'asc');
        $perPage = $request->input('per_page', 15);

        $books = $this->libBookRepository->getAll($validatedData,$search, $sortBy, $sortOrder, $perPage);
        if (empty($books['items'])){
            throw new DataNotFoundException('Data Not Found');
        }
        return response()->json([
            'books' => $books['items'],
            'total' => $books['total']
        ],200);
    }

    public function showBook($id) {
        $book = $this->libBookRepository->find($id);
        return response()->json(['book'=>$book], 200);
    }

    public function updateBook(UpdateLibBookRequest $request, $id)
    {
        $book = $this->libBookRepository->update($id, $request->validated());
        return response()->response()->json(['message'=>'Book Updates Successfully'],201);
    }

    public function deleteBook($id){
        if ($this->libBookRepository->delete($id)) {
            return response()->json(['message' => 'Book deleted successfully.'], 200);
        }
        throw new DataNotFoundException('Data Not Found');
    }
}
