<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Interfaces\SlideServiceInterface as SlideService;
use App\Services\Interfaces\SlideCatalogueServiceInterface as SlideCatalogueService;
use App\Repositories\Interfaces\SlideCatalogueRepositoryInterface as SlideCatalogueRepository;
use App\Repositories\Interfaces\SlideRepositoryInterface as SlideRepository;
use App\Models\Language;
use App\Http\Requests\StoreSlideRequest;
use App\Http\Requests\UpdateSlideRequest;

class SlideController extends Controller
{
    //
    protected $slideService;
    protected $slideRepository;
    protected $slideCatalogueRepository;
    public function __construct(
        SlideService $slideService,
        SlideRepository $slideRepository,
    ) {
        $this->slideService = $slideService;
        $this->slideRepository = $slideRepository;

        $this->middleware(function($request, $next) {
            $locale = app()->getLocale();
            $language = Language::where('canonical', $locale)->first();
            $this->language = $language->id;
            return $next($request);
        });
    }
    
    public function index(Request $request) {
        $this->authorize('modules', 'slide.index');
        $slides = $this->slideService->paginate($request, $this->language);
        $config = $this->config();
        $config['model'] = 'Slide';
        $config['seo'] = __('messages.slide');
        $template = 'backend.slide.slide.index';
        return
            view('backend.dashboard.layout',
            compact('template', 'config', 'slides'));
    }
    public function create() {
        $this->authorize('modules', 'slide.create');
        $config = $this->config();
        $config['seo'] = __('messages.slide');
        $config['method'] = 'create';
        $template = 'backend.slide.slide.store';
        return
            view('backend.dashboard.layout',
            compact('template', 'config'));
    }
    public function store(StoreSlideRequest $request) {
        if($this->slideService->create($request, $this->language)) {
            return redirect()->route('slide.index')->with('success', 'Cập nhật slide thành công');
        }
        return 
            redirect()->route('slide.index')->with('error', 'Cập nhật Slide thất bại');
    }
    public function edit($id) {
        $this->authorize('modules', 'slide.update');
        $slide = $this->slideRepository->findById($id);
        $slideItem = $this->slideService->convertSlideArray($slide->item[$this->language]);

        $config = $this->config();
        $config['seo'] = __('messages.slide');
        $config['method'] = 'edit';
        $template = 'backend.slide.slide.store';
        return 
            view('backend.dashboard.layout',
            compact('template', 'config', 'slide', 'slideItem'));
    }
    public function update($id, UpdateSlideRequest $request) {
        if($this->slideService->update($id, $request, $this->language)) {
            return 
                redirect()
                ->route('slide.index')
                ->with('success', 'Cập nhật thành viên thành công');
        }
        return 
            redirect()
            ->route('slide.index')
            ->with('error', 'Cập nhật thành viên thất bại');
    }
    public function delete($id) {
        $this->authorize('modules', 'slide.destroy');
        $slide = $this->slideRepository->findById($id);
        $config['seo'] = __('messages.slide');
        $template = 'backend.slide.slide.delete';
        return
            view('backend.dashboard.layout',
            compact('template', 'config', 'slide'));
    }
    public function destroy($id) {
        if($this->slideService->destroy($id)) {
            return 
                redirect()
                ->route('slide.index')
                ->with('success', 'Xoá thành viên thành công');
        }
        return 
            redirect()
            ->route('slide.index')
            ->with('error', 'Xoá thành viên thất bại. Hãy thử lại');
    }
    private function config() {
        return [
            'js' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
                'backend/library/slide.js',
                'backend/plugin/ckfinder_2/ckfinder.js',
                'backend/js/plugins/nestable/jquery.nestable.js',
                'backend/plugin/nice-select/js/jquery.nice-select.min.js',
                'backend/js/plugins/switchery/switchery.js',
                
            ],
            'css' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
                'backend/plugin/nice-select/css/nice-select.css',
                'backend/css/plugins/switchery/switchery.css',
            ]
        ];
    }
}
