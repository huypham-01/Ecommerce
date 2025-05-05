<?php

namespace App\Services;
use App\Services\Interfaces\PostCatalogueServiceInterface;
use App\Services\BaseService;
use App\Repositories\Interfaces\PostCatalogueRepositoryInterface as PostCatalogueRepository;
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
class PostCatalogueService extends BaseService implements PostCatalogueServiceInterface
{
    protected $postCatalogueRepository;
    protected $routerRepository;
    protected $nestedset;
    protected $language;
    public function __construct(
        PostCatalogueRepository $postCatalogueRepository,
        RouterRepository $routerRepository,
    ) {
        $this->language = $this->currentLanguage();
        $this->postCatalogueRepository = $postCatalogueRepository;
        $this->routerRepository = $routerRepository;
        $this->nestedset = new Nestedsetbie([
            'table' => 'post_catalogues',
            'foreignkey' => 'post_catalogue_id',
            'language_id' => $this->language
        ]);
        $this->controllerName = 'PostCatalogueController';
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
        $postCatalogue = $this->postCatalogueRepository->pagination(
            $this->paginteSelect(), 
            $condition, 
            $paePage,
            ['path' => 'post.catalogue.index'],
            ['post_catalogues.lft', 'ASC'],
            [
                ['post_catalogue_language as tb2', 'tb2.post_catalogue_id', '=', 'post_catalogues.id'],
            ],
        );
        return $postCatalogue;
    }
    public function create($request, $languageId) {
        DB::beginTransaction();
        try {
            $postCatalogue = $this->createCatalogue($request);
            if($postCatalogue->id > 0) {
                $this->updateLanguageForCatalogue($postCatalogue, $request);
                $this->createRouter($postCatalogue, $request, $this->controllerName, $languageId);
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
            $postCatalogue = $this->postCatalogueRepository->findById($id);
            $flag = $this->updateCatalogue($postCatalogue, $request);
            if($flag == TRUE) {
                $this->updateLanguageForCatalogue($postCatalogue, $request);
                $this->updateRouter($postCatalogue, $request, $this->controllerName, $languageId);
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
            $postCatalogue = $this->postCatalogueRepository->delete($id);
            $this->routerRepository->forceDeleteByCondition([
                ['module_id', '=', $id],
                ['controllers', '=', 'app\Http\Controller\Forntend\PostCatalogueController'],
            ]);
            $this->nestedset = new Nestedsetbie([
                'table' => 'post_catalogues',
                'foreignkey' => 'post_catalogue_id',
                'language_id' => $this->language
            ]);
            // $this->nestedset->Get('level ASC, order ASC');
            // $this->nestedset->Recursive(0, $this->nestedset->Set());
            // $this->nestedset->Action();
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
        $postCatalogue = $this->postCatalogueRepository->create($payload);
        return $postCatalogue;
    }
    private function updateCatalogue($postCatalogue, $request) {
        $payload = $request->only($this->payload());
        $payload['album'] = $this->formatAlbum($request);
        $flag = $this->postCatalogueRepository->update($postCatalogue->id, $payload);
        return $flag;
    }
    private function updateLanguageForCatalogue($postCatalogue, $request) {
        $payload = $this->formatLanguagePayload($postCatalogue, $request);
        $postCatalogue->languages()->detach([$this->language, $postCatalogue->id]);
        $language = $this->postCatalogueRepository->createPivot(
            $postCatalogue, 
            $payload, 
            'languages');
        return $language;
    }
    private function formatLanguagePayload($postCatalogue, $request) {
        $payload = $request->only($this->payloadLaguage());
        $payload['canonical'] = Str::slug($payload['canonical']);
        $payload['language_id'] = $this->language;
        $payload['post_catalogue_id'] = $postCatalogue->id;
        return $payload;
    }

    //
    private function paginteSelect() {
        return [
            'post_catalogues.id',
            'post_catalogues.publish',
            'post_catalogues.image',
            'post_catalogues.level',
            'post_catalogues.order',
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
