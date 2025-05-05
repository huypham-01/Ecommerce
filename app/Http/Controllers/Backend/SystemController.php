<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Services\Interfaces\SystemServiceInterface as SystemService;
use App\Repositories\Interfaces\SystemRepositoryInterface as SystemRepository;
use Illuminate\Http\Request;
use App\Classes\System;
use App\Models\Language;

class SystemController extends Controller
{
    protected $systemLibrary;
    protected $systemRepository;
    protected $systemService;
    protected $language;
    public function __construct(
        System $systemLibrary, 
        SystemService $systemService,
        SystemRepository $systemRepository,
    ) {
        $this->systemLibrary = $systemLibrary;
        $this->systemRepository = $systemRepository;
        $this->systemService = $systemService;
        $this->middleware(function($request, $next) {
            $locale = app()->getLocale();
            $language = Language::where('canonical', $locale)->first();
            $this->language = $language->id;
            return $next($request);
        });
    }
    public function index() {
        $systemConfig = $this->systemLibrary->config();
        $systems = convert_array($this->systemRepository->findByCondition([
            ['language_id','=', $this->language]
        ], TRUE), 'keyword', 'content');
        
        $template = 'backend.system.index';
        $config = $this->config();
        $config['seo'] = __('messages.system');
        return view('backend.dashboard.layout', compact(
            'template',
            'systemConfig', 
            'config',
            'systems',
        ));
    }
    public function store(Request $request) {
        if($this->systemService->save($request, $this->language)) {
            return 
                redirect()
                ->route('system.index')
                ->with('success', 'Cập nhật bản ghi thành công');
        }
        return 
            redirect()
            ->route('system.index')
            ->with('error', 'Cập nhật bản ghi thất bại');
    }
    private function config() {
        return [
            'js' => [
                'backend/plugin/ckfinder_2/ckfinder.js',
                'backend/library/finder.js',
                'backend/plugin/ckeditor_4/ckeditor.js',
            ]
        ];
    }
    
}
