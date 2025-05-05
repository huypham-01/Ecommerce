<?php

namespace App\Services;
use App\Services\Interfaces\PromotionServiceInterface;
use App\Repositories\Interfaces\PromotionRepositoryInterface as PromotionRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use App\Enums\PromotionEnum;
/**
 * Class PromotionService
 * @package App\Services
 */
class PromotionService extends BaseService implements PromotionServiceInterface
{
    protected $promotionRepository;
    public function __construct(PromotionRepository $promotionRepository) {
        $this->promotionRepository = $promotionRepository;
    }
    public function paginate($request) {
        $condition = [
            'keyword' => addslashes($request->input('keyword')),
            'publish' => $request->integer('publish')
        ];
        $paePage = $request->integer('perpage');
        $promotions = $this->promotionRepository->pagination(
            $this->paginteSelect(), 
            $condition, 
            $paePage,
            ['path' => 'promotion/index'],
        );
        return $promotions;
    }
    public function create($request, $languageId) {
        DB::beginTransaction();
        try {
            $payload = $request->only(
                'name', 
                'code', 
                'description', 
                'method', 
                'startDate',
                'endDate',
                'neverEndDate',
            );
            switch($payload['method']) {
                case PromotionEnum::ORDER_AMOUNT_RANGE :
                    $payload[PromotionEnum::DISCOUNT] = $this->orderByRange($request);
                    break;
                case PromotionEnum::PRODUCT_AND_QUANTITY :
                    $payload[PromotionEnum::DISCOUNT] = $this->productAndQuantity($request);
                    $promotion = $this->promotionRepository->create($payload);
                    if($promotion->id > 0) {
                        $object = $request->input('object');
                        $payloadRelation = [];
                        foreach($object['id'] as $key => $val) {
                            $payloadRelation[] = [
                                'promotion_id' =>$promotion->id,
                                'product_id' => $val,
                                'product_variant_id' => $object['product_variant_id'][$key],
                                'model' => $request->input(PromotionEnum::MODULE_TYPE),
                            ];
                        }
                    }
                    dd($temp);
                    break;
            }
            
            $payload['item'] = $this->handlePromotionItem($request, $languageId);
            $promotion = $this->promotionRepository->create($payload);
            die();
            DB::commit();
            return true;
        }catch(\Exception $e) {
            DB::rollBack();
            echo $e->getMessage();die();
            return false;
        }
    }
    private function handleSourceAndCondition($request) {
        $data = [
            'source' => [
                'status' => $request->input('source'),
                'data' => $request->input('sourceValue'),
            ],
            'apply' => [
                'status' => $request->input('applyStatus'),
                'data' => $request->input('applyValue'),
            ],
        ];
        // không chọn sẽ lỗi
        foreach($data['apply']['data'] as $key => $val) {
            $data['apply']['condition'][$val] = $request->input($val); 
        }
        return $data;
    }
    private function orderByRange($request) {
        $data['info'] = $request->input('promotion_order_amount_range');
        return $data + $this->handleSourceAndCondition($request);
    }
    private function productAndQuantity($request) {
        $data['info'] = $request->input('product_and_quantity');
        $data['info']['model'] = $request->input(PromotionEnum::MODULE_TYPE);
        $data['info']['object'] = $request->input('object');
        return $data + $this->handleSourceAndCondition($request);
    }
    public function update($id, $request, $languageId) {
        DB::beginTransaction();
        try {
            $promotion = $this->promotionRepository->findById($id);
            $sliteItem = $promotion->item;
            unset($sliteItem[$languageId]);
            $payload = $request->only('_token', 'name', 'keyword', 'setting', 'short_code');
            $payload['item'] = $this->handlePromotionItem($request, $languageId) + $sliteItem;
            $promotion = $this->promotionRepository->update($id,$payload);
            DB::commit();
            return true;
        }catch(\Exception $e) {
            DB::rollBack();
            echo $e->getMessage();die();
            return false;
        }
    }
    public function destroy($id) {
        DB::beginTransaction();
        try {
            $promotion = $this->promotionRepository->forceDelete($id);
            DB::commit();
            return true;
        }catch(\Exception $e) {
            DB::rollBack();
            echo $e->getMessage();die();
            return false;
        }
    }
    private function handlePromotionItem($request, $languageId) {
        $promotion = $request->input('promotion');
        $temp = [];
        foreach($promotion['image'] as $key => $val) {
            $temp[$languageId][] = [
                'image' => $val,
                'name' => $promotion['name'][$key],
                'description' => $promotion['description'][$key],
                'canonical' => $promotion['canonical'][$key],
                'alt' => $promotion['alt'][$key],
                'window' => (isset($promotion['window'][$key])) ? $promotion['window'][$key] : ''
            ];
        }
        return $temp;
    }
    public function convertPromotionArray(array $promotion = []): array {
        $temp = [];
        $fields = ['image', 'description', 'window', 'canonical', 'name', 'alt'];
        foreach($promotion as $key => $val) {
            foreach($fields as $field) {
                $temp[$field][] = $val[$field];
            }
        }
        return $temp;
    }
    private function paginteSelect() {
        return [
            'id',
            'name',
            'keyword',
            'publish',
            'item'
        ];
    }
}
