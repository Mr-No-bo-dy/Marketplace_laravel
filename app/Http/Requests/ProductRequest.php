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
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'id_seller' => session('id_seller'),
        ]);
    }

    /**
     * Get the validation rules that apply to the request for storing Product.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'id_producer' => ['bail', 'required', 'integer', 'min:1', 'max:999999999', 'exists:App\Models\Producer'],
            'id_category' => ['bail', 'required', 'integer', 'min:1', 'max:999999999', 'exists:App\Models\Category'],
            'id_subcategory' => ['bail', 'required', 'integer', 'min:1', 'max:999999999', 'exists:App\Models\Subcategory'],
            'id_seller' => ['bail', 'required', 'integer', 'min:1', 'max:999999999', 'exists:App\Models\Seller'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:511'],
            'price' => ['required', 'numeric', 'min:1', 'max:999999.99'],
            'amount' => ['integer', 'min:1', 'max:2147483647'],
            'images.*' => ['nullable', 'image', 'mimes:jpg,jpeg,png,bmp,gif,webp,svg', 'max:5120'],
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
            'images' => trans('products.images'),
        ];
    }
}
