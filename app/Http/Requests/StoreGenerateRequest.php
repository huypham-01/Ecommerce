<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGenerateRequest extends FormRequest
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
            'name' => 'required|unique:generates',
            //'module_type' => 'gt:0',
            'schema' => 'required',
        ];
    }
    public function messages() {
        return [
            'name.required'         => 'Bạn chưa nhập tên Module',
            'name.unique'           => 'Tên Module đã tồn tại',
            //'module_type'           => 'Bạn phải chọn kiểu Module',
            'schema.required'       => 'Bạn chưa nhập vào Schema' ,
        ];
    }
}
