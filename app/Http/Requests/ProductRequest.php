<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request for Storing Product.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'id_producer' => ['int'],
            'id_category' => ['int'],
            'id_subcategory' => ['int'],
            'id_seller' => ['int'],
            'name' => ['string', 'max:255'],
            'description' => ['string', 'max:511'],
            'price' => ['int'],
            'amount' => ['int'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'Назва',
            'description' => 'Опис',
            'price' => 'Ціна',
            'amount' => 'Кількість',
        ];
    }
}
