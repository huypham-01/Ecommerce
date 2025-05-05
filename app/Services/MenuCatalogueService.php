<?php

namespace App\Services;
use App\Services\Interfaces\MenuCatalogueServiceInterface;
use App\Services\BaseService;
use App\Repositories\Interfaces\MenuCatalogueRepositoryInterface as MenuCatalogueRepository;
use App\Repositories\Interfaces\RouterRepositoryInterface as RouterRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Classes\Nestedsetbie;
use App\Models\MenuCatalogue;
use Illuminate\Support\Str;

/**
 * Class UserCatalogueService
 * @package App\Services
 */
class MenuCatalogueService extends BaseService implements MenuCatalogueServiceInterface
{
    protected $menuCatalogueRepository;
    public function __construct(
        MenuCatalogueRepository $menuCatalogueRepository,
    ) {
        $this->menuCatalogueRepository = $menuCatalogueRepository;
        $this->controllerName = 'MenuCatalogueController';
    }
    public function paginate($request) {
        $condition['keyword'] = addslashes($request->input('keyword'));
        $condition['publish'] = $request->integer('publish');
        $paePage = $request->integer('perpage');
        $menuCatalogues = $this->menuCatalogueRepository->pagination(
            $this->paginteSelect(), 
            $condition, 
            $paePage,
            ['path' => 'menu/index']
        );
        return $menuCatalogues;
    }
    public function create($request) {
        DB::beginTransaction();
        try {
            $payload = $request->only('name', 'keyword');
            $menuCatalogue = $this->menuCatalogueRepository->create($payload);

            DB::commit();
            return [
                'name' => $menuCatalogue->name,
                'id' => $menuCatalogue->id,
            ];
        }catch(\Exception $e) {
            DB::rollBack();
            echo $e->getMessage();die();
            return false;
        }
    }
    public function update($id, $request, $languageId) {
        DB::beginTransaction();
        try {
           
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
            $menuCatalogue = $this->menuCatalogueRepository->delete($id);
            
            DB::commit();
            return true;
        }catch(\Exception $e) {
            DB::rollBack();
            //echo $e->getMessage();die();
            return false;
        }
    }
    //
    private function createMenuCatalogue($request) {
        $payload = $request->only($this->payload());
        $payload['user_id'] = Auth::id();
        $payload['album'] = $this->formatAlbum($request);
        $menuCatalogue = $this->menuCatalogueRepository->create($payload);
        return $menuCatalogue;
    }
    private function uploadMenuCatalogue($menuCatalogue, $request) {
        $payload = $request->only($this->payload());
        $payload['album'] = $this->formatAlbum($request);
        return $flag = $this->menuCatalogueRepository->update($menuCatalogue->id, $payload);
    }


    private function updateLanguageForMenuCatalogue($menuCatalogue, $request, $languageId) {
        $payload = $request->only($this->payloadLaguage());
        $payload = $this->formatLanguagePayload($payload, $menuCatalogue->id, $languageId);
        
        $menuCatalogue->languages()->detach([$languageId, $menuCatalogue->id]);
        $aa =  $this->menuCatalogueRepository->createPivot(
            $menuCatalogue,
            $payload, 
            'languages'
        );
        return $aa;
    }
    private function updateCatalogueForMenuCatalogue($menuCatalogue, $request) {
        $menuCatalogue->menuCatalogue_catalogues()->sync($this->catalogue($request));
    }
    private function formatLanguagePayload($payload, $menuCatalogueId, $languageId) {
        $payload['language_id'] = $languageId;
        $payload['menuCatalogue_id'] = $menuCatalogueId;
        $payload['canonical'] = Str::slug($payload['canonical']);
        return $payload;
    }

 
    //
 

    private function catalogue($request) {
        if ($request->input('catalogue') != null) {
            return array_unique(array_merge($request->input('catalogue'), [$request->menuCatalogue_catalogue_id]));
        }
        return [$request->menuCatalogue_catalogue_id];
    }
    private function whereRaw($request, $languageId) {
        $rawCondition = [];
        if($request->integer('menuCatalogue_catalogue_id') > 0) {
            $rawCondition['whereRaw'] = [
                [
                    'tb3.menuCatalogue_catalogue_id IN (
                        SELECT id 
                        FROM menuCatalogue_catalogues 
                        JOIN menuCatalogue_catalogue_language ON menuCatalogue_catalogues.id = menuCatalogue_catalogue_language.menuCatalogue_catalogue_id
                        WHERE lft >= (SELECT lft FROM menuCatalogue_catalogues as pc WHERE pc.id = ?) 
                        AND rgt <= (SELECT rgt FROM menuCatalogue_catalogues as pc WHERE pc.id = ?)
                    )',
                    [$request->integer('menuCatalogue_catalogue_id'), $request->integer('menuCatalogue_catalogue_id')]
                ]
            ];
        }
        return $rawCondition;
    }
    private function paginteSelect() {
        return [
            'id',
            'name',
            'keyword',
            'publish'
        ];
    }
    private function payload() {
        return [
            'follow', 
            'publish', 
            'image',
            'album',
            'menuCatalogue_catalogue_id',
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
