<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Language;
use App\Services\Interfaces\PromotionServiceInterface as PromotionService;
use App\Repositories\Interfaces\PromotionRepositoryInterface as PromotionRepository;
use App\Repositories\Interfaces\SourceRepositoryInterface as SourceRepository;
use App\Http\Requests\Promotion\StorePromotionRequest;
use App\Http\Requests\Promotion\UpdatePromotionRequest;

class PromotionController extends Controller
{
    //
    protected $promotionService;
    protected $promotionRepository;
    protected $sourceRepository;
    protected $language;
    public function __construct(
        PromotionService $promotionService,
        PromotionRepository $promotionRepository,
        SourceRepository $sourceRepository
    ) {
        $this->promotionService = $promotionService;
        $this->promotionRepository = $promotionRepository;
        $this->sourceRepository = $sourceRepository;
        $this->middleware(function($request, $next) {
            $locale = app()->getLocale();
            $language = Language::where('canonical', $locale)->first();
            $this->language = $language->id;
            return $next($request);
        });
    }
    public function index(Request $request) {
        $this->authorize('modules', 'promotion.index');
        $promotions = $this->promotionService->paginate($request);
        $config = $this->config();
        $config['model'] = 'Promotion';
        $config['seo'] = __('messages.promotion');
        $template = 'backend.promotion.promotion.index';
        return
            view('backend.dashboard.layout',
            compact('template', 'config', 'promotions'));
    }
    public function create() {
        $this->authorize('modules', 'promotion.create');
        $sources = $this->sourceRepository->all();
        $config = $this->config();
        $config['seo'] = __('messages.promotion');
        $config['method'] = 'create';
        $template = 'backend.promotion.promotion.store';
        return
            view('backend.dashboard.layout',
            compact('template', 'config', 'sources'));
    }
    public function store(
        StorePromotionRequest $request,
    ) {
        if($this->promotionService->create($request, $this->language)) {
            return 
                redirect()
                ->route('promotion.index')
                ->with('success', 'Thêm mới thành viên thành công');
        }
        return 
            redirect()
            ->route('promotion.index')
            ->with('error', 'Thêm mới thành viên thất bại');
    }
    public function edit($id) {
        $this->authorize('modules', 'promotion.update');
        $promotion = $this->promotionRepository->findById($id);
        $config = $this->config();
        $config['seo'] = __('messages.promotion');
        $config['method'] = 'edit';
        $template = 'backend.promotion.promotion.store';
        return 
            view('backend.dashboard.layout',
            compact('template', 'config','promotion'));
    }
    public function update($id, UpdatePromotionRequest $request) {
        if($this->promotionService->update($id, $request)) {
            return 
                redirect()
                ->route('promotion.index')
                ->with('success', 'Cập nhật thành viên thành công');
        }
        return 
            redirect()
            ->route('promotion.index')
            ->with('error', 'Cập nhật thành viên thất bại');
    }
    public function delete($id) {
        $this->authorize('modules', 'promotion.destroy');
        $promotion = $this->promotionRepository->findById($id);
        $config['seo'] = __('messages.promotion');
        $template = 'backend.promotion.promotion.delete';
        return
            view('backend.dashboard.layout',
            compact('template', 'config', 'promotion'));
    }
    public function destroy($id) {
        if($this->promotionService->destroy($id)) {
            return 
                redirect()
                ->route('promotion.index')
                ->with('success', 'Xoá thành viên thành công');
        }
        return 
            redirect()
            ->route('promotion.index')
            ->with('error', 'Xoá thành viên thất bại. Hãy thử lại');
    }

    private function config() {
        return [
            'js' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
                'backend/library/location.js',
                'backend/plugin/ckfinder_2/ckfinder.js',
                'backend/library/finder.js',
                'backend/js/plugins/switchery/switchery.js',
                'backend/plugin/datetimepicker-master/build/jquery.datetimepicker.full.js',
                'backend/library/promotion.js',
            ],
            'css' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
                'backend/css/plugins/switchery/switchery.css',
                'backend/plugin/datetimepicker-master/build/jquery.datetimepicker.min.css',
            ]
        ];
    }
}
