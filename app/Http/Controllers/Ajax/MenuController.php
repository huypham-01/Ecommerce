<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\MenuRepositoryInterface as MenuRepository;
use App\Services\Interfaces\MenuCatalogueServiceInterface as MenuCatalogueService;
use App\Services\Interfaces\MenuServiceInterface as MenuService;
use App\Models\Language;
use App\Http\Requests\StoreMenuCatalogueRequest;

class MenuController extends Controller
{
    //
    protected $menuRepository;
    protected $menuCatalogueService;
    protected $menuService;
    protected $language;
    public function __construct(
        MenuRepository $menuRepository,
        MenuCatalogueService $menuCatalogueService,
        MenuService $menuService,
    ) {
        $this->menuRepository = $menuRepository;
        $this->menuCatalogueService = $menuCatalogueService;
        $this->menuService = $menuService;
        $this->middleware(function($request, $next) {
            $locale = app()->getLocale();
            $language = Language::where('canonical', $locale)->first();
            $this->language = $language->id;
            // $this->initialize();
            return $next($request);
        });
    }
    public function createCatalogue(StoreMenuCatalogueRequest $request) {
        $menuCatalogue = $this->menuCatalogueService->create($request);
        if($menuCatalogue !== FALSE) {
            return response()->json([
                'code' => 0,
                'message' => 'Tạo nhóm menu thành công',
                'data' => $menuCatalogue
            ]);
        }
        return response()->json([
            'message' => 'Có vấn đề xảy ra, hãy thử lại',
            'code' => 1
        ]);
    }
    public function drag(Request $request) {
        $json = json_decode($request->string('json'), true);
        $menuCatalogueId = $request->integer('menu_catalogue_id');

        $flag = $this->menuService->dragUpdate($json, $menuCatalogueId, $this->language);
    }
   
}
