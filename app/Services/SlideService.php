<?php

namespace App\Services;
use App\Services\Interfaces\SlideServiceInterface;
use App\Repositories\Interfaces\SlideRepositoryInterface as SlideRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
/**
 * Class SlideService
 * @package App\Services
 */
class SlideService extends BaseService implements SlideServiceInterface
{
    protected $slideRepository;
    public function __construct(SlideRepository $slideRepository) {
        $this->slideRepository = $slideRepository;
    }
    public function paginate($request) {
        $condition = [
            'keyword' => addslashes($request->input('keyword')),
            'publish' => $request->integer('publish')
        ];
        $paePage = $request->integer('perpage');
        $slides = $this->slideRepository->pagination(
            $this->paginteSelect(), 
            $condition, 
            $paePage,
            ['path' => 'slide/index'],
        );
        return $slides;
    }
    public function create($request, $languageId) {
        DB::beginTransaction();
        try {
            $payload = $request->only(['_token', 'name', 'keyword', 'setting', 'short_code']);

            $payload['item'] = $this->handleSlideItem($request, $languageId);
            $slide = $this->slideRepository->create($payload);
            DB::commit();
            return true;
        }catch(\Exception $e) {
            DB::rollBack();
            echo $e->getMessage();die();
            return false;
        }
    }
    public function update($id, $request, $languageId) {
        DB::beginTransaction();
        try {
            $slide = $this->slideRepository->findById($id);
            $sliteItem = $slide->item;
            unset($sliteItem[$languageId]);
            $payload = $request->only('_token', 'name', 'keyword', 'setting', 'short_code');
            $payload['item'] = $this->handleSlideItem($request, $languageId) + $sliteItem;
            $slide = $this->slideRepository->update($id,$payload);
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
            $slide = $this->slideRepository->forceDelete($id);
            DB::commit();
            return true;
        }catch(\Exception $e) {
            DB::rollBack();
            echo $e->getMessage();die();
            return false;
        }
    }
    private function handleSlideItem($request, $languageId) {
        $slide = $request->input('slide');
        $temp = [];
        foreach($slide['image'] as $key => $val) {
            $temp[$languageId][] = [
                'image' => $val,
                'name' => $slide['name'][$key],
                'description' => $slide['description'][$key],
                'canonical' => $slide['canonical'][$key],
                'alt' => $slide['alt'][$key],
                'window' => (isset($slide['window'][$key])) ? $slide['window'][$key] : ''
            ];
        }
        return $temp;
    }
    public function convertSlideArray(array $slide = []): array {
        $temp = [];
        $fields = ['image', 'description', 'window', 'canonical', 'name', 'alt'];
        foreach($slide as $key => $val) {
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
