<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class PasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request for changing Password.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'old_password' => ['required', 'string', 'min:8', 'max:255'],
            'new_password' => ['required', 'string', 'min:8', 'max:255'],
            'new_password2' => ['required', 'string', 'min:8', 'max:255'],
        ];
    }

    /**
     * Get custom attributes for validator errors when changing Password.
     *
     * @return array
     */
    public function attributes(): array
    {
        return [
            'old_password' => trans('site_profile.password'),
            'new_password' => trans('site_profile.password'),
            'new_password2' => trans('site_profile.password'),
        ];
    }
}
