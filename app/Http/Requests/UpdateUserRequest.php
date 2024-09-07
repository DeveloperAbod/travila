<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;


class UpdateUserRequest extends FormRequest
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
            'name' => ['sometimes', 'string', 'max:255'],
            'last_name' => ['sometimes', 'string', 'max:255'],
            'email' => ['sometimes', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['sometimes', 'confirmed', 'min:8', 'max:255', Rules\Password::defaults()],
            'phone' => 'numeric|digits:9|sometimes|regex:/^7[0-9]{8}$/|unique:users',
            'gender' => ['sometimes', 'string', 'in:male,female'],
        ];
    }
}
