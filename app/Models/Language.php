<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Language extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name', 'status',
    ];

    public static function createLanguage($data)
    {
        $language = Language::create([
            'name' => $data['name'],
        ]);
        return $language;
    }

    public static function updateLanguage($data, $id)
    {
        $language = Language::find($id);

        $language->update([
            'name' => $data['name'],
        ]);

        return $language;
    }
}
