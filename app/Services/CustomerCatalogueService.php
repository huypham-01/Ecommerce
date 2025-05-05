<?php

namespace App\Services;
use App\Services\Interfaces\CustomerCatalogueServiceInterface;
use App\Repositories\Interfaces\CustomerCatalogueRepositoryInterface as CustomerCatalogueRepository;
use App\Repositories\Interfaces\CustomerRepositoryInterface as CustomerRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
/**
 * Class CustomerCatalogueService
 * @package App\Services
 */
class CustomerCatalogueService extends BaseService implements CustomerCatalogueServiceInterface
{
    protected $customerCatalogueRepository;
    protected $customerRepository;
    public function __construct(
        CustomerCatalogueRepository $customerCatalogueRepository,
        CustomerRepository $customerRepository
    ) {
        $this->customerCatalogueRepository = $customerCatalogueRepository;
        $this->customerRepository = $customerRepository;
    }
    public function paginate($request) {
        $condition = [
            'keyword' => addslashes($request->input('keyword')),
            'publish' => $request->integer('publish')
        ];
        $paePage = $request->integer('perpage');
        $customerCatalogue = $this->customerCatalogueRepository->pagination(
            $this->paginteSelect(), 
            $condition, 
            $paePage,
            ['path' => 'customer/catalogue/index'],
            ['id', 'DESC'], 
            [],
            ['customers']);
        return $customerCatalogue;
    }
    public function create($request) {
        DB::beginTransaction();
        try {
            $payload = $request->except(['_token', 'send']);
            $customerCatalogue = $this->customerCatalogueRepository->create($payload);
            DB::commit();
            return true;
        }catch(\Exception $e) {
            DB::rollBack();
            echo $e->getMessage();die();
            return false;
        }
    }
    public function update($id, $request) {
        DB::beginTransaction();
        try {
            $payload = $request->except(['_token', 'send']);
            $customerCatalogue = $this->customerCatalogueRepository->update($id,$payload);
            DB::commit();
            return true;
        }catch(\Exception $e) {
            DB::rollBack();
            echo $e->getMessage();die();
            return false;
        }
    }
    private function changeCustomerStatus($post, $value) {
        DB::beginTransaction();
        try {
            $array = [];
            if(isset($post['modelId'])) {
                $array[] = $post['modelId'];
            } else {
                $array = $post['id'];
            }
            $payload[$post['field']] = $value;
            $this->customerRepository->updateByWhereIn('customer_catalogue_id', $array, $payload);
            DB::commit();
            return true;
        }catch(\Exception $e) {
            DB::rollBack();
            echo $e->getMessage();die();
            return false;
        }
    }
    private function converBrithday($brithday = '') {
        $carbonDate = Carbon::createFromFormat('Y-m-d', $brithday);
        $brithday = $carbonDate->format('Y-m-d H:i:s');
        return $brithday;
    }
    public function destroy($id) {
        DB::beginTransaction();
        try {
            $customerCatalogue = $this->customerCatalogueRepository->forceDelete($id);
            DB::commit();
            return true;
        }catch(\Exception $e) {
            DB::rollBack();
            echo $e->getMessage();die();
            return false;
        }
    }
    public function setPermission($request) {
        DB::beginTransaction();
        try {
            $permissions = $request->input('permission');
            if(count($permissions)) {
                foreach($permissions as $key => $val) {
                    $customerCatalogue = $this->customerCatalogueRepository->findById($key);
                    $customerCatalogue->permissions()->detach();
                    $customerCatalogue->permissions()->sync($val);
                }
            }
            DB::commit();
            return true;
        }catch(\Exception $e) {
            DB::rollBack();
            echo $e->getMessage();die();
            return false;
        }
    }
    private function paginteSelect() {
        return [
            'id',
            'name',
            'description',
            'publish'
        ];
    }
}
