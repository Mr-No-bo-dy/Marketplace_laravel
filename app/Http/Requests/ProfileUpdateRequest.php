<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
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
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['string', 'max:255'],
            'surname' => ['nullable', 'string', 'max:255'],
            'email' => ['email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
            'phone' => ['required', 'string', 'regex:/^\+?[0-9]{10,14}$/'],
        ];
    }

    /**
     * Get custom attributes for validator errors when storing User.
     *
     * @return array
     */
    public function attributes(): array
    {
        return [
            'name' => trans('admin/admins.name'),
            'surname' => trans('admin/admins.surname'),
            'email' => trans('admin/admins.email'),
            'phone' => trans('admin/admins.phone'),
        ];
    }
}
