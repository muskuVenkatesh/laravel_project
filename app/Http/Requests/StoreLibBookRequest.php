<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLibBookRequest extends FormRequest
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
            'isbn13' => 'required|string|unique:lib_book,isbn13',
            'isbn10' => 'nullable|string|unique:lib_book,isbn10',
            'display_name' => 'nullable|string',
            'published_date' => 'required',
        ];
    }

    public function messages(): array
{
    return [
        'branch_id.required' => 'The branch ID is required.',
        'branch_id.exists' => 'The selected branch ID is invalid.',
        'name.required' => 'The name is required.',
        'name.string' => 'The name must be a string.',
        'title.required' => 'The title is required.',
        'title.string' => 'The title must be a string.',
        'description.string' => 'The description must be a string.',
        'author.required' => 'The author is required.',
        'author.string' => 'The author must be a string.',
        'price.required' => 'The price is required.',
        'price.numeric' => 'The price must be a number.',
        'publisher.string' => 'The publisher must be a string.',
        'isbn13.required' => 'The ISBN-13 is required.',
        'isbn13.string' => 'The ISBN-13 must be a string.',
        'isbn13.unique' => 'The ISBN-13 has already been taken.',
        'isbn10.string' => 'The ISBN-10 must be a string.',
        'isbn10.unique' => 'The ISBN-10 has already been taken.',
        'display_name.string' => 'The display name must be a string.',
        'published_date.required' => 'The published date is required.',
    ];
}

}
