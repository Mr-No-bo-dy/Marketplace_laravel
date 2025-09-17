<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AuthRequest extends FormRequest
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
        $login = $this->input('login');
        if (is_string($login)) {
            $login = preg_replace('/[\s()-]/', '', $login);
            $this->merge([
                'login' => $login,
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'login' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'max:255'],
        ];
    }

    /**
     * Get custom attributes for validator errors when storing Client.
     *
     * @return array
     */
    public function attributes(): array
    {
        return [
            'login' => trans('auth.login'),
            'password' => trans('auth.password'),
        ];
    }
}
