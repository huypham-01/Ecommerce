<?php

namespace App\Services;
use App\Services\Interfaces\{class}ServiceInterface;
use App\Services\BaseService;
use App\Repositories\Interfaces\{class}RepositoryInterface as {class}Repository;
use App\Repositories\Interfaces\RouterRepositoryInterface as RouterRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Classes\Nestedsetbie;
use Illuminate\Support\Str;
/**
 * Class UserCatalogueService
 * @package App\Services
 */
class {class}Service extends BaseService implements {class}ServiceInterface
{
    protected ${module}Repository;
    protected $routerRepository;
    public function __construct(
        {class}Repository ${module}Repository,
        RouterRepository $routerRepository
    ) {
        $this->{module}Repository = ${module}Repository;
        $this->routerRepository = $routerRepository;
        $this->controllerName = '{class}Controller';
    }
    public function paginate($request, $languageId) {
        $condition['keyword'] = addslashes($request->input('keyword'));
        $condition['publish'] = $request->integer('publish');
        $condition['where'] = [
            ['tb2.language_id', '=', $languageId]
        ];
        $paePage = $request->integer('perpage');
        ${module} = $this->{module}Repository->pagination(
            $this->paginteSelect(), 
            $condition, 
            $paePage,
            ['path' => '{module}/index'],
            [
                '{module}s.id', 'DESC'
            ],
            [
                ['{module}_language as tb2', 'tb2.{module}_id', '=', '{module}s.id'],
                ['{module}_catalogue_{module} as tb3', '{module}s.id', '=', 'tb3.{module}_id'],
            ],
            ['{module}_catalogues'],
            $this->whereRaw($request, $languageId)
        );
        return ${module};
    }
    public function create($request, $languageId) {
        DB::beginTransaction();
        try {
            ${module} = $this->create{class}($request);
            if(${module}->id > 0) {
                $this->updateLanguageFor{class}(${module}, $request, $languageId);
                $this->updateCatalogueFor{class}(${module}, $request);
                
                $this->createRouter(${module}, $request, $this->controllerName, $languageId);
            }

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
            ${module} = $this->{module}Repository->findById($id);
            if($this->upload{class}(${module}, $request)) {
                $this->updateLanguageFor{class}(${module}, $request, $languageId);
                
                $this->updateCatalogueFor{class}(${module}, $request);
                $this->updateRouter(${module}, $request, $this->controllerName, $languageId);
            }
            DB::commit();
            return true;
        }catch(\Exception $e) {
            DB::rollBack();
            echo $e->getMessage();die();
            return false;
        }
    }
    public function updateStatus(${module} = []) {
        DB::beginTransaction();
        try {
            $payload[${module}['field']] = ((${module}['value'] == 1) ? 2 : 1);
            ${module} = $this->{module}Repository->update(${module}['modelId'],$payload);
            //$this->changeUserStatus(${module}, $payload[${module}['field']]);

            DB::commit();
            return true;
        }catch(\Exception $e) {
            DB::rollBack();
            echo $e->getMessage();die();
            return false;
        }
    }
    public function updateStatusAll(${module}) {
        DB::beginTransaction();
        try {
            $payload[${module}['field']] = ${module}['value'];
            $lag = $this->{module}Repository->updateByWhereIn('id', ${module}['id'], $payload);
            //$this->changeUserStatus(${module}, ${module}['value']);

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
            ${module} = $this->{module}Repository->delete($id);
            $this->routerRepository->forceDeleteByCondition([
                ['module_id', '=', $id],
                ['controllers', '=', 'app\Http\Controller\Forntend\{module}Controller'],
            ]);
            DB::commit();
            return true;
        }catch(\Exception $e) {
            DB::rollBack();
            //echo $e->getMessage();die();
            return false;
        }
    }
    //
    private function create{class}($request) {
        $payload = $request->only($this->payload());
        $payload['user_id'] = Auth::id();
        $payload['album'] = $this->formatAlbum($request);
        ${module} = $this->{module}Repository->create($payload);
        return ${module};
    }
    private function upload{class}(${module}, $request) {
        $payload = $request->only($this->payload());
        $payload['album'] = $this->formatAlbum($request);
        return $flag = $this->{module}Repository->update(${module}->id, $payload);
    }


    private function updateLanguageFor{class}(${module}, $request, $languageId) {
        $payload = $request->only($this->payloadLaguage());
        $payload = $this->formatLanguagePayload($payload, ${module}->id, $languageId);
        
        ${module}->languages()->detach([$languageId, ${module}->id]);
        $aa =  $this->{module}Repository->createPivot(
            ${module},
            $payload, 
            'languages'
        );
        return $aa;
    }
    private function updateCatalogueFor{class}(${module}, $request) {
        ${module}->{module}_catalogues()->sync($this->catalogue($request));
    }
    private function formatLanguagePayload($payload, ${module}Id, $languageId) {
        $payload['language_id'] = $languageId;
        $payload['{module}_id'] = ${module}Id;
        $payload['canonical'] = Str::slug($payload['canonical']);
        return $payload;
    }

 
    //
 

    private function catalogue($request) {
        if ($request->input('catalogue') != null) {
            return array_unique(array_merge($request->input('catalogue'), [$request->{module}_catalogue_id]));
        }
        return [$request->{module}_catalogue_id];
    }
    private function whereRaw($request, $languageId) {
        $rawCondition = [];
        if($request->integer('{module}_catalogue_id') > 0) {
            $rawCondition['whereRaw'] = [
                [
                    'tb3.{module}_catalogue_id IN (
                        SELECT id 
                        FROM {module}_catalogues 
                        JOIN {module}_catalogue_language ON {module}_catalogues.id = {module}_catalogue_language.{module}_catalogue_id
                        WHERE lft >= (SELECT lft FROM {module}_catalogues as pc WHERE pc.id = ?) 
                        AND rgt <= (SELECT rgt FROM {module}_catalogues as pc WHERE pc.id = ?)
                    )',
                    [$request->integer('{module}_catalogue_id'), $request->integer('{module}_catalogue_id')]
                ]
            ];
        }
        return $rawCondition;
    }
    private function paginteSelect() {
        return [
            '{module}s.id',
            '{module}s.publish',
            '{module}s.image',
            '{module}s.order',
            'tb2.name',
            'tb2.canonical',
        ];
    }
    private function payload() {
        return [
            'follow', 
            'publish', 
            'image',
            'album',
            '{module}_catalogue_id',
        ];
    }
    private function payloadLaguage() {
        return [
            'name', 
            'description', 
            'content',
            'meta_title',
            'meta_keyword', 
            'meta_description', 
            'canonical'
        ];
    }
}
