<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FeesAcademicPaymentRequest extends FormRequest
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
            'fees_st_pay_id' => 'required',
            'student_id' => 'required',
            'transaction_id' => 'nullable|string|max:255|unique:fees_academic_payments,transaction_id',
            'fees_amount' => 'required',
            'discount' => 'required',
            'amount_to_pay' => 'required',
            'amount_paid' => 'required',
            'fine' => 'nullable',
            'payment_date' => 'nullable',
        ];
    }
}
