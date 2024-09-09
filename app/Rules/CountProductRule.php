<?php

namespace App\Rules;

use App\Models\Document;
use App\Models\Product;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CountProductRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (request()->post('type') === Document::DOCUMENT_EXPENSE) {
            $ids = array_column(request()->post()['products'], 'product_id');

            foreach ($ids as $id) {
                if (Product::where('id', $id)->value('count') - $value < 0) {
                    $fail('Недостаточное количество товара на складе');
                }
            }
        }
    }
}
