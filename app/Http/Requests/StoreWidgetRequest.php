<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWidgetRequest extends FormRequest
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
            'keyword' => 'required|unique:widgets',
            'short_code' => 'required|unique:widgets',
        ];
    }
    public function messages():array {
        return [
            'name.required' => 'Bạn chưa nhập tên Widget',
            'keyword.required' => 'Bạn chưa nhập từ khoá Widget',
            'keyword.unique' => 'Từ khoá đã tồn tại bạn, hãy nhập từ khoá khác',
            'short_code.required' => 'Bạn chưa nhập vào short_code',
            'short_code.unique' => 'ShortCode đã tồn tại, hãy nhập shortcode khác',
        ];
    }
}
