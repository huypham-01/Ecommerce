<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            'name'              => 'required|string',
            'email'             => 'required|string|email|unique:users,email,'.$this->id.'|max:191',
            'user_catalogue_id' => 'required|integer|gt:0',
        ];
    }
    public function messages() {
        return [
            'name.required'    => 'Bạn chưa nhập họ và tên',
            'name.string'      => 'Họ và tên chưa đúng định dang',
            'email.required'       => 'Bạn chưa nhập email',
            'email.email'          => 'Email chưa đúng định dạng. Ví dụ: abc@gmail.com',
            'email.unique'         => 'Email đã tồn tại. Vui lòng nhập Email khác',
            'email.string'         => 'Email không đúng định dạng',
            'email.max'            => 'Độ dài Email không vượt quá 191 ký tự',
            'user_catalogue_id.gt' => 'Bạn chưa chọn nhóm thành viên',
        ];
    }
}
