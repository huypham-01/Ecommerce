<?php

namespace App\Services;
use App\Services\Interfaces\{class}CatalogueServiceInterface;
use App\Services\BaseService;
use App\Repositories\Interfaces\{class}CatalogueRepositoryInterface as {class}CatalogueRepository;
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
class {class}CatalogueService extends BaseService implements {class}CatalogueServiceInterface
{
    protected ${module}CatalogueRepository;
    protected $routerRepository;
    protected $nestedset;
    protected $language;
    public function __construct(
        {class}CatalogueRepository ${module}CatalogueRepository,
        RouterRepository $routerRepository,
    ) {
        $this->language = $this->currentLanguage();
        $this->{module}CatalogueRepository = ${module}CatalogueRepository;
        $this->routerRepository = $routerRepository;
        $this->nestedset = new Nestedsetbie([
            'table' => '{module}_catalogues',
            'foreignkey' => '{module}_catalogue_id',
            'language_id' => $this->language
        ]);
        $this->controllerName = '{class}CatalogueController';
    }
    public function paginate($request) {
        $paePage = $request->integer('perpage');
        $condition = [
            'keyword' => addslashes($request->input('keyword')),
            'publish' => $request->integer('publish'),
            'where'   => [
                ['tb2.language_id', '=', $this->language]
            ],
        ];
        ${module}Catalogue = $this->{module}CatalogueRepository->pagination(
            $this->paginteSelect(), 
            $condition, 
            $paePage,
            ['path' => '{module}/catalogue/index'],
            [
                '{module}_catalogues.lft', 'ASC'
            ],
            [
                ['{module}_catalogue_language as tb2', 'tb2.{module}_catalogue_id', '=', '{module}_catalogues.id'],
            ],
        );
        return ${module}Catalogue;
    }
    public function create($request, $languageId) {
        DB::beginTransaction();
        try {
            ${module}Catalogue = $this->createCatalogue($request);
            if(${module}Catalogue->id > 0) {
                $this->updateLanguageForCatalogue(${module}Catalogue, $request);
                $this->createRouter(${module}Catalogue, $request, $this->controllerName, $languageId);
                $this->nestedset();
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
            ${module}Catalogue = $this->{module}CatalogueRepository->findById($id);
            $flag = $this->updateCatalogue(${module}Catalogue, $request);
            if($flag == TRUE) {
                $this->updateLanguageForCatalogue(${module}Catalogue, $request);
                $this->updateRouter(${module}Catalogue, $request, $this->controllerName, $languageId);
                $this->nestedset();
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
            ${module}Catalogue = $this->{module}CatalogueRepository->update(${module}['modelId'],$payload);
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
            $lag = $this->{module}CatalogueRepository->updateByWhereIn('id', ${module}['id'], $payload);
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
            ${module}Catalogue = $this->{module}CatalogueRepository->delete($id);
            $this->routerRepository->forceDeleteByCondition([
                ['module_id', '=', $id],
                ['controllers', '=', 'app\Http\Controller\Forntend\{module}CatalogueController'],
            ]);
            $this->nestedset->Get('level ASC, order ASC');
            $this->nestedset->Recursive(0, $this->nestedset->Set());
            $this->nestedset->Action();
            DB::commit();
            return true;
        }catch(\Exception $e) {
            DB::rollBack();
            echo $e->getMessage();die();
            return false;
        }
    }
    //
    private function createCatalogue($request) {
        $payload = $request->only($this->payload());
        $payload['album'] = $this->formatAlbum($request);
        $payload['user_id'] = Auth::id();
        ${module}Catalogue = $this->{module}CatalogueRepository->create($payload);
        return ${module}Catalogue;
    }
    private function updateCatalogue(${module}Catalogue, $request) {
        $payload = $request->only($this->payload());
        $payload['album'] = $this->formatAlbum($request);
        $flag = $this->{module}CatalogueRepository->update(${module}Catalogue->id, $payload);
        return $flag;
    }
    private function updateLanguageForCatalogue(${module}Catalogue, $request) {
        $payload = $this->formatLanguagePayload(${module}Catalogue, $request);
        ${module}Catalogue->languages()->detach([$this->language, ${module}Catalogue->id]);
        $language = $this->{module}CatalogueRepository->createPivot(
            ${module}Catalogue, 
            $payload, 
            'languages');
        return $language;
    }
    private function formatLanguagePayload(${module}Catalogue, $request) {
        $payload = $request->only($this->payloadLaguage());
        $payload['canonical'] = Str::slug($payload['canonical']);
        $payload['language_id'] = $this->language;
        $payload['{module}_catalogue_id'] = ${module}Catalogue->id;
        return $payload;
    }

    //
    private function paginteSelect() {
        return [
            '{module}_catalogues.id',
            '{module}_catalogues.publish',
            '{module}_catalogues.image',
            '{module}_catalogues.level',
            '{module}_catalogues.order',
            'tb2.name',
            'tb2.canonical',
        ];
    }
    private function payload() {
        return [
            'parent_id', 
            'follow', 
            'publish', 
            'image',
            'album'
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
