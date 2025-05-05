<?php

namespace App\Services;
use App\Services\Interfaces\ProductCatalogueServiceInterface;
use App\Services\BaseService;
use App\Repositories\Interfaces\ProductCatalogueRepositoryInterface as ProductCatalogueRepository;
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
class ProductCatalogueService extends BaseService implements ProductCatalogueServiceInterface
{
    protected $productCatalogueRepository;
    protected $routerRepository;
    protected $nestedset;
    protected $language;
    public function __construct(
        ProductCatalogueRepository $productCatalogueRepository,
        RouterRepository $routerRepository,
    ) {
        $this->language = $this->currentLanguage();
        $this->productCatalogueRepository = $productCatalogueRepository;
        $this->routerRepository = $routerRepository;
        $this->nestedset = new Nestedsetbie([
            'table' => 'product_catalogues',
            'foreignkey' => 'product_catalogue_id',
            'language_id' => $this->language
        ]);
        $this->controllerName = 'ProductCatalogueController';
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
        $productCatalogue = $this->productCatalogueRepository->pagination(
            $this->paginteSelect(), 
            $condition, 
            $paePage,
            ['path' => 'product.catalogue.index'],
            [
                'product_catalogues.lft', 'ASC'
            ],
            [
                ['product_catalogue_language as tb2', 'tb2.product_catalogue_id', '=', 'product_catalogues.id'],
            ],
        );
        return $productCatalogue;
    }
    public function create($request, $languageId) {
        DB::beginTransaction();
        try {
            $productCatalogue = $this->createCatalogue($request);
            if($productCatalogue->id > 0) {
                $this->updateLanguageForCatalogue($productCatalogue, $request);
                $this->createRouter($productCatalogue, $request, $this->controllerName, $languageId);
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
            $productCatalogue = $this->productCatalogueRepository->findById($id);
            $flag = $this->updateCatalogue($productCatalogue, $request);
            if($flag == TRUE) {
                $this->updateLanguageForCatalogue($productCatalogue, $request);
                $this->updateRouter($productCatalogue, $request, $this->controllerName, $languageId);
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
  
    public function destroy($id) {
        DB::beginTransaction();
        try {
            $productCatalogue = $this->productCatalogueRepository->delete($id);
            $this->routerRepository->forceDeleteByCondition([
                ['module_id', '=', $id],
                ['controllers', '=', 'app\Http\Controller\Forntend\ProductCatalogueController'],
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
        $productCatalogue = $this->productCatalogueRepository->create($payload);
        return $productCatalogue;
    }
    private function updateCatalogue($productCatalogue, $request) {
        $payload = $request->only($this->payload());
        $payload['album'] = $this->formatAlbum($request);
        $flag = $this->productCatalogueRepository->update($productCatalogue->id, $payload);
        return $flag;
    }
    private function updateLanguageForCatalogue($productCatalogue, $request) {
        $payload = $this->formatLanguagePayload($productCatalogue, $request);
        $productCatalogue->languages()->detach([$this->language, $productCatalogue->id]);
        $language = $this->productCatalogueRepository->createPivot(
            $productCatalogue, 
            $payload, 
            'languages');
        return $language;
    }
    private function formatLanguagePayload($productCatalogue, $request) {
        $payload = $request->only($this->payloadLaguage());
        $payload['canonical'] = Str::slug($payload['canonical']);
        $payload['language_id'] = $this->language;
        $payload['product_catalogue_id'] = $productCatalogue->id;
        return $payload;
    }

    //
    private function paginteSelect() {
        return [
            'product_catalogues.id',
            'product_catalogues.publish',
            'product_catalogues.image',
            'product_catalogues.level',
            'product_catalogues.order',
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
