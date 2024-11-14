<?php
namespace App\Repositories;
use App\Models\LibBook;
use App\Interfaces\LibBookRepositoryInterface;

class LibBookRepository implements LibBookRepositoryInterface {
    protected $libbook;
    public function __construct(LibBook $libbook)
    {
        $this->libbook = $libbook;
    }

    public function createLibBook(array $data){
        return $this->libbook->createLibBook($data);
    }

    public function getAll($validatedData, $search = null, $sortBy = 'id', $sortOrder = 'asc', $perPage = 15) {
        $query = $this->libbook
            ->where('status', 1)
            ->where('branch_id', $validatedData['branch_id']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('title', 'like', "%$search%")
                  ->orWhere('description', 'like', "%$search%")
                  ->orWhere('author', 'like', "%$search%")
                  ->orWhere('isbn13', 'like', "%$search%")
                  ->orWhere('isbn10', 'like', "%$search%")
                  ->orWhere('publisher', 'like', "%$search%")
                  ->orWhere('display_name', 'like', "%$search%");
                });
        }

        $query->orderBy($sortBy, $sortOrder);
        $books = $query->paginate($perPage);
        return [
            'items' => $books->items(),
            'total' => $books->total(),
        ];
    }

    public function find($id) {
        return $this->libbook->findOrFail($id);
    }

    public function update($id, array $data){
        return $this->libbook->updateLibBook($id,$data);
    }

    public function delete($id){
        $book = $this->find($id);
        if ($book) {
            $book->status = 0;
            return $book->save();
        }
    }
}
