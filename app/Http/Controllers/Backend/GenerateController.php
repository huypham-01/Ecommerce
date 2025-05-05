<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Interfaces\GenerateServiceInterface as GenerateService;
use App\Repositories\Interfaces\GenerateRepositoryInterface as GenerateRepository;
use App\Http\Requests\StoreGenerateRequest;
use App\Http\Requests\UpdateGenerateRequest;
use Illuminate\Support\Facades\App;

class GenerateController extends Controller
{
    //
    protected $generateService;
    protected $generateRepository;
    public function __construct(
        GenerateService $generateService,
        GenerateRepository $generateRepository
    ) {
        $this->generateService = $generateService;
        $this->generateRepository = $generateRepository;
    }
    public function index(Request $request) {
        $this->authorize('modules', 'generate.index');
        $generate = $this->generateService->paginate($request);
        $config = [
            'js' => [
                'backend/js/plugins/switchery/switchery.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
            ],
            'css' => [
                'backend/css/plugins/switchery/switchery.css',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
            ],
            'model' => 'Generate'
        ];
        $config['seo'] = __('messages.generate');
        $template = 'backend.generate.index';
        return
            view('backend.dashboard.layout',
            compact('template', 'config', 'generate'));
    }
    public function create() {
        $this->authorize('modules', 'generate.create');
        $config = $this->configData();
        $config['seo'] = __('messages.generate');
        $config['method'] = 'create';
        $template = 'backend.generate.store';
        return
            view('backend.dashboard.layout',
            compact('template', 'config'));
    }
    public function store(
        StoreGenerateRequest $request,
    ) {
        if($this->generateService->create($request)) {
            return 
                redirect()
                ->route('generate.index')
                ->with('success', 'Thêm mới ngôn ngữ thành công');
        }
        return 
            redirect()
            ->route('generate.index')
            ->with('error', 'Thêm mới ngôn ngữ thất bại');
    }
    public function edit($id) {
        $this->authorize('modules', 'generate.update');
        $generate = $this->generateRepository->findById($id);
        $config = $this->configData();
        $config['seo'] = __('messages.generate');
        $config['method'] = 'edit';
        $template = 'backend.generate.store';
        return 
            view('backend.dashboard.layout',
            compact('template', 'config','generate'));
    }
    public function update($id, UpdateGenerateRequest $request) {
        if($this->generateService->update($id, $request)) {
            return 
                redirect()
                ->route('generate.index')
                ->with('success', 'Cập nhật ngôn ngữ thành công');
        }
        return 
            redirect()
            ->route('generate.index')
            ->with('error', 'Cập nhật ngôn ngữ thất bại');
    }
    public function delete($id) {
        $this->authorize('modules', 'generate.destroy');
        $generate = $this->generateRepository->findById($id);
        $config['seo'] = __('messages.generate');
        $template = 'backend.generate.delete';
        return
            view('backend.dashboard.layout',
            compact('template', 'config', 'generate'));
    }
    public function destroy($id) {
        if($this->generateRepository->delete($id)) {
            return 
                redirect()
                ->route('generate.index')
                ->with('success', 'Xoá ngôn ngữ thành công');
        }
        return 
            redirect()
            ->route('generate.index')
            ->with('error', 'Xoá ngôn ngữ thất bại. Hãy thử lại');
    }
    private function configData() {
        return [
            'js' => [
                'backend/plugin/ckeditor_4/ckeditor.js',
                'backend/plugin/ckfinder_2/ckfinder.js',
                'backend/library/finder.js',
                'backend/library/seo.js',
                'backend/library/location.js',
                'backend/js/plugins/switchery/switchery.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
                
            ],
            'css' => [
                'backend/css/plugins/switchery/switchery.css',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',

                
            ]
        ];
    }

   
}
