<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WarehouseRequest extends FormRequest
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
            'name' => 'required|unique:warehouses,name|string|max:255' . $this->route('warehouse'),
            'address' => 'required|string|max:255',
            'photo' => 'required|image|mimes:jpg,png,jpeg|max:2048',
            'phone' => 'required|string|max:15'
        ];
    }
}
