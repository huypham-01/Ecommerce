<?php

namespace App\Http\Requests\Promotion;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\Promotions\OrderAmountRangeRule;
use App\Rules\Promotions\ProductAndQuantytiRule;
use App\Enums\PromotionEnum;
class StorePromotionRequest extends FormRequest
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
        $rules = [
            'name' => 'required',
            'code' => 'required',
            'startDate' => 'required|custome_date_format',
        ];
        $date = $this->only('startDate', 'endDate');
        if(!$this->input('neverEndDate')) {
            $rules['endDate'] = 'required|custome_date_format|custome_after:startDate';
        }
        $method = $this->input('method');
        switch($method) {
            case PromotionEnum::ORDER_AMOUNT_RANGE :
                $rules['method'] = [new OrderAmountRangeRule($this->input('promotion_order_amount_range'))];
                break;
            case PromotionEnum::PRODUCT_AND_QUANTITY :
                $rules['method'] = [new ProductAndQuantytiRule($this->only('product_and_quantity', 'object'))];
                break;
        }

        return $rules;
    }
    public function messages() {
        $messages = [
            'name.required'         => 'Bạn chưa nhập tên khuyến mãi',
            'code.required'         => 'Bạn chưa nhập từ khoá của khuyến mãi',
            'code.unique'      => 'Mã khuyễn mãi đã tồn tại, hãy nhập mã khác',
            'startDate.required'      => 'Bạn chưa nhập ngày bắt đầu khuyến mãi',
            'startDate.custome_date_format'      => 'Ngày bắt đầu không đúng định dạng',
            'endDate.required'      => 'Bạn chưa nhập ngày kết thúc chương trình khuyến mãi',
            'endDate.custome_date_format'      => 'Ngày kết thúc không đúng định dạng',
        ];
        if(!$this->input('neverEndDate')) {
            $messages['endDate.required'] = 'Bạn chưa chọn ngày kết thúc của khuyến mãi';
            $messages['endDate.custome_after'] = 'Ngày kết thúc khuyến mãi phải lớn hơn ngày bắt đầu';
        }
        return $messages;
    }
}
