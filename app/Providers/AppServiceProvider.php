<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use DateTime;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public $serviceBingdings = [
        'App\Services\Interfaces\UserServiceInterface'            => 'App\Services\UserService',

        'App\Services\Interfaces\UserCatalogueServiceInterface'            => 'App\Services\UserCatalogueService',
        'App\Services\Interfaces\CustomerServiceInterface'            => 'App\Services\CustomerService',

        'App\Services\Interfaces\CustomerCatalogueServiceInterface'            => 'App\Services\CustomerCatalogueService',

        'App\Services\Interfaces\LanguageServiceInterface'            => 'App\Services\LanguageService',

        'App\Services\Interfaces\GenerateServiceInterface'            => 'App\Services\GenerateService',

        'App\Services\Interfaces\PermissionServiceInterface'            => 'App\Services\PermissionService',

        'App\Services\Interfaces\PostCatalogueServiceInterface'            => 'App\Services\PostCatalogueService',

        'App\Services\Interfaces\PostServiceInterface'            => 'App\Services\PostService',

        'App\Services\Interfaces\MenuServiceInterface'            => 'App\Services\MenuService',
        'App\Services\Interfaces\MenuCatalogueServiceInterface'            => 'App\Services\MenuCatalogueService',
        'App\Services\Interfaces\SlideServiceInterface'            => 'App\Services\SlideService',
        'App\Services\Interfaces\WidgetServiceInterface'            => 'App\Services\WidgetService',
        'App\Services\Interfaces\PromotionServiceInterface'            => 'App\Services\PromotionService',
        'App\Services\Interfaces\SourceServiceInterface'            => 'App\Services\SourceService',
        
        
        'App\Services\Interfaces\ProductCatalogueServiceInterface' => 'App\Services\ProductCatalogueService',
        'App\Services\Interfaces\ProductServiceInterface' => 'App\Services\ProductService',
        'App\Services\Interfaces\AttributeCatalogueServiceInterface' => 'App\Services\AttributeCatalogueService',
        'App\Services\Interfaces\AttributeServiceInterface' => 'App\Services\AttributeService',
        'App\Services\Interfaces\SystemServiceInterface' => 'App\Services\SystemService',
        
    ];
    public function register(): void
    {
        foreach($this->serviceBingdings as $key => $value){
            $this->app->bind($key, $value);
        }
        $this->app->register(RepositoryServiceProvider::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Validator::extend('custome_date_format', function ($attributre, $value, $parameters, $validator) {
            return DateTime::createFromFormat('Y-m-d H:i:s', $value) != false;
        });
        Validator::extend('custome_after', function ($attributre, $value, $parameters, $validator) {
            $starDate = Carbon::createFromFormat('Y-m-d H:i:s', $validator->getData()[$parameters[0]]);
            $endDate = Carbon::createFromFormat('Y-m-d H:i:s', $value);
            return $endDate->greaterThan($starDate) !== false;
        });
        Schema::defaultStringLength(191);
    }
}
