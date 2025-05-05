<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Interfaces\ProductServiceInterface as ProductService;
use App\Repositories\Interfaces\ProductRepositoryInterface as ProductRepository;
use App\Repositories\Interfaces\AttributeCatalogueRepositoryInterface as AttributeCatalogueRepository;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Requests\DeleteProductCatalogueRequest;
use App\Classes\Nestedsetbie;
use App\Models\Language;

class ProductController extends Controller
{
    //
    protected $productService;
    protected $productRepository;
    protected $nestedset;
    protected $language;
    protected $attributeCatalogue;
    public function __construct(
        ProductService $productService,
        ProductRepository $productRepository,
        AttributeCatalogueRepository $attributeCatalogue
    ) {
        $this->middleware(function($request, $next) {
            $locale = app()->getLocale();
            $language = Language::where('canonical', $locale)->first();
            $this->language = $language->id;
            $this->initialize();
            return $next($request);
        });
        $this->productService = $productService;
        $this->attributeCatalogue = $attributeCatalogue;
        $this->productRepository = $productRepository;
        $this->nestedset = new Nestedsetbie([
            'table' => 'product_catalogues',
            'foreignkey' => 'product_catalogue_id',
            'language_id' => $this->language,
        ]);
        $this->language = $this->currentLanguage();
    }
    public function index(Request $request) {
        $this->authorize('modules', 'product.index');
        $products = $this->productService->paginate($request, $this->language);
        $config = $this->configData();
        $config['seo'] = __('messages.product');
        $config['model'] = 'Product';
        $dropdown = $this->nestedset->Dropdown();
        $template = 'backend.product.product.index';
        return
            view('backend.dashboard.layout',
            compact('template', 'config', 'products', 'dropdown'));
    }
    public function create() {
        $this->authorize('modules', 'product.create');
        $attributeCatalogue = $this->attributeCatalogue->getAll($this->language);
        $config = $this->configData();
        $config['seo'] = __('messages.product');
        $config['method'] = 'create';
        $config['model'] = 'Product';
        $dropdown = $this->nestedset->Dropdown();
        $template = 'backend.product.product.store';
        return
            view('backend.dashboard.layout',
            compact('attributeCatalogue','template', 'config', 'dropdown'));
    }
    public function store(
        StoreProductRequest $request,
    ) {
        if($this->productService->create($request, $this->language)) {
            return 
                redirect()
                ->route('product.index')
                ->with('success', 'Thêm mới sản phẩm thành công');
        }
        return 
            redirect()
            ->route('product.index')
            ->with('error', 'Thêm mới sản phẩm thất bại');
    }
    public function edit($id) {
        $this->authorize('modules', 'product.update');
        $product = $this->productRepository->getProductById($id, $this->language);
        $attributeCatalogue = $this->attributeCatalogue->getAll($this->language);
        $config = $this->configData();
        $config['seo'] = __('messages.product');
        $config['method'] = 'edit';
        $dropdown = $this->nestedset->Dropdown();
        $album = json_decode($product->album);
        $template = 'backend.product.product.store';
        return 
            view('backend.dashboard.layout',
            compact(
                'template', 
                'config', 
                'dropdown', 
                'product', 
                'album',
                'attributeCatalogue',
            ));
    }
    public function update($id, UpdateProductRequest $request) {
        if($this->productService->update($id, $request, $this->language)) {
            return 
                redirect()
                ->route('product.index')
                ->with('success', 'Cập nhật sản phẩm thành công');
        }
        return 
            redirect()
            ->route('product.index')
            ->with('error', 'Cập nhật sản phẩm thất bại');
    }
    public function delete($id) {
        $this->authorize('modules', 'product.catalogue.destroy');
        $product = $this->productRepository->getProductById($id, $this->language);
        $config['method'] = 'delete';
        $config['seo'] = __('messages.product');
        $template = 'backend.product.product.delete';
        return
            view('backend.dashboard.layout',
            compact('template', 'config', 'product'));
    }
    public function destroy($id) {
        if($this->productService->destroy($id)) {
            return 
                redirect()
                ->route('product.index')
                ->with('success', 'Xoá nhóm thành viên thành công');
        }
        return 
            redirect()
            ->route('product.index')
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
                'backend/library/variant.js',
                'backend/library/location.js',
                'backend/js/plugins/switchery/switchery.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
                'backend/plugin/nice-select/js/jquery.nice-select.min.js',
                
            ],
            'css' => [
                'backend/css/plugins/switchery/switchery.css',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
                'backend/plugin/nice-select/css/nice-select.css',

                
            ]
        ];
    }
}
