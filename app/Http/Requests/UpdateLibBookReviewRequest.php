<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLibBookReviewRequest extends FormRequest
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
            'book_id' => 'sometimes|integer|exists:lib_book,id',
            'review' => 'sometimes|string|max:255',
            'rating' => 'sometimes|string|in:1,2,3,4,5',
        ];
    }

    public function messages(): array
    {
        return [
            'book_id.sometimes' => 'The book ID is optional for updates but must be an integer if provided.',
            'book_id.integer' => 'The book ID must be a valid integer.',
            'book_id.exists' => 'The selected book ID does not exist.',
            'review.sometimes' => 'The review is optional but must be valid text if provided.',
            'review.string' => 'The review must be a string.',
            'review.max' => 'The review cannot exceed 255 characters.',
            'rating.sometimes' => 'The rating is optional but must be valid if provided.',
            'rating.string' => 'The rating must be a string.',
            'rating.in' => 'The rating must be one of the following values: 1, 2, 3, 4, or 5.',
        ];
    }
}
