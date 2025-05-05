<?php

namespace App\Services;
use App\Services\Interfaces\AttributeCatalogueServiceInterface;
use App\Services\BaseService;
use App\Repositories\Interfaces\AttributeCatalogueRepositoryInterface as AttributeCatalogueRepository;
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
class AttributeCatalogueService extends BaseService implements AttributeCatalogueServiceInterface
{
    protected $attributeCatalogueRepository;
    protected $routerRepository;
    protected $nestedset;
    protected $language;
    public function __construct(
        AttributeCatalogueRepository $attributeCatalogueRepository,
        RouterRepository $routerRepository,
    ) {
        $this->language = $this->currentLanguage();
        $this->attributeCatalogueRepository = $attributeCatalogueRepository;
        $this->routerRepository = $routerRepository;
        $this->nestedset = new Nestedsetbie([
            'table' => 'attribute_catalogues',
            'foreignkey' => 'attribute_catalogue_id',
            'language_id' => $this->language
        ]);
        $this->controllerName = 'AttributeCatalogueController';
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
        $attributeCatalogue = $this->attributeCatalogueRepository->pagination(
            $this->paginteSelect(), 
            $condition, 
            $paePage,
            ['path' => 'attribute/catalogue/index'],
            [
                'attribute_catalogues.lft', 'ASC'
            ],
            [
                ['attribute_catalogue_language as tb2', 'tb2.attribute_catalogue_id', '=', 'attribute_catalogues.id'],
            ],
        );
        return $attributeCatalogue;
    }
    public function create($request, $languageId) {
        DB::beginTransaction();
        try {
            $attributeCatalogue = $this->createCatalogue($request);
            if($attributeCatalogue->id > 0) {
                $this->updateLanguageForCatalogue($attributeCatalogue, $request);
                $this->createRouter($attributeCatalogue, $request, $this->controllerName, $languageId);
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
            $attributeCatalogue = $this->attributeCatalogueRepository->findById($id);
            $flag = $this->updateCatalogue($attributeCatalogue, $request);
            if($flag == TRUE) {
                $this->updateLanguageForCatalogue($attributeCatalogue, $request);
                $this->updateRouter($attributeCatalogue, $request, $this->controllerName, $languageId);
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
    public function updateStatus($attribute = []) {
        DB::beginTransaction();
        try {
            $payload[$attribute['field']] = (($attribute['value'] == 1) ? 2 : 1);
            $attributeCatalogue = $this->attributeCatalogueRepository->update($attribute['modelId'],$payload);
            //$this->changeUserStatus($attribute, $payload[$attribute['field']]);

            DB::commit();
            return true;
        }catch(\Exception $e) {
            DB::rollBack();
            echo $e->getMessage();die();
            return false;
        }
    }
    public function updateStatusAll($attribute) {
        DB::beginTransaction();
        try {
            $payload[$attribute['field']] = $attribute['value'];
            $lag = $this->attributeCatalogueRepository->updateByWhereIn('id', $attribute['id'], $payload);
            //$this->changeUserStatus($attribute, $attribute['value']);

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
            $attributeCatalogue = $this->attributeCatalogueRepository->delete($id);
            $this->routerRepository->forceDeleteByCondition([
                ['module_id', '=', $id],
                ['controllers', '=', 'app\Http\Controller\Forntend\attributeCatalogueController'],
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
        $attributeCatalogue = $this->attributeCatalogueRepository->create($payload);
        return $attributeCatalogue;
    }
    private function updateCatalogue($attributeCatalogue, $request) {
        $payload = $request->only($this->payload());
        $payload['album'] = $this->formatAlbum($request);
        $flag = $this->attributeCatalogueRepository->update($attributeCatalogue->id, $payload);
        return $flag;
    }
    private function updateLanguageForCatalogue($attributeCatalogue, $request) {
        $payload = $this->formatLanguagePayload($attributeCatalogue, $request);
        $attributeCatalogue->languages()->detach([$this->language, $attributeCatalogue->id]);
        $language = $this->attributeCatalogueRepository->createPivot(
            $attributeCatalogue, 
            $payload, 
            'languages');
        return $language;
    }
    private function formatLanguagePayload($attributeCatalogue, $request) {
        $payload = $request->only($this->payloadLaguage());
        $payload['canonical'] = Str::slug($payload['canonical']);
        $payload['language_id'] = $this->language;
        $payload['attribute_catalogue_id'] = $attributeCatalogue->id;
        return $payload;
    }

    //
    private function paginteSelect() {
        return [
            'attribute_catalogues.id',
            'attribute_catalogues.publish',
            'attribute_catalogues.image',
            'attribute_catalogues.level',
            'attribute_catalogues.order',
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
