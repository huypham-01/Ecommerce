<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMenuCatalogueRequest extends FormRequest
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
            'name' => 'required',
            'keyword' => 'required|unique:menu_catalogues',
        ];
    }
    public function messages() {
        return [
            'name.required'         => 'Bạn chưa nhập tên của nhóm menu',
            'keyword.required'    => 'Bạn chưa nhập ô từ khoá',
            'keyword.unique'      => 'Nhóm menu đã tồn tại',
        ];
    }
}
