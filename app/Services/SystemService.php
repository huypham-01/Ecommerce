<?php

namespace App\Services;
use App\Services\Interfaces\SystemServiceInterface;
use App\Repositories\Interfaces\SystemRepositoryInterface as SystemRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
/**
 * Class SystemService
 * @package App\Services
 */
class SystemService implements SystemServiceInterface
{
    protected $systemRepository;
    public function __construct(SystemRepository $systemRepository) {
        $this->systemRepository = $systemRepository;
        
    }
    public function save($request, $languageId) {
        DB::beginTransaction();
        try {
            $config = $request->input('config');
            $payload = [];
            if(count($config)) {
                foreach($config as $key => $val) {
                    $payload = [
                        'keyword' => $key,
                        'content' => $val,
                        'language_id' => $languageId,
                        'user_id' => Auth::id(),
                    ];
                    $condition = ['keyword' => $key, 'language_id' => $languageId];
                    $this->systemRepository->updateOrInsert($condition, $payload);
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
            'email',
            'name',
            'phone',
            'address',
            'publish',
            'system_catalogue_id',
            'image'
        ];
    }
}
