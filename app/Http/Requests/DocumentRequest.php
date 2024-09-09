<?php

namespace App\Http\Requests;

use App\Models\Document;
use App\Rules\CountProductRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class DocumentRequest extends FormRequest
{
    public const array TYPES = [
        Document::DOCUMENT_INCOME,
        Document::DOCUMENT_EXPENSE,
        Document::DOCUMENT_INVENTORY
    ];

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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        $rules = [
            'type' => ['required', Rule::in(self::TYPES)],
            'created_at' => ['nullable', 'date_format:Y-m-d H:i:s'],
            'products' => ['required', 'array'],
            'products.*.product_id' => ['exists:App\Models\Product,id'],
            'products.*.quantity' => [new CountProductRule(), 'required', 'numeric', 'min:0'],
        ];

        if ($this->type === Document::DOCUMENT_INCOME) {
            $rules['products.*.price'] = ['required', 'numeric', 'min:0'];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'type.in' => 'Доступные типы документов: ' . implode(', ', self::TYPES),
            'required' => 'Поле :attribute обязательно для заполнения',
            'created_at' => 'Поле :attribute в формате Y-m-d H:i:s',
            'products.*.product_id' => 'Создать документ можно к существующему товару',
            'numeric' => 'Поле :attribute задается как число',
            'min' => 'Минимальное значение :min',
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        $response = response()->json([
            'message' => 'Переданные данные некорректны',
            'errors' => $validator->errors()
        ], 422);

        throw new HttpResponseException($response);
    }
}
