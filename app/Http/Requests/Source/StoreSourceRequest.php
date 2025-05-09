<?php

namespace App\Http\Requests\Source;

use Illuminate\Foundation\Http\FormRequest;

class StoreSourceRequest extends FormRequest
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
            'keyword' => 'required|unique:sources',
        ];
    }
    public function messages(): array
    {
        return [
            'name.required' => 'Bạn chưa nhập tên của nguồn khách',
            'keyword.required' => 'Bạn chưa nhập từ khoá của nguồn khách',
            'keyword.unique' => 'Từ khoá đã tồn tại, vui lòng nhập từ khác',
        ];
    }
}
