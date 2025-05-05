<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Interfaces\LanguageServiceInterface as LanguageService;
use App\Repositories\Interfaces\LanguageRepositoryInterface as LanguageRepository;
use App\Http\Requests\StoreLanguageRequest;
use App\Http\Requests\UpdateLanguageRequest;
use App\Http\Requests\TranslateRequest;
use Illuminate\Support\Facades\App;

class LanguageController extends Controller
{
    //
    protected $languageService;
    protected $languageRepository;
    public function __construct(
        LanguageService $languageService,
        LanguageRepository $languageRepository
    ) {
        $this->languageService = $languageService;
        $this->languageRepository = $languageRepository;
    }
    public function index(Request $request) {
        $this->authorize('modules', 'language.index');
        $language = $this->languageService->paginate($request);
        $config = [
            'js' => [
                'backend/js/plugins/switchery/switchery.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
            ],
            'css' => [
                'backend/css/plugins/switchery/switchery.css',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
            ],
            'model' => 'Language'
        ];
        $config['seo'] = config('apps.language');
        $template = 'backend.language.index';
        return
            view('backend.dashboard.layout',
            compact('template', 'config', 'language'));
    }
    public function create() {
        $this->authorize('modules', 'language.create');
        $config = $this->configData();
        $config['seo'] = config('apps.language');
        $config['method'] = 'create';
        $template = 'backend.language.store';
        return
            view('backend.dashboard.layout',
            compact('template', 'config'));
    }
    public function store(
        StoreLanguageRequest $request,
    ) {
        if($this->languageService->create($request)) {
            return 
                redirect()
                ->route('language.index')
                ->with('success', 'Thêm mới ngôn ngữ thành công');
        }
        return 
            redirect()
            ->route('language.index')
            ->with('error', 'Thêm mới ngôn ngữ thất bại');
    }
    public function edit($id) {
        $this->authorize('modules', 'language.update');
        $language = $this->languageRepository->findById($id);
        $config = $this->configData();
        $config['seo'] = config('apps.language');
        $config['method'] = 'edit';
        $template = 'backend.language.store';
        return 
            view('backend.dashboard.layout',
            compact('template', 'config','language'));
    }
    public function update($id, UpdateLanguageRequest $request) {
        if($this->languageService->update($id, $request)) {
            return 
                redirect()
                ->route('language.index')
                ->with('success', 'Cập nhật ngôn ngữ thành công');
        }
        return 
            redirect()
            ->route('language.index')
            ->with('error', 'Cập nhật ngôn ngữ thất bại');
    }
    public function delete($id) {
        $this->authorize('modules', 'language.destroy');
        $language = $this->languageRepository->findById($id);
        $config['seo'] = config('apps.language');
        $template = 'backend.language.delete';
        return
            view('backend.dashboard.layout',
            compact('template', 'config', 'language'));
    }
    public function destroy($id) {
        if($this->languageRepository->delete($id)) {
            return 
                redirect()
                ->route('language.index')
                ->with('success', 'Xoá ngôn ngữ thành công');
        }
        return 
            redirect()
            ->route('language.index')
            ->with('error', 'Xoá ngôn ngữ thất bại. Hãy thử lại');
    }
    private function configData() {
        return [
            'js' => [
                'backend/plugin/ckfinder_2/ckfinder.js',
                'backend/library/finder.js',
            ],
        ];
    }
    public function swithBackendLanguage($id) {
        $language = $this->languageRepository->findById($id);
        if($this->languageService->switch($id)) {
            session(['app_locale' => $language->canonical]);
            App::setLocale($language->canonical);
        }
        return redirect()->back();
    }
    public function translate($id = 0, $languageId = 0, $model = '') {
        $repositoryInstance = $this->respositoryInstance($model);
        $languageInstance = $this->respositoryInstance('Language');
        $currentLanguage = $languageInstance->findByCondition([
            ['canonical', '=', session('app_locale')],
        ]);
        $method = 'get'.$model.'ById';
        $object = $repositoryInstance->{$method}($id, $currentLanguage->id);
        $objectTranslate = $repositoryInstance->{$method}($id, $languageId);
        $this->authorize('modules', 'language.translate');
        $config = [
            'js' => [
                'backend/plugin/ckeditor_4/ckeditor.js',
                'backend/plugin/ckfinder_2/ckfinder.js',
                'backend/library/finder.js',
                'backend/library/seo.js',
                'backend/library/location.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',              
            ],
            'css' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
            ],
        ];
        $option = [
            'id' => $id,
            'languageId' => $languageId,
            'model' => $model
        ];
        $config['seo'] = config('apps.language');
        $template = 'backend.language.translate';
        return
            view('backend.dashboard.layout',
            compact('template', 'config', 'object','objectTranslate', 'option'));
    }
    public function storeTranslate(TranslateRequest $request) {
        $option = $request->input('option');
        if($this->languageService->saveTranslate($option, $request)) {
            return 
                redirect()->back()->with('success', 'Cập nhật bản ghi thành công');
        }
        return 
            redirect()
            ->back()
            ->with('error', 'Có vẫn đề xảy ra, Vui lòng thử lại');
    }
    private function respositoryInstance($model) {
        $repositoryNamespace = '\App\Repositories\\'.ucfirst($model).'Repository';
        if(class_exists($repositoryNamespace)) {
            $repositoryInstance = app($repositoryNamespace);
        }
        return $repositoryInstance;
    }
}
