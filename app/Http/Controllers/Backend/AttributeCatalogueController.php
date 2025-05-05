<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Interfaces\AttributeCatalogueServiceInterface as AttributeCatalogueService;
use App\Repositories\Interfaces\AttributeCatalogueRepositoryInterface as AttributeCatalogueRepository;
use App\Http\Requests\StoreAttributeCatalogueRequest;
use App\Http\Requests\UpdateAttributeCatalogueRequest;
use App\Http\Requests\DeleteAttributeCatalogueRequest;
use App\Classes\Nestedsetbie;
use App\Models\Language;

class AttributeCatalogueController extends Controller
{
    //
    protected $attributeCatalogueService;
    protected $attributeCatalogueRepository;
    protected $nestedset;
    protected $language;
    public function __construct(
        AttributeCatalogueService $attributeCatalogueService,
        AttributeCatalogueRepository $attributeCatalogueRepository
    ) {
        $this->middleware(function($request, $next) {
            $locale = app()->getLocale();
            $language = Language::where('canonical', $locale)->first();
            $this->language = $language->id;
            $this->initialize();
            return $next($request);
        });
        $this->attributeCatalogueService = $attributeCatalogueService;
        $this->attributeCatalogueRepository = $attributeCatalogueRepository;
    }
    public function index(Request $request) {
        $this->authorize('modules', 'attribute.catalogue.index');
        $attributeCatalogues = $this->attributeCatalogueService->paginate($request);
        $config = $this->configData();
        $config['seo'] = __('messages.attributeCatalogue');
        $config['model'] = 'AttributeCatalogue';
        $template = 'backend.attribute.catalogue.index';
        return
            view('backend.dashboard.layout',
            compact('template', 'config', 'attributeCatalogues'));
    }
    public function create() {
        $this->authorize('modules', 'attribute.catalogue.create');
        $config = $this->configData();
        $config['seo'] = __('messages.attributeCatalogue');
        $config['method'] = 'create';
        $dropdown = $this->nestedset->Dropdown();
        $template = 'backend.attribute.catalogue.store';
        return
            view('backend.dashboard.layout',
            compact('template', 'config', 'dropdown'));
    }
    public function store(
        StoreAttributeCatalogueRequest $request,
    ) {
        if($this->attributeCatalogueService->create($request, $this->language)) {
            return 
                redirect()
                ->route('attribute.catalogue.index')
                ->with('success', 'Thêm mới nhóm bài viết thành công');
        }
        return 
            redirect()
            ->route('attribute.catalogue.index')
            ->with('error', 'Thêm mới nhóm bài viết thất bại');
    }
    public function edit($id) {
        $this->authorize('modules', 'attribute.catalogue.update');
        $attributeCatalogue = $this->attributeCatalogueRepository->getAttributeCatalogueById($id, $this->language);
        $config = $this->configData();
        $config['seo'] = __('messages.attributeCatalogue');
        $config['method'] = 'edit';
        $dropdown = $this->nestedset->Dropdown();
        $album = json_decode($attributeCatalogue->album);
        $template = 'backend.attribute.catalogue.store';
        return 
            view('backend.dashboard.layout',
            compact(
                'template', 
                'config', 
                'dropdown', 
                'attributeCatalogue', 
                'album'
            ));
    }
    public function update($id, UpdateAttributeCatalogueRequest $request) {
        if($this->attributeCatalogueService->update($id, $request, $this->language)) {
            return 
                redirect()
                ->route('attribute.catalogue.index')
                ->with('success', 'Cập nhật nhóm bài viết thành công');
        }
        return 
            redirect()
            ->route('attribute.catalogue.index')
            ->with('error', 'Cập nhật nhóm bài viết thất bại');
    }
    public function delete($id, DeleteAttributeCatalogueRequest $request) {
        $this->authorize('modules', 'attribute.catalogue.destroy');
        $attributeCatalogue = $this->attributeCatalogueRepository->getAttributeCatalogueById($id, $this->language);
        $config['method'] = 'delete';
        $config['seo'] = __('messages.attributeCatalogue');
        $template = 'backend.attribute.catalogue.delete';
        return
            view('backend.dashboard.layout',
            compact('template', 'config', 'attributeCatalogue'));
    }
    public function destroy($id, DeleteAttributeCatalogueRequest $request) {
        if($this->attributeCatalogueService->destroy($id)) {
            return 
                redirect()
                ->route('attribute.catalogue.index')
                ->with('success', 'Xoá nhóm thành viên thành công');
        }
        return 
            redirect()
            ->route('attribute.catalogue.index')
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
