<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class updaterequestidcard extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'id_type' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'file_path' => 'nullable'
        ];
    }
}
