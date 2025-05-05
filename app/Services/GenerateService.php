<?php

namespace App\Services;
use App\Services\Interfaces\GenerateServiceInterface;
use App\Repositories\Interfaces\GenerateRepositoryInterface as GenerateRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
/**
 * Class UserCatalogueService
 * @package App\Services
 */
class GenerateService implements GenerateServiceInterface
{
    protected $generateRepository;
    public function __construct(
        GenerateRepository $generateRepository,
    ) {
        $this->generateRepository = $generateRepository;
    }
    public function paginate($request) {
        $condition['keyword'] = addslashes($request->input('keyword'));
        $condition['publish'] = $request->integer('publish');
        $paePage = $request->integer('perpage');
        $generate = $this->generateRepository->pagination(
            $this->paginteSelect(), 
            $condition, 
            $paePage,
            ['path' => 'generate/index']
        );
        return $generate;
    }
    public function create($request) {
        //DB::beginTransaction();
        try {
            $database = $this->makeDatabase($request);
            $controller = $this->makeController($request);
            $model = $this->makeModel($request);
            $repository = $this->makeRepository($request);
            $service = $this->makeService($request);
            $provider = $this->makeProvider($request);
            $makeRequest = $this->makeRequest($request);
            $view = $this->makeView($request);
            if($request->input('module_type') == 'catalogue' ) {
                $rule = $this->makeRule($request);
            }
            $route =  $this->makeRoute($request);
            die();
            // $this->makeRule();
            // $this->makeLang();


            $payload = $request->except(['_token', 'send']);
            $payload['user_id'] = Auth::id();
            $generate = $this->generateRepository->create($payload);
            DB::commit();
            return true;
        }catch(\Exception $e) {
            DB::rollBack();
            echo $e->getMessage();die();
            return false;
        }
    }
    public function update($id, $request) {
        DB::beginTransaction();
        try {
            $payload = $request->except(['_token', 'send']);
            $generate = $this->generateRepository->update($id,$payload);
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
            $userCatalogue = $this->generateRepository->forceDelete($id);
            DB::commit();
            return true;
        }catch(\Exception $e) {
            DB::rollBack();
            echo $e->getMessage();die();
            return false;
        }
    }
    private function makeDatabase($request) {
        try {
            $payload = $request->only('schema', 'name', 'module_type');
            $module = $this->convertModuleNameToTableName($payload['name']);
            $moduleExtract = explode('_', $module);
            $this->makeMainTable($request, $module, $payload);
            if($payload['module_type'] !== 'difference') {
                $this->makeLanguageTable($request, $module);
                if(count($moduleExtract) == 1) {
                    $this->makeRelationTable($request, $module);
                }            
            }
            ARTISAN::call('migrate');
            return true;
        }catch(\Exception $e) {
            DB::rollBack();
            echo $e->getMessage(); die();
            return false;
        }
    }
    private function makeMainTable($request, $module, $payload) {
        $tableName = $module.'s';
        $migrationFileName = date('Y_m_d_His').'_create_'.$tableName.'_table.php';
        $migrationPath = database_path('migrations/'.$migrationFileName);       
        $migrationTemplate = $this->createMigrateFile($payload['schema'], $tableName);
        FILE::put($migrationPath, $migrationTemplate);
    }
    private function makeLanguageTable($request, $module) {
        $foreinKey = $module.'_id';
        $pivotTableName = $module.'_language';
        $pivotSchema = $this->pivotSchema($module);
        $dropPivotTable = $module.'_language';
        $migrationPivot = $this->createMigrateFile($pivotSchema, $dropPivotTable);
        $migrationPivotFileName = date('Y_m_d_His', time() + 10).'_create_'.$pivotTableName.'_table.php';
        $migrationPivotPath = database_path('migrations/'.$migrationPivotFileName);
        FILE::put($migrationPivotPath, $migrationPivot);
    }
    private function makeRelationTable($request, $module) {
        $moduleExtract = explode('_', $module);
        $tableName = $module.'_catalogue_'.$moduleExtract[0];
        $schema = $this->relationSchema($tableName, $module);
        $migrationRelationFile = $this->createMigrateFile($schema, $tableName);
        $migrationPivotFileName = date('Y_m_d_His', time() + 10).'_create_'.$tableName.'_table.php';
        $migrationPivotPath = database_path('migrations/'.$migrationPivotFileName);
        FILE::put($migrationPivotPath, $migrationRelationFile);
    }
    private function createMigrateFile($schema, $dropPivotTable) {        
        $migrationTemplate = <<<MIGRATION
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        {$schema}
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('{$dropPivotTable}');
    }
};        
MIGRATION;
        return $migrationTemplate;
    }
    private function relationSchema($tableName = '', $module = '',) {
        $schema = <<<SCHEMA
Schema::create('{$tableName}', function (Blueprint \$table) {
            \$table->unsignedBigInteger('{$module}_catalogue_id');
            \$table->unsignedBigInteger('{$module}_id');
            \$table->foreign('{$module}_catalogue_id')->references('id')->on('{$module}_catalogues')->onDelete('cascade');
            \$table->foreign('{$module}_id')->references('id')->on('{$module}s')->onDelete('cascade');
    });
SCHEMA;
        return $schema;
    }
    private function pivotSchema($module) {
        $pivotSchema = <<<SCHEMA
Schema::create('{$module}_language', function (Blueprint \$table) {
            \$table->unsignedBigInteger('{$module}_id');
            \$table->unsignedBigInteger('language_id');
            \$table->foreign('{$module}_id')->references('id')->on('{$module}s')->onDelete('cascade');
            \$table->foreign('language_id')->references('id')->on('languages')->onDelete('cascade');
            \$table->string('name');
            \$table->text('description')->nullable();
            \$table->longText('content')->nullable();
            \$table->string('meta_title')->nullable();
            \$table->string('meta_keyword')->nullable();
            \$table->string('canonical')->unique();
            \$table->text('meta_description')->nullable();
            \$table->timestamps();
    });
SCHEMA;
    return $pivotSchema;
    }
    private function makeController($request) {
        $payload = $request->only('name', 'module_type');
        switch($payload['module_type']) {
            case 'catalogue':
                $this->createTemplateController($payload['name'], 'PostCatalogueController');
                break;
            case 'detail':
                $this->createTemplateController($payload['name'], 'PostController');
                break;
            default:
                //$this->createSingleController();
                
        }
    }
    private function createTemplateController($name, $controllerFile) {
        try {
            $controllerName = $name.'Controller.php';
            $templateControllerPath = base_path('app/Templates/Controller/'.$controllerFile.'.php');
            $module = explode('_', $this->convertModuleNameToTableName($name));
            $controllerContent= file_get_contents($templateControllerPath);
            if(count($module) == 1) {
                $replacement = [
                    'class' => $name,
                    'module' => lcfirst($name),
                ];
            }else{
                $replacement = [
                    'ModuleTemplate' => $name,
                    'moduleTemplate' => lcfirst($name),
                    'foreignKey' => $this->convertModuleNameToTableName($name).'_id',
                    'tableName' => $this->convertModuleNameToTableName($name).'s',
                    'moduleView' => str_replace('_', '.',$this->convertModuleNameToTableName($name)),
                ];
            }
            $newContent = $this->replaceContent($controllerContent, $replacement);
            $controllerPath = base_path('app/Http/Controllers/Backend/'.$controllerName);
            FILE::put($controllerPath, $newContent);
            return true;
        }catch(\Exception $e) {
            echo $e->getMessage();die();
            return false;
        }
    }

    private function makeModel($request) {
        $moduleType = $request->input('module_type');
        $modelName = $request->input('name');
        switch($moduleType) {
            case 'catalogue':
                $this->createCatalogueModel($request, $modelName);
                break;
            case 'detail':
                $this->createModel($request, $modelName);
                break;
            default:
                echo 1;die(); 
        }
    }
    private function createCatalogueModel($request, $modelName) {
        $templateModlePath = base_path('app/Templates/Model/PostCatalogue.php');
        $modelContent= file_get_contents($templateModlePath);
        $module = $this->convertModuleNameToTableName($request->input('name'));
        $extractModule = explode('_', $module);
        $replacement = [
            'class' => ucfirst($extractModule[0]),
            'module' => $extractModule[0],
        ];
        $newContent = $this->replaceContent($modelContent, $replacement);
        $this->createModelFile($modelName, $newContent);
    }
    private function createModel($request, $modelName) {
        $template = base_path('app/Templates/Model/Post.php');
        $content = file_get_contents($template);
        $module = $this->convertModuleNameToTableName($request->input('name'));
        $replacement = [
            '$class' => ucfirst($module),
            '$module' => $module
        ];
        $newContent = $this->replaceContent($content, $replacement);
        $this->createModelFile($modelName, $newContent);
    }
    private function createModelFile($modelName, $modelContent) {
        $modelPath = base_path('app/Models/'.$modelName.'.php');
        FILE::put($modelPath, $modelContent);
    }
    private function makeRepository($request) {
        $name = $request->input('name');
        $module = explode('_', $this->convertModuleNameToTableName($name));
        $repositoryPath = (count($module) == 1) ? base_path('app/Templates/Repositories/PostRepository.php') : base_path('app/Templates/Repositories/PostCatalogueRepository.php');
        $path = [
            'Interfaces' => base_path('app/Templates/repositories/TemplateRepositoryInterface.php'),
            'Respositories' => $repositoryPath
        ];
        $replacement = [
            'class' => ucfirst(current($module)),
            'module' => lcfirst(current($module)),
            'extend' => (count($module) == 2) ? 'Catalogue' : '',
        ];

        foreach($path as $key => $val){
            $content = file_get_contents($val);
            $newContent = $this->replaceContent($content, $replacement);
            $contentPath = ($key == 'Interfaces') ? base_path('app/Repositories/Interfaces/'.$name.'RepositoryInterface.php') : base_path('app/Repositories/'.$name.'Repository.php');
            if(!FILE::exists($contentPath)){
                FILE::put($contentPath, $newContent);
            }
        }
    }
    private function makeService($request) {
        $name = $request->input('name');
        $module = explode('_', $this->convertModuleNameToTableName($name));
        $servicePath = (count($module) == 1) ? base_path('app/Templates/Services/PostService.php') : base_path('app/Templates/Services/PostCatalogueService.php');
        $path = [
            'Interfaces' => base_path('app/Templates/services/TemplateServiceInterface.php'),
            'Services' => $servicePath
        ];
        $replacement = [
            'class' => ucfirst(current($module)),
            'module' => lcfirst(current($module)),
            'extend' => (count($module) == 2) ? 'Catalogue' : '',
        ];

        foreach($path as $key => $val){
            $content = file_get_contents($val);
            $newContent = $this->replaceContent($content, $replacement);
            $contentPath = ($key == 'Interfaces') ? base_path('app/Services/Interfaces/'.$name.'ServiceInterface.php') : base_path('app/Services/'.$name.'Service.php');
            if(!FILE::exists($contentPath)){
                FILE::put($contentPath, $newContent);
            }
        }
    }
    private function makeProvider($request) {
        $name = $request->input('name');
        $provider = [
            'providerPath' => base_path('app/Providers/AppServiceProvider.php'),
            'repositoryProviderPath' => base_path('app/Providers/RepositoryServiceProvider.php')
        ];
        foreach($provider as $key => $val) {
            $content = file_get_contents($val);
            $insertLine = ($key == 'providerPath') ? "'App\\Services\\Interfaces\\{$name}ServiceInterface' => 'App\\Services\\{$name}Service'," : "'App\\Repositories\\Interfaces\\{$name}RepositoryInterface'  => 'App\\Repositories\\{$name}Repository',";
            $position = strpos($content, '];');
            if($position !== false) {
                $newContent = substr_replace($content, "    ".$insertLine."\n".'    ', $position, 0);
            }
            FILE::put($val, $newContent);
        }
    }
    private function makeRequest($request) {
        $name = $request->input('name');
        $requestArray = ['Store'.$name.'Request', 'Update'.$name.'Request', 'Delete'.$name.'Request'];
        $requestTemplate = ['TemplateStoreRequest','TemplateUpdateRequest','TemplateDeleteRequest'];
        if($request->input('module_type') != 'catalogue'){
            unset($requestArray[2]);
            unset($requestTemplate[2]);
        }
        foreach($requestTemplate as $key => $val){
            $requestPath = base_path('app/Templates/requests/'.$val.'.php');
            $requestContent = file_get_contents($requestPath);
            $requestContent = str_replace('{Module}', $name, $requestContent);
            $requestPut = base_path('app/Http/Requests/'.$requestArray[$key].'.php');
            FILE::put($requestPut, $requestContent);
        }
        
    }
    private function makeView($request) {
        try{
            $name = $request->input('name');
            $module = $this->convertModuleNameToTableName($name); 
            $extractModule = explode('_', $module);
            $basePath =  resource_path("views/backend/{$extractModule[0]}");

            $folderPath = (count($extractModule) == 2) ? "$basePath/{$extractModule[1]}" : "$basePath/{$extractModule[0]}";
            $componentPath = "$folderPath/component";
            $this->createDirectory($folderPath);
            $this->createDirectory($componentPath);
            $sourcePath = base_path('app/Templates/views/'.((count($extractModule) == 2) ? 'catalogue' : 'post').'/');
            $viewPath = (count($extractModule) == 2) ? "{$extractModule[0]}.{$extractModule[1]}" : $extractModule[0];
            $replacement = [
                'view' => $viewPath,
                'module' => lcfirst($name),
                'Module' => $name,
            ];
            $fileArray = ['store.blade.php','index.blade.php','delete.blade.php'];
            $componentFile = ['aside.blade.php', 'filter.blade.php','table.blade.php'];
            $this->CopyAndReplaceContent($sourcePath, $folderPath, $fileArray, $replacement);
            $this->CopyAndReplaceContent("{$sourcePath}component/", $componentPath, $componentFile, $replacement);
            return true;
        }catch(\Exception $e ){
            DB::rollBack();
            // Log::error($e->getMessage());
            echo $e->getMessage();die();
            return false;
        }
    }
    private function makeRule($request) {
        $name = $request->input('name');
        $destination = base_path('app/Rules/Check'.$name.'ChildrenRule.php');
        $ruleTemplate = base_path('app/Templates/TemplateRule.php');
        $content = file_get_contents($ruleTemplate);
        $content = str_replace('{Module}', $name, $content);
        if(!FILE::exists($destination)) {
            FILE::put($destination, $content);
        }
    }
    private function makeRoute($request) {
        $name = $request->input('name');
        $module = $this->convertModuleNameToTableName($name);
        $moduleExtract = explode('_', $module);
        $routesPath = base_path('routes/web.php');
        $content = file_get_contents($routesPath);
        $routeUrl = (count($moduleExtract) == 2) ? "{$moduleExtract[0]}/{$moduleExtract[1]}" : $moduleExtract[0];
        $routeName = (count($moduleExtract) == 2) ? "{$moduleExtract[0]}.{$moduleExtract[1]}" : $moduleExtract[0];
        $routeGroup = <<<ROUTE
Route::group(['prefix' => '$routeUrl'], function () {
    Route::get('index', [{$name}Controller::class, 'index'])->name('{$routeName}.index');
    Route::get('create', [{$name}Controller::class, 'create'])->name('{$routeName}.create');
    Route::post('store', [{$name}Controller::class, 'store'])->name('{$routeName}.store');
    Route::get('{id}/edit', [{$name}Controller::class, 'edit'])->where(['id'=>'[0-9]+'])->name('{$routeName}.edit');
    Route::post('{id}/update', [{$name}Controller::class, 'update'])->where(['id'=>'[0-9]+'])->name('{$routeName}.update');
    Route::get('{id}/delete', [{$name}Controller::class, 'delete'])->where(['id'=>'[0-9]+'])->name('{$routeName}.delete');
    Route::delete('{id}/destroy', [{$name}Controller::class, 'destroy'])->where(['id'=>'[0-9]+'])->name('{$routeName}.destroy');
    });
//@@new-module@@
ROUTE;
        $content = str_replace('//@@new-module@@', $routeGroup, $content);
        FILE::put($routesPath, $content);
        
        $useController = <<<ROUTE
use App\Http\Controllers\Backend\\{$name}Controller;
//@@useController@@
ROUTE;
    $content = str_replace('//@@useController@@', $useController, $content);
    FILE::put($routesPath, $content);
    }
    private function convertModuleNameToTableName($name) {
        $temp = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $name));
        return $temp;
    }
    private function createDirectory($path) {
        if(!FILE::exists($path)) {
            File::makeDirectory($path, 0755, true);
        }
    }
    private function copyAndReplaceContent(string $sourcePath, string $destinationPath, array $fileArray, array $replacement) {
        foreach($fileArray as $key => $value) {
            $sourceFile =  $sourcePath.$value;
            $destination = "{$destinationPath}/{$value}";
            $content = file_get_contents($sourceFile);
            foreach($replacement as $keyreplace => $replace) {
                $content = str_replace('{'.$keyreplace.'}', $replace, $content);    
            }
            if(!FILE::exists($destination)) {
                FILE::put($destination, $content);
            }
        }
    }
    private function replaceContent($content, $replacement) {
        $newContent = $content;
        foreach($replacement as $key => $value) {
            $newContent = str_replace('{'.$key.'}', $replacement[$key], $newContent);
        }
        return $newContent;
    }
    private function paginteSelect() {
        return [
            'id',
            'name',
            'schema',
        ];
    }
}
