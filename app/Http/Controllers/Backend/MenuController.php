<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Interfaces\MenuServiceInterface as MenuService;
use App\Services\Interfaces\MenuCatalogueServiceInterface as MenuCatalogueService;
use App\Repositories\Interfaces\MenuCatalogueRepositoryInterface as MenuCatalogueRepository;
use App\Repositories\Interfaces\MenuRepositoryInterface as MenuRepository;
use App\Models\Language;
use App\Http\Requests\StoreMenuRequest;
use App\Http\Requests\UpdateMenuRequest;
use App\Http\Requests\storeMenuChildrenRequest;

class MenuController extends Controller
{
    //
    protected $menuService;
    protected $menuCatalogueService;
    protected $menuRepository;
    protected $menuCatalogueRepository;
    public function __construct(
        MenuService $menuService,
        MenuCatalogueService $menuCatalogueService,
        MenuRepository $menuRepository,
        MenuCatalogueRepository $menuCatalogueRepository
    ) {
        $this->menuService = $menuService;
        $this->menuCatalogueService = $menuCatalogueService;
        $this->menuRepository = $menuRepository;
        $this->menuCatalogueRepository = $menuCatalogueRepository;

        $this->middleware(function($request, $next) {
            $locale = app()->getLocale();
            $language = Language::where('canonical', $locale)->first();
            $this->language = $language->id;
            return $next($request);
        });
    }
    
    public function index(Request $request) {
        $this->authorize('modules', 'menu.index');
        $menuCatalogues = $this->menuCatalogueService->paginate($request, $this->language);
        $config = [
            'js' => [
                'backend/js/plugins/switchery/switchery.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
            ],
            'css' => [
                'backend/css/plugins/switchery/switchery.css',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
            ],
            'model' => 'MenuCatalogue',
        ];
        $config['seo'] = __('messages.menu');
        $template = 'backend.menu.menu.index';
        return
            view('backend.dashboard.layout',
            compact('template', 'config', 'menuCatalogues'));
    }
    public function create() {
        $this->authorize('modules', 'menu.create');
        $menuCatalogues = $this->menuCatalogueRepository->all();
        $config = $this->config();
        $config['seo'] = __('messages.menu');
        $config['method'] = 'create';
        $template = 'backend.menu.menu.store';
        return
            view('backend.dashboard.layout',
            compact('template', 'config', 'menuCatalogues'));
    }
    public function store(StoreMenuRequest $request) {
        if($this->menuService->save($request, $this->language)) {
            $menuCatalogueId = $request->input('menu_catalogue_id');
            return redirect()->route('menu.edit', ['id' => $menuCatalogueId])->with('success', 'Cập nhật menu thành công');
        }
        return 
            redirect()->route('menu.index')->with('error', 'Cập nhật Menu thất bại');
    }
    public function edit($id) {
        $this->authorize('modules', 'menu.update');
        $language = $this->language;
        $menus = $this->menuRepository->findByCondition([
            ['menu_catalogue_id', '=', $id]
        ], TRUE, [
            'languages' => function ($query) use ($language) {
                $query->where('language_id', $language);
            },
        ], ['order', 'DESC']);
        $menuCatalogue = $this->menuCatalogueRepository->findById($id);
        $config = $this->config();
        $config['seo'] = __('messages.menu');
        $config['method'] = 'edit';
        $template = 'backend.menu.menu.show';
        return 
            view('backend.dashboard.layout',
            compact('template', 'config', 'menus', 'id', 'menuCatalogue'));
    }
    public function editMenu($id) {
        $this->authorize('modules', 'menu.update');
        $language = $this->language;
        $menus = $this->menuRepository->findByCondition([
            ['menu_catalogue_id', '=', $id],
            ['parent_id', '=', 0]
        ], TRUE, [
            'languages' => function ($query) use ($language) {
                $query->where('language_id', $language);
            },
        ], ['order', 'DESC']);
        $menuList = $this->menuService->convertMenu($menus, $language);
        $menuCatalogues = $this->menuCatalogueRepository->all();
        $menuCatalogue = $this->menuCatalogueRepository->findById($id);
        $config = $this->config();
        $config['seo'] = __('messages.menu');
        $config['method'] = 'update';
        $template = 'backend.menu.menu.store';
        return
            view('backend.dashboard.layout',
            compact(
                'template', 
                'config', 
                'menuList', 
                'menuCatalogues', 
                'menuCatalogue',
                'id',
            ));
    }
    public function update($id, UpdateMenuRequest $request) {
        if($this->menuService->save($id, $request)) {
            return 
                redirect()
                ->route('menu.index')
                ->with('success', 'Cập nhật thành viên thành công');
        }
        return 
            redirect()
            ->route('menu.index')
            ->with('error', 'Cập nhật thành viên thất bại');
    }
    public function delete($id) {
        $this->authorize('modules', 'menu.destroy');
        $menuCatalogue = $this->menuCatalogueRepository->findById($id);
        $config['seo'] = __('messages.menu');
        $template = 'backend.menu.menu.delete';
        return
            view('backend.dashboard.layout',
            compact('template', 'config', 'menuCatalogue'));
    }
    public function destroy($id) {
        if($this->menuService->destroy($id)) {
            return 
                redirect()
                ->route('menu.index')
                ->with('success', 'Xoá thành viên thành công');
        }
        return 
            redirect()
            ->route('menu.index')
            ->with('error', 'Xoá thành viên thất bại. Hãy thử lại');
    }

    public function children($id) {
        $this->authorize('modules', 'menu.create');
        $language = $this->language;
        $menu = $this->menuRepository->findById($id, ['*'], [
            'languages' => function ($query) use ($language) {
                $query->where('language_id', $language);
            }
        ]);
      
        $menuList = $this->menuService->getAndConvertMenu($menu, $language);
        $config = $this->config();
        $config['seo'] = __('messages.menu');
        $config['method'] = 'children';
        $template = 'backend.menu.menu.children';
        return
            view('backend.dashboard.layout',
            compact('template', 'config','menu', 'menuList'));
    }
    public function saveChildren(storeMenuChildrenRequest $request, $id) {
        $menu = $this->menuRepository->findById($id);
        if($this->menuService->saveChildren($request, $this->language, $menu)) {
            return 
                redirect()
                ->route('menu.edit', ['id' => $menu->menu_catalogue_id])
                ->with('success', 'Cập nhật thành viên thành công');
        }
        return 
            redirect()
            ->route('menu.edit', ['id' => $menu->menu_catalogue_id])
            ->with('error', 'Cập nhật thành viên thất bại');
    }
    private function config() {
        return [
            'js' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
                'backend/library/menu.js',
                'backend/js/plugins/nestable/jquery.nestable.js',
                
            ],
            'css' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
                
            ]
        ];
    }
}
