<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Models\Language;
use Illuminate\Http\Request;
use App\Services\Interfaces\UserServiceInterface as UserService;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;


class dashboardController extends Controller
{
    //
    protected $language;
    public function __construct(
       
    ) {
        $this->middleware(function($request, $next) {
            $locale = app()->getLocale();
            $language = Language::where('canonical', $locale)->first();
            $this->language = $language->id;
            return $next($request);
        });
    }
    public function changeStatus(Request $request){
        $post = $request->input();
        $serviceInterfaceNamespacce = '\App\Services\\'.ucfirst($post['model']).'Service';
        if(class_exists($serviceInterfaceNamespacce)) {
            $serviceInstance = app($serviceInterfaceNamespacce);
        }
        $flag = $serviceInstance->updateStatus($post);
        return response()->json(['flag' => $flag]);
    }
    public function changeStatusAll(Request $request) {
        $post = $request->input();
        $serviceInterfaceNamespacce = '\App\Services\\'.ucfirst($post['model']).'Service';
        if(class_exists($serviceInterfaceNamespacce)) {
            $serviceInstance = app($serviceInterfaceNamespacce);
        }
        $flag = $serviceInstance->updateStatusAll($post);
        return response()->json(['flag' => $flag]);
    }
    public function getMenu(Request $request) {
        $model = $request->input('model');
        $page = ($request->input('page')) ?? 1;
        $keyword = ($request->string('keyword')) ?? null;
        $serviceInterfaceNamespacce = '\App\Repositories\\'.ucfirst($model).'Repository';
        if(class_exists($serviceInterfaceNamespacce)) {
            $serviceInstance = app($serviceInterfaceNamespacce);
        }
        $agruments = $this->paginationAgrument($model);
        $object = $serviceInstance->pagination(...array_values($agruments));
        
        return response()->json($object);
    }
    private function paginationAgrument(string $model = ''): array {
        $model = Str::snake($model);
        $join = [
            [$model.'_language as tb2', 'tb2.'.$model.'_id', '=', $model.'s.id'],
        ];
        if(strpos($model, '_catalogue') === false) {
            $join[] = [''.$model.'_catalogue_'.$model.' as tb3', ''.$model.'s.id', '=','tb3.'.$model.'_id'];
        }
        $condition = [
            'where' => [
                ['tb2.language_id', '=', $this->language],
            ]
        ];
        // if(!is_null($keyword)) {
        //     $condition['keywork'] = addslashes($keyword);
        // };
        return [
            'column' => ['id', 'name', 'canonical'],
            'condition' => $condition,
            'perpage' => 5,
            'paginationConfig' => [
                'path' => $model.'.index',
                'groupBy' => ['id', 'name', 'canonical'],
            ],
            'orderBy' => [$model.'s.id', 'DESC'],
            'join' => $join,
            'relations' => [],
        ];
    }
    public function findModelByObject(Request $request) {
        $get = $request->input();
        $keyword = $get['keyword'];
        $alias = Str::snake($get['model']) . '_language';
        $class = loadClass($get['model']);
        $object = $class->findWidgetItem([
            ['name','LIKE', '%'.$keyword.'%'],
        ], $this->language, $alias);
        return response()->json($object);
    }

    public function findPromotionObject(Request $request) {
        $get = $request->input();
        $model = $get['option']['model'];
        $keyword = $get['search'];
        $alias = Str::snake($model).'_language';
        $class = loadClass($model);
        $object = $class->findWidgetItem([
            ['name','LIKE', '%'.$keyword.'%'],
        ], $this->language, $alias);
        $temp = [];
        if(count($object)) {
            foreach($object as $key => $val) {
                $temp[] = [
                    'id' => $val->id,
                    'text' => $val->languages->first()->pivot->name,
                ];
            }
        }
        return response()->json(array('item' => $temp));
    }
    public function getPromotionConditionValue(Request $request) {
        try {
            $get = $request->input();
            switch ($get['value']) {
                case 'staff_take_care_customer':
                    $class = loadClass('User');
                    $object = $class->all()->toArray();
                    break;
                case 'customer_group':
                    $class = loadClass('CustomerCatalogue');
                    $object = $class->all()->toArray();
                    break;
                case 'customer_gender':
                    $object = __('module.gender');
                    break;
                case 'customer_birthday':
                    $object = __('module.day');
                    break;
            }
            $temp = [];
            if(!is_null($object) && count($object)) {
                foreach ($object as $key => $val) {
                    $temp[] = [
                        'id' => $val['id'],
                        'text' => $val['name'],
                    ];
                }
            }
            return response()->json([
                'data' => $temp,
                'error' => false,
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'error' => true,
                'message' => $e->getMessage(),
            ]);
        }
    }
}
