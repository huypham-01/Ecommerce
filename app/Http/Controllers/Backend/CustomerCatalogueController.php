<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Interfaces\CustomerCatalogueServiceInterface as CustomerCatalogueService;
use App\Repositories\Interfaces\CustomerCatalogueRepositoryInterface as CustomerCatalogueRepository;
use App\Http\Requests\Customer\StoreCustomerCatalogueRequest;

class CustomerCatalogueController extends Controller
{
    //
    protected $customerCatalogueService;
    protected $customerCatalogueRepository;
    public function __construct(
        CustomerCatalogueService $customerCatalogueService,
        CustomerCatalogueRepository $customerCatalogueRepository,
    ) {
        $this->customerCatalogueService = $customerCatalogueService;
        $this->customerCatalogueRepository = $customerCatalogueRepository;
    }
    public function index(Request $request) { 
        $this->authorize('modules', 'customer.catalogue.index');
        $customercatalogues = $this->customerCatalogueService->paginate($request);
        $config = [
            'js' => [
                'backend/js/plugins/switchery/switchery.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
            ],
            'css' => [
                'backend/css/plugins/switchery/switchery.css',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
            ],
            'model' => 'CustomerCatalogue',
        ];
        $config['seo'] = __('messages.customerCatalogue');
        $template = 'backend.customer.catalogue.index';
        return
            view('backend.dashboard.layout',
            compact('template', 'config', 'customercatalogues'));
    }
    public function create() {
        $this->authorize('modules', 'customer.catalogue.create');
        $config['seo'] = __('messages.customerCatalogue');
        $config['method'] = 'create';
        $template = 'backend.customer.catalogue.store';
        return
            view('backend.dashboard.layout',
            compact('template', 'config'));
    }
    public function store(
        StoreCustomerCatalogueRequest $request,
    ) {
        if($this->customerCatalogueService->create($request)) {
            return 
                redirect()
                ->route('customer.catalogue.index')
                ->with('success', 'Thêm mới nhóm thành viên thành công');
        }
        return 
            redirect()
            ->route('customer.catalogue.index')
            ->with('error', 'Thêm mới nhóm thành viên thất bại');
    }
    public function edit($id) {
        $this->authorize('modules', 'customer.catalogue.update');
        $customerCatalogue = $this->customerCatalogueRepository->findById($id);
        $config = [
            'js' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
                'backend/library/location.js'
                
            ],
            'css' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
                
            ]
        ];
        $config['seo'] = __('messages.customerCatalogue');
        $config['method'] = 'edit';
        $template = 'backend.customer.catalogue.store';
        return 
            view('backend.dashboard.layout',
            compact('template', 'config','customerCatalogue'));
    }
    public function update($id, storeCustomerCatalogueRequest $request) {
        if($this->customerCatalogueService->update($id, $request)) {
            return 
                redirect()
                ->route('customer.catalogue.index')
                ->with('success', 'Cập nhật thành viên thành công');
        }
        return 
            redirect()
            ->route('customer.catalogue.index')
            ->with('error', 'Cập nhật thành viên thất bại');
    }
    public function delete($id) {
        $this->authorize('modules', 'customer.catalogue.destroy');
        $customercatalogue = $this->customerCatalogueRepository->findById($id);
        $config['seo'] = __('messages.customerCatalogue');
        $template = 'backend.customer.catalogue.delete';
        return
            view('backend.dashboard.layout',
            compact('template', 'config', 'customercatalogue'));
    }
    public function destroy($id) {
        if($this->customerCatalogueRepository->delete($id)) {
            return 
                redirect()
                ->route('customer.catalogue.index')
                ->with('success', 'Xoá nhóm thành viên thành công');
        }
        return 
            redirect()
            ->route('customer.catalogue.index')
            ->with('error', 'Xoá nhóm thành viên thất bại. Hãy thử lại');
    }
}
