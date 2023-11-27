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
     * Get the validation rules that apply to the request for storing Product.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'id_producer' => ['bail', 'integer', 'min: 1', 'max:9223372036854775807', 'exists:App\Models\Admin\Producer'],
            'id_category' => ['bail', 'integer', 'min: 1', 'max:9223372036854775807', 'exists:App\Models\Admin\Category'],
            'id_subcategory' => ['bail', 'integer', 'min: 1', 'max:9223372036854775807', 'exists:App\Models\Admin\Subcategory'],
            'id_seller' => ['bail', 'integer', 'min: 1', 'max:9223372036854775807', 'exists:App\Models\Site\Seller'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:511'],
            'price' => ['required', 'numeric', 'min:1', 'max:999999.99'],
            'amount' => ['integer', 'min:1', 'max:2147483647'],
        ];
    }

    /**
     * Get custom attributes for validator errors when storing Product.
     *
     * @return array
     */
    public function attributes(): array
    {
        return [
            'name' => trans('products.name'),
            'description' => trans('products.description'),
            'price' => trans('products.price'),
            'amount' => trans('products.amount'),
        ];
    }
}
