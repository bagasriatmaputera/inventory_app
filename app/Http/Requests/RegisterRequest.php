<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'name' => 'required|string|min:5|max:255',
            'email' => 'required|email|unique:users,email',
            'photo' => 'nullable|mimes:jpg,jpeg,png|max:2048',
            'phone' => 'required|string|min:8|unique:users,phone',
            'password' => 'required|min:8|string|confirmed'
        ];
    }
}
