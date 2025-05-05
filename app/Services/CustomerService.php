<?php

namespace App\Services;
use App\Services\Interfaces\CustomerServiceInterface;
use App\Repositories\Interfaces\CustomerRepositoryInterface as CustomerRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
/**
 * Class CustomerService
 * @package App\Services
 */
class CustomerService extends BaseService implements CustomerServiceInterface
{
    protected $customerRepository;
    public function __construct(CustomerRepository $customerRepository) {
        $this->customerRepository = $customerRepository;
    }
    public function paginate($request) {
        $condition = [
            'keyword' => addslashes($request->input('keyword')),
            'publish' => $request->integer('publish')
        ];
        $paePage = $request->integer('perpage');
        $customers = $this->customerRepository->pagination(
            $this->paginteSelect(), 
            $condition, 
            $paePage,
            ['path' => 'customer/index'],
        );
        return $customers;
    }
    public function create($request) {
        DB::beginTransaction();
        try {
            $payload = $request->except(['_token', 'send', 're_password']);
            if($payload['birthday'] != null) {
                $payload['birthday'] = $this->converBrithday($payload['birthday']);
            }
            $payload['password'] = Hash::make($payload['password']);
            $customer = $this->customerRepository->create($payload);
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
            if($payload['birthday'] != null) {
                $payload['birthday'] = $this->converBrithday($payload['birthday']);
            }
            $customer = $this->customerRepository->update($id,$payload);
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
        $brithday = $carbonDate->format('Y-m-d');
        return $brithday;
    }
    public function destroy($id) {
        DB::beginTransaction();
        try {
            $customer = $this->customerRepository->forceDelete($id);
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
            'email',
            'name',
            'phone',
            'address',
            'publish',
            'customer_catalogue_id',
            'source_id',
        ];
    }
}
