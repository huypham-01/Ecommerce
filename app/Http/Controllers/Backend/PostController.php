<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Interfaces\PostServiceInterface as PostService;
use App\Repositories\Interfaces\PostRepositoryInterface as PostRepository;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Requests\DeletePostCatalogueRequest;
use App\Classes\Nestedsetbie;
use App\Models\Language;

class PostController extends Controller
{
    //
    protected $postService;
    protected $postRepository;
    protected $nestedset;
    protected $language;
    public function __construct(
        postService $postService,
        postRepository $postRepository
    ) {
        $this->middleware(function($request, $next) {
            $locale = app()->getLocale();
            $language = Language::where('canonical', $locale)->first();
            $this->language = $language->id;
            $this->initialize();
            return $next($request);
        });

        $this->postService = $postService;
        $this->postRepository = $postRepository;
        $this->nestedset = new Nestedsetbie([
            'table' => 'post_catalogues',
            'foreignkey' => 'post_catalogue_id',
            'language_id' => $this->language,
        ]);
        $this->language = $this->currentLanguage();
    }
    public function index(Request $request) {
        $this->authorize('modules', 'post.index');
        $posts = $this->postService->paginate($request, $this->language);
        $config = $this->configData();
        $config['seo'] = config('apps.post');
        $config['model'] = 'Post';
        $dropdown = $this->nestedset->Dropdown();
        $template = 'backend.post.post.index';
        return
            view('backend.dashboard.layout',
            compact('template', 'config', 'posts', 'dropdown'));
    }
    public function create() {
        $this->authorize('modules', 'post.create');
        $config = $this->configData();
        $config['seo'] = config('apps.post');
        $config['method'] = 'create';
        $config['model'] = 'Post';
        $dropdown = $this->nestedset->Dropdown();
        $template = 'backend.post.post.store';
        return
            view('backend.dashboard.layout',
            compact('template', 'config', 'dropdown'));
    }
    public function store(
        StorePostRequest $request,
    ) {
        if($this->postService->create($request, $this->language)) {
            return 
                redirect()
                ->route('post.index')
                ->with('success', 'Thêm mới bài viết thành công');
        }
        return 
            redirect()
            ->route('post.index')
            ->with('error', 'Thêm mới bài viết thất bại');
    }
    public function edit($id) {
        $this->authorize('modules', 'post.update');
        $post = $this->postRepository->getPostById($id, $this->language);
        $config = $this->configData();
        $config['seo'] = config('apps.post');
        $config['method'] = 'edit';
        $dropdown = $this->nestedset->Dropdown();
        $album = json_decode($post->album);
        $template = 'backend.post.post.store';
        return 
            view('backend.dashboard.layout',
            compact(
                'template', 
                'config', 
                'dropdown', 
                'post', 
                'album'
            ));
    }
    public function update($id, UpdatePostRequest $request) {
        if($this->postService->update($id, $request, $this->language)) {
            return 
                redirect()
                ->route('post.index')
                ->with('success', 'Cập nhật bài viết thành công');
        }
        return 
            redirect()
            ->route('post.index')
            ->with('error', 'Cập nhật bài viết thất bại');
    }
    public function delete($id) {
        $this->authorize('modules', 'post.catalogue.destroy');
        $post = $this->postRepository->getPostById($id, $this->language);
        $config['method'] = 'delete';
        $config['seo'] = config('apps.post');
        $template = 'backend.post.post.delete';
        return
            view('backend.dashboard.layout',
            compact('template', 'config', 'post'));
    }
    public function destroy($id) {
        if($this->postService->destroy($id)) {
            return 
                redirect()
                ->route('post.index')
                ->with('success', 'Xoá nhóm thành viên thành công');
        }
        return 
            redirect()
            ->route('post.index')
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
