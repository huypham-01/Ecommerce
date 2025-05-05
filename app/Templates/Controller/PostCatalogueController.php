<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Interfaces\{ModuleTemplate}ServiceInterface as {ModuleTemplate}Service;
use App\Repositories\Interfaces\{ModuleTemplate}RepositoryInterface as {ModuleTemplate}Repository;
use App\Http\Requests\Store{ModuleTemplate}Request;
use App\Http\Requests\Update{ModuleTemplate}Request;
use App\Http\Requests\Delete{ModuleTemplate}Request;
use App\Classes\Nestedsetbie;
use App\Models\Language;

class {ModuleTemplate}Controller extends Controller
{
    //
    protected ${moduleTemplate}Service;
    protected ${moduleTemplate}Repository;
    protected $nestedset;
    protected $language;
    public function __construct(
        {ModuleTemplate}Service ${moduleTemplate}Service,
        {ModuleTemplate}Repository ${moduleTemplate}Repository
    ) {
        $this->middleware(function($request, $next) {
            $locale = app()->getLocale();
            $language = Language::where('canonical', $locale)->first();
            $this->language = $language->id;
            $this->initialize();
            return $next($request);
        });
        $this->{moduleTemplate}Service = ${moduleTemplate}Service;
        $this->{moduleTemplate}Repository = ${moduleTemplate}Repository;
    }
    public function index(Request $request) {
        $this->authorize('modules', '{moduleView}.index');
        ${moduleTemplate}s = $this->{moduleTemplate}Service->paginate($request);
        $config = $this->configData();
        $config['seo'] = __('messages.{moduleTemplate}');
        $config['model'] = '{ModuleTemplate}';
        $template = 'backend.{moduleView}.index';
        return
            view('backend.dashboard.layout',
            compact('template', 'config', '{moduleTemplate}s'));
    }
    public function create() {
        $this->authorize('modules', '{moduleView}.create');
        $config = $this->configData();
        $config['seo'] = __('messages.{moduleTemplate}');
        $config['method'] = 'create';
        $dropdown = $this->nestedset->Dropdown();
        $template = 'backend.{moduleView}.store';
        return
            view('backend.dashboard.layout',
            compact('template', 'config', 'dropdown'));
    }
    public function store(
        Store{ModuleTemplate}Request $request,
    ) {
        if($this->{moduleTemplate}Service->create($request, $this->language)) {
            return 
                redirect()
                ->route('{moduleView}.index')
                ->with('success', 'Thêm mới nhóm bài viết thành công');
        }
        return 
            redirect()
            ->route('{moduleView}.index')
            ->with('error', 'Thêm mới nhóm bài viết thất bại');
    }
    public function edit($id) {
        $this->authorize('modules', '{moduleView}.update');
        ${moduleTemplate} = $this->{moduleTemplate}Repository->get{ModuleTemplate}ById($id, $this->language);
        $config = $this->configData();
        $config['seo'] = __('messages.{moduleTemplate}');
        $config['method'] = 'edit';
        $dropdown = $this->nestedset->Dropdown();
        $album = json_decode(${moduleTemplate}->album);
        $template = 'backend.{moduleView}.store';
        return 
            view('backend.dashboard.layout',
            compact(
                'template', 
                'config', 
                'dropdown', 
                '{moduleTemplate}', 
                'album'
            ));
    }
    public function update($id, Update{ModuleTemplate}Request $request) {
        if($this->{moduleTemplate}Service->update($id, $request, $this->language)) {
            return 
                redirect()
                ->route('{moduleView}.index')
                ->with('success', 'Cập nhật nhóm bài viết thành công');
        }
        return 
            redirect()
            ->route('{moduleView}.index')
            ->with('error', 'Cập nhật nhóm bài viết thất bại');
    }
    public function delete($id, Delete{ModuleTemplate}Request $request) {
        $this->authorize('modules', '{moduleView}.destroy');
        ${moduleTemplate} = $this->{moduleTemplate}Repository->get{ModuleTemplate}ById($id, $this->language);
        $config['method'] = 'delete';
        $config['seo'] = __('messages.{moduleTemplate}');
        $template = 'backend.{moduleView}.delete';
        return
            view('backend.dashboard.layout',
            compact('template', 'config', '{moduleTemplate}'));
    }
    public function destroy($id, Delete{ModuleTemplate}Request $request) {
        if($this->{moduleTemplate}Service->destroy($id)) {
            return 
                redirect()
                ->route('{moduleView}.index')
                ->with('success', 'Xoá nhóm thành viên thành công');
        }
        return 
            redirect()
            ->route('{moduleView}.index')
            ->with('error', 'Xoá nhóm thành viên thất bại. Hãy thử lại');
    }
    private function initialize() {
        $this->nestedset = new Nestedsetbie([
            'table' => '{tableName}',
            'foreignkey' => '{foreignKey}',
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
