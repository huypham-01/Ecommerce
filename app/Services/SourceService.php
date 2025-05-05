<?php

namespace App\Services;
use App\Services\Interfaces\SourceServiceInterface;
use App\Repositories\Interfaces\SourceRepositoryInterface as SourceRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
/**
 * Class SourceService
 * @package App\Services
 */
class SourceService extends BaseService implements SourceServiceInterface
{
    protected $sourceRepository;
    public function __construct(SourceRepository $sourceRepository) {
        $this->sourceRepository = $sourceRepository;
    }
    public function paginate($request) {
        $condition = [
            'keyword' => addslashes($request->input('keyword')),
            'publish' => $request->integer('publish')
        ];
        $paePage = $request->integer('perpage');
        $sources = $this->sourceRepository->pagination(
            $this->paginteSelect(), 
            $condition, 
            $paePage,
            ['path' => 'source/index'],
        );
        return $sources;
    }
    public function create($request, $languageId) {
        DB::beginTransaction();
        try {
            $payload = $request->only('name', 'keyword', 'description');
        
            $source = $this->sourceRepository->create($payload);

            DB::commit();
            return true;
        }catch(\Exception $e) {
            DB::rollBack();
            echo $e->getMessage();die();
            return false;
        }
    }
    public function update($id, $request, $languageId) {
        DB::beginTransaction();
        try {
            $payload = $request->only('name', 'keyword', 'description');
            $source = $this->sourceRepository->update($id,$payload);
            DB::commit();
            return true;
        }catch(\Exception $e) {
            DB::rollBack();
            echo $e->getMessage();die();
            return false;
        }
    }
    public function destroy($id) {
        DB::beginTransaction();
        try {
            $source = $this->sourceRepository->forceDelete($id);
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
            'keyword',
            'description',
            'publish',
        ];
    }
}
