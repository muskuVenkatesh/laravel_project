<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLibBookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'branch_id' => 'required|exists:branches,id',
            'name' => 'required|string',
            'title' => 'required|string',
            'description' => 'nullable|string',
            'author' => 'required|string',
            'price' => 'required|numeric',
            'publisher' => 'nullable|string',
            'quantity' => 'required|integer|min:0',
            'isbn13' => 'required|string|unique:lib_book,isbn13,' . $this->route('id'),
            'isbn10' => 'nullable|string|unique:lib_book,isbn10,' . $this->route('id'),
            'display_name' => 'nullable|string',
            'published_date' => 'required'
        ];
    }
}
