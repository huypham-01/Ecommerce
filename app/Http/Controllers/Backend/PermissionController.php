<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Interfaces\PermissionServiceInterface as PermissionService;
use App\Repositories\Interfaces\PermissionRepositoryInterface as PermissionRepository;
use App\Http\Requests\StorePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;
use Illuminate\Support\Facades\App;

class PermissionController extends Controller
{
    //
    protected $permissionService;
    protected $permissionRepository;
    public function __construct(
        PermissionService $permissionService,
        PermissionRepository $permissionRepository
    ) {
        $this->permissionService = $permissionService;
        $this->permissionRepository = $permissionRepository;
    }
    public function index(Request $request) { 

        $permissions = $this->permissionService->paginate($request);
        $config = [
            'js' => [
                'backend/js/plugins/switchery/switchery.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
            ],
            'css' => [
                'backend/css/plugins/switchery/switchery.css',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
            ],
            'model' => 'Permission'
        ];
        $config['seo'] = __('messages.permission');
        $template = 'backend.permission.index';
        return
            view('backend.dashboard.layout',
            compact('template', 'config', 'permissions'));
    }
    public function create() {
        $this->authorize('modules', 'permission.create');
        $config = $this->configData();
        $config['seo'] = __('messages.permission');
        $config['method'] = 'create';
        $template = 'backend.permission.store';
        return
            view('backend.dashboard.layout',
            compact('template', 'config'));
    }
    public function store(
        StorePermissionRequest $request,
    ) {
        if($this->permissionService->create($request)) {
            return 
                redirect()
                ->route('permission.index')
                ->with('success', 'Thêm mới ngôn ngữ thành công');
        }
        return 
            redirect()
            ->route('permission.index')
            ->with('error', 'Thêm mới ngôn ngữ thất bại');
    }
    public function edit($id) {

        $permission = $this->permissionRepository->findById($id);
        $config = $this->configData();
        $config['seo'] = __('messages.permission');
        $config['method'] = 'edit';
        $template = 'backend.permission.store';
        return 
            view('backend.dashboard.layout',
            compact('template', 'config','permission'));
    }
    public function update($id, UpdatePermissionRequest $request) {
        if($this->permissionService->update($id, $request)) {
            return 
                redirect()
                ->route('permission.index')
                ->with('success', 'Cập nhật ngôn ngữ thành công');
        }
        return 
            redirect()
            ->route('permission.index')
            ->with('error', 'Cập nhật ngôn ngữ thất bại');
    }
    public function delete($id) {

        $permission = $this->permissionRepository->findById($id);
        $config['seo'] = __('messages.permission');
        $template = 'backend.permission.delete';
        return
            view('backend.dashboard.layout',
            compact('template', 'config', 'permission'));
    }
    public function destroy($id) {
        if($this->permissionRepository->delete($id)) {
            return 
                redirect()
                ->route('permission.index')
                ->with('success', 'Xoá ngôn ngữ thành công');
        }
        return 
            redirect()
            ->route('permission.index')
            ->with('error', 'Xoá ngôn ngữ thất bại. Hãy thử lại');
    }
    private function configData() {
        return [
            'js' => [
                'backend/plugin/ckfinder_2/ckfinder.js',
                'backend/library/finder.js',
            ],
        ];
    }
    
}
