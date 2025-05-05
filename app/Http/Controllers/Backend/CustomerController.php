<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Interfaces\CustomerServiceInterface as CustomerService;
use App\Repositories\Interfaces\ProvinceRepositoryInterface as ProvinceRepository;
use App\Repositories\Interfaces\CustomerRepositoryInterface as CustomerRepository;
use App\Repositories\Interfaces\SourceRepositoryInterface as SourceRepository;
use App\Repositories\Interfaces\CustomerCatalogueRepositoryInterface as CustomerCatalogueRepository;
use App\Http\Requests\Customer\StoreCustomerRequest;
use App\Http\Requests\Customer\UpdateCustomerRequest;

class CustomerController extends Controller
{
    //
    protected $customerService;
    protected $provinceRepository;
    protected $customerRepository;
    protected $sourceRepository;
    protected $customerCatalogueRepository;
    public function __construct(
        CustomerService $customerService,
        ProvinceRepository $provinceRepository,
        CustomerRepository $customerRepository,
        SourceRepository $sourceRepository,
        CustomerCatalogueRepository $customerCatalogueRepository
    ) {
        $this->customerService = $customerService;
        $this->provinceRepository = $provinceRepository;
        $this->customerRepository = $customerRepository;
        $this->sourceRepository = $sourceRepository;
        $this->customerCatalogueRepository = $customerCatalogueRepository;
    }
    public function index(Request $request) {
        $this->authorize('modules', 'customer.index');
        $customers = $this->customerService->paginate($request);
        $customerCatalogues = $this->customerCatalogueRepository->all();
        $config = [
            'js' => [
                'backend/js/plugins/switchery/switchery.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
            ],
            'css' => [
                'backend/css/plugins/switchery/switchery.css',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
            ],
            'model' => 'Customer'
        ];
        $config['seo'] = __('messages.customer');
        $template = 'backend.customer.customer.index';
        return
            view('backend.dashboard.layout',
            compact('template', 'config', 'customers', 'customerCatalogues'));
    }
    public function create() {
        $this->authorize('modules', 'customer.create');
        $provinces = $this->provinceRepository->all();
        $customerCatalogues = $this->customerCatalogueRepository->all();
        $sources = $this->sourceRepository->all();
        $config = $this->config();
        $config['seo'] = __('messages.customer');
        $config['method'] = 'create';
        $template = 'backend.customer.customer.store';
        return
            view('backend.dashboard.layout',
            compact('template', 'config', 'provinces', 'customerCatalogues', 'sources'));
    }
    public function store(
        StoreCustomerRequest $request,
    ) {
        if($this->customerService->create($request)) {
            return 
                redirect()
                ->route('customer.index')
                ->with('success', 'Thêm mới thành viên thành công');
        }
        return 
            redirect()
            ->route('customer.index')
            ->with('error', 'Thêm mới thành viên thất bại');
    }
    public function edit($id) {
        $this->authorize('modules', 'customer.update');
        $customer = $this->customerRepository->findById($id);
        $customerCatalogues = $this->customerCatalogueRepository->all();
        $sources = $this->sourceRepository->all();
        $provinces = $this->provinceRepository->all();
        $config = $this->config();
        $config['seo'] = __('messages.customer');
        $config['method'] = 'edit';
        $template = 'backend.customer.customer.store';
        return 
            view('backend.dashboard.layout',
            compact('template', 'config', 'provinces','customer', 'customerCatalogues', 'sources'));
    }
    public function update($id, UpdateCustomerRequest $request) {
        if($this->customerService->update($id, $request)) {
            return 
                redirect()
                ->route('customer.index')
                ->with('success', 'Cập nhật thành viên thành công');
        }
        return 
            redirect()
            ->route('customer.index')
            ->with('error', 'Cập nhật thành viên thất bại');
    }
    public function delete($id) {
        $this->authorize('modules', 'customer.destroy');
        $customer = $this->customerRepository->findById($id);
        $config['seo'] = __('messages.customer');
        $template = 'backend.customer.customer.delete';
        return
            view('backend.dashboard.layout',
            compact('template', 'config', 'customer'));
    }
    public function destroy($id) {
        if($this->customerService->destroy($id)) {
            return 
                redirect()
                ->route('customer.index')
                ->with('success', 'Xoá thành viên thành công');
        }
        return 
            redirect()
            ->route('customer.index')
            ->with('error', 'Xoá thành viên thất bại. Hãy thử lại');
    }

    private function config() {
        return [
            'js' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
                'backend/library/location.js',
                'backend/plugin/ckfinder_2/ckfinder.js',
                'backend/library/finder.js',
                
            ],
            'css' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
                
            ]
        ];
    }
}
