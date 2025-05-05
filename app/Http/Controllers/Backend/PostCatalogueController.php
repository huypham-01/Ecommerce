<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Interfaces\PostCatalogueServiceInterface as PostCatalogueService;
use App\Repositories\Interfaces\PostCatalogueRepositoryInterface as PostCatalogueRepository;
use App\Http\Requests\StorePostCatalogueRequest;
use App\Http\Requests\UpdatePostCatalogueRequest;
use App\Http\Requests\DeletePostCatalogueRequest;
use App\Classes\Nestedsetbie;
use App\Models\Language;

class PostCatalogueController extends Controller
{
    //
    protected $postCatalogueService;
    protected $postCatalogueRepository;
    protected $nestedset;
    protected $language;
    public function __construct(
        postCatalogueService $postCatalogueService,
        postCatalogueRepository $postCatalogueRepository
    ) {
        $this->middleware(function($request, $next) {
            $locale = app()->getLocale();
            $language = Language::where('canonical', $locale)->first();
            $this->language = $language->id;
            $this->initialize();
            return $next($request);
        });
        $this->postCatalogueService = $postCatalogueService;
        $this->postCatalogueRepository = $postCatalogueRepository;
        $this->language = app()->getLocale();
    }
    public function index(Request $request) {
        $this->authorize('modules', 'post.catalogue.index');
        $postcatalogues = $this->postCatalogueService->paginate($request);
        $config = $this->configData();
        $config['seo'] = __('messages.postCatalogue');
        $config['model'] = 'PostCatalogue';
        $template = 'backend.post.catalogue.index';
        return
            view('backend.dashboard.layout',
            compact('template', 'config', 'postcatalogues'));
    }
    public function create() {
        $this->authorize('modules', 'post.catalogue.create');
        $config = $this->configData();
        $config['seo'] = __('messages.postCatalogue');
        $config['method'] = 'create';
        $dropdown = $this->nestedset->Dropdown();
        $template = 'backend.post.catalogue.store';
        return
            view('backend.dashboard.layout',
            compact('template', 'config', 'dropdown'));
    }
    public function store(
        StorePostCatalogueRequest $request,
    ) {
        if($this->postCatalogueService->create($request, $this->language)) {
            return 
                redirect()
                ->route('post.catalogue.index')
                ->with('success', 'Thêm mới nhóm bài viết thành công');
        }
        return 
            redirect()
            ->route('post.catalogue.index')
            ->with('error', 'Thêm mới nhóm bài viết thất bại');
    }
    public function edit($id) {
        $this->authorize('modules', 'post.catalogue.update');
        $postCatalogue = $this->postCatalogueRepository->getPostCatalogueById($id, $this->language);
        $config = $this->configData();
        $config['seo'] = __('messages.postCatalogue');
        $config['method'] = 'edit';
        $dropdown = $this->nestedset->Dropdown();
        $album = json_decode($postCatalogue->album);
        $template = 'backend.post.catalogue.store';
        return 
            view('backend.dashboard.layout',
            compact(
                'template', 
                'config', 
                'dropdown', 
                'postCatalogue', 
                'album'
            ));
    }
    public function update($id, UpdatePostCatalogueRequest $request) {
        if($this->postCatalogueService->update($id, $request, $this->language)) {
            return 
                redirect()
                ->route('post.catalogue.index')
                ->with('success', 'Cập nhật nhóm bài viết thành công');
        }
        return 
            redirect()
            ->route('post.catalogue.index')
            ->with('error', 'Cập nhật nhóm bài viết thất bại');
    }
    public function delete($id, DeletePostCatalogueRequest $request) {
        $this->authorize('modules', 'post.catalogue.destroy');
        $postCatalogue = $this->postCatalogueRepository->getPostCatalogueById($id, $this->language);
        $config['method'] = 'delete';
        $config['seo'] = config('apps.postCatalogue');
        $template = 'backend.post.catalogue.delete';
        return
            view('backend.dashboard.layout',
            compact('template', 'config', 'postCatalogue'));
    }
    public function destroy($id, DeletePostCatalogueRequest $request) {
        if($this->postCatalogueService->destroy($id)) {
            return 
                redirect()
                ->route('post.catalogue.index')
                ->with('success', 'Xoá nhóm thành viên thành công');
        }
        return 
            redirect()
            ->route('post.catalogue.index')
            ->with('error', 'Xoá nhóm thành viên thất bại. Hãy thử lại');
    }
    private function initialize() {
        $this->nestedset = new Nestedsetbie([
            'table' => 'post_catalogues',
            'foreignkey' => 'post_catalogue_id',
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
