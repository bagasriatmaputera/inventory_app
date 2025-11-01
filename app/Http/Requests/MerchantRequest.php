<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MerchantRequest extends FormRequest
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
            'name' => 'required|string|min:3|max:50|unique:merchants,name,' . $this->route('merchant'),
            'address' => 'required|string|max:255',
            'photo' => 'image|mimes:jpg,png,jpeg|max:2048',
            'phone' => 'required|string|max:15|unique:merchants,phone',
            'keeper_id' => 'required|exists:users,id'

        ];
    }
}
