<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Interfaces\{class}ServiceInterface as {class}Service;
use App\Repositories\Interfaces\{class}RepositoryInterface as {class}Repository;
use App\Http\Requests\Store{class}Request;
use App\Http\Requests\Update{class}Request;
use App\Http\Requests\Delete{class}CatalogueRequest;
use App\Classes\Nestedsetbie;
use App\Models\Language;

class {class}Controller extends Controller
{
    //
    protected ${module}Service;
    protected ${module}Repository;
    protected $nestedset;
    protected $language;
    public function __construct(
        {module}Service ${module}Service,
        {module}Repository ${module}Repository
    ) {
        $this->middleware(function($request, $next) {
            $locale = app()->getLocale();
            $language = Language::where('canonical', $locale)->first();
            $this->language = $language->id;
            $this->initialize();
            return $next($request);
        });

        $this->{module}Service = ${module}Service;
        $this->{module}Repository = ${module}Repository;
        $this->nestedset = new Nestedsetbie([
            'table' => '{module}_catalogues',
            'foreignkey' => '{module}_catalogue_id',
            'language_id' => $this->language,
        ]);
        $this->language = $this->currentLanguage();
    }
    public function index(Request $request) {
        $this->authorize('modules', '{module}.index');
        ${module}s = $this->{module}Service->paginate($request, $this->language);
        $config = $this->configData();
        $config['seo'] = __('messages.{module}');
        $config['model'] = '{class}';
        $dropdown = $this->nestedset->Dropdown();
        $template = 'backend.{module}.{module}.index';
        return
            view('backend.dashboard.layout',
            compact('template', 'config', '{module}s', 'dropdown'));
    }
    public function create() {
        $this->authorize('modules', '{module}.create');
        $config = $this->configData();
        $config['seo'] = __('messages.{module}');
        $config['method'] = 'create';
        $config['model'] = '{class}';
        $dropdown = $this->nestedset->Dropdown();
        $template = 'backend.{module}.{module}.store';
        return
            view('backend.dashboard.layout',
            compact('template', 'config', 'dropdown'));
    }
    public function store(
        Store{class}Request $request,
    ) {
        if($this->{module}Service->create($request, $this->language)) {
            return 
                redirect()
                ->route('{module}.index')
                ->with('success', 'Thêm mới bài viết thành công');
        }
        return 
            redirect()
            ->route('{module}.index')
            ->with('error', 'Thêm mới bài viết thất bại');
    }
    public function edit($id) {
        $this->authorize('modules', '{module}.update');
        ${module} = $this->{module}Repository->get{class}ById($id, $this->language);
        $config = $this->configData();
        $config['seo'] = __('messages.{module}');
        $config['method'] = 'edit';
        $dropdown = $this->nestedset->Dropdown();
        $album = json_decode(${module}->album);
        $template = 'backend.{module}.{module}.store';
        return 
            view('backend.dashboard.layout',
            compact(
                'template', 
                'config', 
                'dropdown', 
                '{module}', 
                'album'
            ));
    }
    public function update($id, Update{class}Request $request) {
        if($this->{module}Service->update($id, $request, $this->language)) {
            return 
                redirect()
                ->route('{module}.index')
                ->with('success', 'Cập nhật bài viết thành công');
        }
        return 
            redirect()
            ->route('{module}.index')
            ->with('error', 'Cập nhật bài viết thất bại');
    }
    public function delete($id) {
        $this->authorize('modules', '{module}.catalogue.destroy');
        ${module} = $this->{module}Repository->get{class}ById($id, $this->language);
        $config['method'] = 'delete';
        $config['seo'] = __('messages.{module}');
        $template = 'backend.{module}.{module}.delete';
        return
            view('backend.dashboard.layout',
            compact('template', 'config', '{module}'));
    }
    public function destroy($id) {
        if($this->{module}Service->destroy($id)) {
            return 
                redirect()
                ->route('{module}.index')
                ->with('success', 'Xoá nhóm thành viên thành công');
        }
        return 
            redirect()
            ->route('{module}.index')
            ->with('error', 'Xoá nhóm thành viên thất bại. Hãy thử lại');
    }
    private function initialize() {
        $this->nestedset = new Nestedsetbie([
            'table' => '{module}_catalogues',
            'foreignkey' => '{module}_catalogue_id',
            'language_id' => $this->language,
        ]);
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
