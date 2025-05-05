<?php

namespace App\Services;
use App\Services\Interfaces\UserServiceInterface;
use App\Repositories\Interfaces\UserRepositoryInterface as UserRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
/**
 * Class UserService
 * @package App\Services
 */
class UserService extends BaseService implements UserServiceInterface
{
    protected $userRepository;
    public function __construct(UserRepository $userRepository) {
        $this->userRepository = $userRepository;
    }
    public function paginate($request) {
        $condition = [
            'keyword' => addslashes($request->input('keyword')),
            'publish' => $request->integer('publish')
        ];
        $paePage = $request->integer('perpage');
        $users = $this->userRepository->pagination(
            $this->paginteSelect(), 
            $condition, 
            $paePage,
            ['path' => 'user/index'],
        );
        return $users;
    }
    public function create($request) {
        DB::beginTransaction();
        try {
            $payload = $request->except(['_token', 'send', 're_password']);
            if($payload['birthday'] != null) {
                $payload['birthday'] = $this->converBrithday($payload['birthday']);
            }
            $payload['password'] = Hash::make($payload['password']);
            $user = $this->userRepository->create($payload);
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
            $user = $this->userRepository->update($id,$payload);
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
            $user = $this->userRepository->forceDelete($id);
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
            'user_catalogue_id',
            'image'
        ];
    }
}
