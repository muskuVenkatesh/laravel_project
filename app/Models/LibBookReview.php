<?php

namespace App\Models;
use App\Models\LibBook;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LibBookReview extends Model
{
    use HasFactory;
    protected $table = 'lib_book_review';
    protected $fillable = [
        'book_id',
        'review',
        'rating',
        'status'
    ];

    public function createLibBookReview($data)
    {
       return LibBookReview::create([
                'book_id' => $data['book_id'],
                'review' => $data['review'],
                'rating' => $data['rating'],
            ]);
    }

    public function updateLibBookReview($id, $data)
    {
        $libBookReview= LibBookReview::findOrFail($id);
        $libBookReview->update([
            'book_id' => $data['book_id'],
            'review' => $data['review'],
            'rating' => $data['rating'],
        ]);
        return $libBookReview;
    }
}
