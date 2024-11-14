<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLibBookReviewRequest extends FormRequest
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
                'book_id' => 'required|integer|exists:lib_book,id',
                'review' => 'required|string|max:255',
                'rating' => 'required|string|in:1,2,3,4,5'
            ];
    }

    public function messages(): array
    {
        return
        [
            'book_id.required' => 'The book ID is required.',
            'book_id.exists' => 'The selected book does not exist.',
            'review.required' => 'The review is required.',
            'review.string' => 'The review must be a string.',
            'review.max' => 'The review must not exceed 255 characters.',
            'rating.required' => 'The rating is required.',
        ];
    }
}
