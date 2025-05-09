<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Interfaces\UserCatalogueServiceInterface as UserCatalogueService;
use App\Repositories\Interfaces\UserCatalogueRepositoryInterface as UserCatalogueRepository;
use App\Repositories\Interfaces\PermissionRepositoryInterface as PermissionRepository;
use App\Http\Requests\storeUserCatalogueRequest;

class UserCatalogueController extends Controller
{
    //
    protected $userCatalogueService;
    protected $userCatalogueRepository;
    protected $permissionRepository;
    public function __construct(
        UserCatalogueService $userCatalogueService,
        UserCatalogueRepository $userCatalogueRepository,
        PermissionRepository $permissionRepository
    ) {
        $this->userCatalogueService = $userCatalogueService;
        $this->userCatalogueRepository = $userCatalogueRepository;
        $this->permissionRepository = $permissionRepository;
    }
    public function index(Request $request) { 
        $this->authorize('modules', 'user.catalogue.index');
        $usercatalogues = $this->userCatalogueService->paginate($request);
        $config = [
            'js' => [
                'backend/js/plugins/switchery/switchery.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
            ],
            'css' => [
                'backend/css/plugins/switchery/switchery.css',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
            ],
            'model' => 'UserCatalogue',
        ];
        $config['seo'] = config('apps.userCatalogue');
        $template = 'backend.user.catalogue.index';
        return
            view('backend.dashboard.layout',
            compact('template', 'config', 'usercatalogues'));
    }
    public function create() {
        $this->authorize('modules', 'user.catalogue.create');
        $config['seo'] = config('apps.userCatalogue');
        $config['method'] = 'create';
        $template = 'backend.user.catalogue.store';
        return
            view('backend.dashboard.layout',
            compact('template', 'config'));
    }
    public function store(
        storeUserCatalogueRequest $request,
    ) {
        if($this->userCatalogueService->create($request)) {
            return 
                redirect()
                ->route('user.catalogue.index')
                ->with('success', 'Thêm mới nhóm thành viên thành công');
        }
        return 
            redirect()
            ->route('user.catalogue.index')
            ->with('error', 'Thêm mới nhóm thành viên thất bại');
    }
    public function edit($id) {
        $this->authorize('modules', 'user.catalogue.update');
        $userCatalogue = $this->userCatalogueRepository->findById($id);
        $config = [
            'js' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
                'backend/library/location.js'
                
            ],
            'css' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
                
            ]
        ];
        $config['seo'] = config('apps.userCatalogue');
        $config['method'] = 'edit';
        $template = 'backend.user.catalogue.store';
        return 
            view('backend.dashboard.layout',
            compact('template', 'config','userCatalogue'));
    }
    public function update($id, storeUserCatalogueRequest $request) {
        if($this->userCatalogueService->update($id, $request)) {
            return 
                redirect()
                ->route('user.catalogue.index')
                ->with('success', 'Cập nhật thành viên thành công');
        }
        return 
            redirect()
            ->route('user.catalogue.index')
            ->with('error', 'Cập nhật thành viên thất bại');
    }
    public function delete($id) {
        $this->authorize('modules', 'user.catalogue.destroy');
        $usercatalogue = $this->userCatalogueRepository->findById($id);
        $config['seo'] = config('apps.userCatalogue');
        $template = 'backend.user.catalogue.delete';
        return
            view('backend.dashboard.layout',
            compact('template', 'config', 'usercatalogue'));
    }
    public function destroy($id) {
        if($this->userCatalogueRepository->delete($id)) {
            return 
                redirect()
                ->route('user.catalogue.index')
                ->with('success', 'Xoá nhóm thành viên thành công');
        }
        return 
            redirect()
            ->route('user.catalogue.index')
            ->with('error', 'Xoá nhóm thành viên thất bại. Hãy thử lại');
    }
    public function permission() {
        $userCatalogues = $this->userCatalogueRepository->all(['permissions']);
        $permissions = $this->permissionRepository->all();
        $config['seo'] = __('messages.permission');
        $template = 'backend.user.catalogue.permission';
        return
            view('backend.dashboard.layout',
            compact('template', 'config', 'permissions','userCatalogues'));
    }
    public function updatePermission(Request $request) {
        if($this->userCatalogueService->setPermission($request)) {
            return redirect()->route('user.catalogue.index')->with('success', 'Cập nhật quyền thành công');
        }
        return redirect()->route('user.catalogue.index')->with('error', 'Có lỗi xảy ra, Vui lòng thử lại');
    }
}
