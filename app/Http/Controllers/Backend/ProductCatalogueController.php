<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Interfaces\ProductCatalogueServiceInterface as ProductCatalogueService;
use App\Repositories\Interfaces\ProductCatalogueRepositoryInterface as ProductCatalogueRepository;
use App\Http\Requests\StoreProductCatalogueRequest;
use App\Http\Requests\UpdateProductCatalogueRequest;
use App\Http\Requests\DeleteProductCatalogueRequest;
use App\Classes\Nestedsetbie;
use App\Models\Language;

class ProductCatalogueController extends Controller
{
    //
    protected $productCatalogueService;
    protected $productCatalogueRepository;
    protected $nestedset;
    protected $language;
    public function __construct(
        ProductCatalogueService $productCatalogueService,
        ProductCatalogueRepository $productCatalogueRepository
    ) {
        $this->middleware(function($request, $next) {
            $locale = app()->getLocale();
            $language = Language::where('canonical', $locale)->first();
            $this->language = $language->id;
            $this->initialize();
            return $next($request);
        });
        $this->productCatalogueService = $productCatalogueService;
        $this->productCatalogueRepository = $productCatalogueRepository;
    }
    public function index(Request $request) {
        $this->authorize('modules', 'product.catalogue.index');
        $productCatalogues = $this->productCatalogueService->paginate($request);
        $config = $this->configData();
        $config['seo'] = __('messages.productCatalogue');
        $config['model'] = 'ProductCatalogue';
        $template = 'backend.product.catalogue.index';
        return
            view('backend.dashboard.layout',
            compact('template', 'config', 'productCatalogues'));
    }
    public function create() {
        $this->authorize('modules', 'product.catalogue.create');
        $config = $this->configData();
        $config['seo'] = __('messages.productCatalogue');
        $config['method'] = 'create';
        $dropdown = $this->nestedset->Dropdown();
        $template = 'backend.product.catalogue.store';
        return
            view('backend.dashboard.layout',
            compact('template', 'config', 'dropdown'));
    }
    public function store(
        StoreProductCatalogueRequest $request,
    ) {
        if($this->productCatalogueService->create($request, $this->language)) {
            return 
                redirect()
                ->route('product.catalogue.index')
                ->with('success', 'Thêm mới nhóm bài viết thành công');
        }
        return 
            redirect()
            ->route('product.catalogue.index')
            ->with('error', 'Thêm mới nhóm bài viết thất bại');
    }
    public function edit($id) {
        $this->authorize('modules', 'product.catalogue.update');
        $productCatalogue = $this->productCatalogueRepository->getProductCatalogueById($id, $this->language);
        $config = $this->configData();
        $config['seo'] = __('messages.productCatalogue');
        $config['method'] = 'edit';
        $dropdown = $this->nestedset->Dropdown();
        $album = json_decode($productCatalogue->album);
        $template = 'backend.product.catalogue.store';
        return 
            view('backend.dashboard.layout',
            compact(
                'template', 
                'config', 
                'dropdown', 
                'productCatalogue', 
                'album'
            ));
    }
    public function update($id, UpdateProductCatalogueRequest $request) {
        if($this->productCatalogueService->update($id, $request, $this->language)) {
            return 
                redirect()
                ->route('product.catalogue.index')
                ->with('success', 'Cập nhật nhóm bài viết thành công');
        }
        return 
            redirect()
            ->route('product.catalogue.index')
            ->with('error', 'Cập nhật nhóm bài viết thất bại');
    }
    public function delete($id, DeleteProductCatalogueRequest $request) {
        $this->authorize('modules', 'product.catalogue.destroy');
        $productCatalogue = $this->productCatalogueRepository->getProductCatalogueById($id, $this->language);
        $config['method'] = 'delete';
        $config['seo'] = __('messages.productCatalogue');
        $template = 'backend.product.catalogue.delete';
        return
            view('backend.dashboard.layout',
            compact('template', 'config', 'productCatalogue'));
    }
    public function destroy($id, DeleteProductCatalogueRequest $request) {
        if($this->productCatalogueService->destroy($id)) {
            return 
                redirect()
                ->route('product.catalogue.index')
                ->with('success', 'Xoá nhóm thành viên thành công');
        }
        return 
            redirect()
            ->route('product.catalogue.index')
            ->with('error', 'Xoá nhóm thành viên thất bại. Hãy thử lại');
    }
    private function initialize() {
        $this->nestedset = new Nestedsetbie([
            'table' => 'product_catalogues',
            'foreignkey' => 'product_catalogue_id',
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
