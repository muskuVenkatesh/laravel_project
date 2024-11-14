<?php

namespace App\Models;

use App\Services\DateService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LibBook extends Model
{
    use HasFactory;
    protected $table = 'lib_book';
    protected $fillable = [
        'branch_id',
        'name',
        'title',
        'description',
        'author',
        'price',
        'publisher',
        'quantity',
        'isbn13',
        'isbn10',
        'display_name',
        'published_date',
        'status'
    ];

    public function createLibBook($data){
        $dateFormatService = new DateService;
        return LibBook::create([
                'branch_id' => $data['branch_id'],
                'name' => $data['name'],
                'title' => $data['title'],
                'description' => $data['description'],
                'author' => $data['author'],
                'price' => $data['price'],
                'publisher' => $data['publisher'],
                'quantity' =>$data['quantity'],
                'isbn13' => $data['isbn13'],
                'isbn10' => $data['isbn10'],
                'display_name' => $data['display_name'],
                'published_date' => $dateFormatService->formatDate($data['published_date']),
            ]);
        }

    public function updateLibBook($id, $data)
    {
        $libBook = LibBook::findOrFail($id);
        $dateFormatService = new DateService;

        $libBook->update([
            'branch_id' => $data['branch_id'],
            'name' => $data['name'],
            'title' => $data['title'],
            'description' => $data['description'],
            'author' => $data['author'],
            'price' => $data['price'],
            'publisher' => $data['publisher'],
            'quantity' =>$data['quantity'],
            'isbn13' => $data['isbn13'],
            'isbn10' => $data['isbn10'],
            'display_name' => $data['display_name'],
            'published_date' => $dateFormatService->formatDate($data['published_date']),
        ]);
        return $libBook;
    }
}
