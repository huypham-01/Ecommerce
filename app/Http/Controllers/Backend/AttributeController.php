<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Interfaces\AttributeServiceInterface as AttributeService;
use App\Repositories\Interfaces\AttributeRepositoryInterface as AttributeRepository;
use App\Http\Requests\StoreAttributeRequest;
use App\Http\Requests\UpdateAttributeRequest;
use App\Http\Requests\DeleteAttributeCatalogueRequest;
use App\Classes\Nestedsetbie;
use App\Models\Language;

class AttributeController extends Controller
{
    //
    protected $attributeService;
    protected $attributeRepository;
    protected $nestedset;
    protected $language;
    public function __construct(
        attributeService $attributeService,
        attributeRepository $attributeRepository
    ) {
        $this->middleware(function($request, $next) {
            $locale = app()->getLocale();
            $language = Language::where('canonical', $locale)->first();
            $this->language = $language->id;
            $this->initialize();
            return $next($request);
        });

        $this->attributeService = $attributeService;
        $this->attributeRepository = $attributeRepository;
        $this->nestedset = new Nestedsetbie([
            'table' => 'attribute_catalogues',
            'foreignkey' => 'attribute_catalogue_id',
            'language_id' => $this->language,
        ]);
        $this->language = $this->currentLanguage();
    }
    public function index(Request $request) {
        $this->authorize('modules', 'attribute.index');
        $attributes = $this->attributeService->paginate($request, $this->language);
        $config = $this->configData();
        $config['seo'] = __('messages.attribute');
        $config['model'] = 'Attribute';
        $dropdown = $this->nestedset->Dropdown();
        $template = 'backend.attribute.attribute.index';
        return
            view('backend.dashboard.layout',
            compact('template', 'config', 'attributes', 'dropdown'));
    }
    public function create() {
        $this->authorize('modules', 'attribute.create');
        $config = $this->configData();
        $config['seo'] = __('messages.attribute');
        $config['method'] = 'create';
        $config['model'] = 'Attribute';
        $dropdown = $this->nestedset->Dropdown();
        $template = 'backend.attribute.attribute.store';
        return
            view('backend.dashboard.layout',
            compact('template', 'config', 'dropdown'));
    }
    public function store(
        StoreAttributeRequest $request,
    ) {
        if($this->attributeService->create($request, $this->language)) {
            return 
                redirect()
                ->route('attribute.index')
                ->with('success', 'Thêm mới bài viết thành công');
        }
        return 
            redirect()
            ->route('attribute.index')
            ->with('error', 'Thêm mới bài viết thất bại');
    }
    public function edit($id) {
        $this->authorize('modules', 'attribute.update');
        $attribute = $this->attributeRepository->getAttributeById($id, $this->language);
        $config = $this->configData();
        $config['seo'] = __('messages.attribute');
        $config['method'] = 'edit';
        $dropdown = $this->nestedset->Dropdown();
        $album = json_decode($attribute->album);
        $template = 'backend.attribute.attribute.store';
        return 
            view('backend.dashboard.layout',
            compact(
                'template', 
                'config', 
                'dropdown', 
                'attribute', 
                'album'
            ));
    }
    public function update($id, UpdateAttributeRequest $request) {
        if($this->attributeService->update($id, $request, $this->language)) {
            return 
                redirect()
                ->route('attribute.index')
                ->with('success', 'Cập nhật bài viết thành công');
        }
        return 
            redirect()
            ->route('attribute.index')
            ->with('error', 'Cập nhật bài viết thất bại');
    }
    public function delete($id) {
        $this->authorize('modules', 'attribute.catalogue.destroy');
        $attribute = $this->attributeRepository->getAttributeById($id, $this->language);
        $config['method'] = 'delete';
        $config['seo'] = __('messages.attribute');
        $template = 'backend.attribute.attribute.delete';
        return
            view('backend.dashboard.layout',
            compact('template', 'config', 'attribute'));
    }
    public function destroy($id) {
        if($this->attributeService->destroy($id)) {
            return 
                redirect()
                ->route('attribute.index')
                ->with('success', 'Xoá nhóm thành viên thành công');
        }
        return 
            redirect()
            ->route('attribute.index')
            ->with('error', 'Xoá nhóm thành viên thất bại. Hãy thử lại');
    }
    private function initialize() {
        $this->nestedset = new Nestedsetbie([
            'table' => 'attribute_catalogues',
            'foreignkey' => 'attribute_catalogue_id',
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
