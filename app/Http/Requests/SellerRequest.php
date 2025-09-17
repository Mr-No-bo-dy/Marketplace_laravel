<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SellerRequest extends FormRequest
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
        $phone = $this->input('phone');
        if (is_string($phone)) {
            $phone = preg_replace('/[\s()-]/', '', $phone);
            $this->merge([
                'phone' => $phone,
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request for storing Seller.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        $idSeller = $this->route('seller')?->id_seller;
        return [
            'id_marketplace' => ['bail', 'required', 'integer', 'min:1', 'max:999999999', 'exists:App\Models\Marketplace'],
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'email' => [
                'bail',
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('sellers', 'email')->ignore($idSeller, 'id_seller'),
            ],
            'phone' => [
                'required',
                'string',
                'regex:/^\+?[0-9]{10,14}$/',
                Rule::unique('sellers', 'phone')->ignore($idSeller, 'id_seller'),
            ],
            'password' => ['required', 'string', 'min:8', 'max:255'],
        ];
    }

    /**
     * Get custom attributes for validator errors when storing Seller.
     *
     * @return array
     */
    public function attributes(): array
    {
        return [
            'name' => trans('site_profile.name'),
            'surname' => trans('site_profile.surname'),
            'email' => trans('site_profile.email'),
            'phone' => trans('site_profile.phone'),
            'password' => trans('site_profile.password'),
        ];
    }
}
