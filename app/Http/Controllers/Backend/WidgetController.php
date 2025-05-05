<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Interfaces\WidgetServiceInterface as WidgetService;
use App\Repositories\Interfaces\WidgetRepositoryInterface as WidgetRepository;
use App\Http\Requests\StoreWidgetRequest;
use App\Http\Requests\UpdateWidgetRequest;
use App\Models\Language;

class WidgetController extends Controller
{
    //
    protected $widgetService;
    protected $widgetRepository;
    protected $language;
    public function __construct(
        WidgetService $widgetService,
        WidgetRepository $widgetRepository
    ) {
        $this->widgetService = $widgetService;
        $this->widgetRepository = $widgetRepository;

        $this->middleware(function($request, $next) {
            $locale = app()->getLocale();
            $language = Language::where('canonical', $locale)->first();
            $this->language = $language->id;
            return $next($request);
        });
    }
    public function index(Request $request) {
        $this->authorize('modules', 'widget.index');
        $widgets = $this->widgetService->paginate($request);
        $config = $this->config();
        $config['model'] = 'Widget';
        $config['seo'] = __('messages.widget');
        $template = 'backend.widget.index';
        return
            view('backend.dashboard.layout',
            compact('template', 'config', 'widgets'));
    }
    public function create() {
        $this->authorize('modules', 'widget.create');
        $config = $this->config();
        $config['seo'] = __('messages.widget');
        $config['method'] = 'create';
        $template = 'backend.widget.store';
        return
            view('backend.dashboard.layout',
            compact('template', 'config',));
    }
    public function store(
        StoreWidgetRequest $request,
    ) {
        if($this->widgetService->create($request, $this->language)) {
            return 
                redirect()
                ->route('widget.index')
                ->with('success', 'Thêm mới bản ghi thành công');
        }
        return 
            redirect()
            ->route('widget.index')
            ->with('error', 'Thêm mới bản ghi thất bại');
    }
    private function menuItemAgrument( array $whereIn = []) {
        $language = $this->language;
        return [
            'condition' => [],
            'flag' => true,
            'relation' => [
                'languages' => function ($query) use ($language) {
                    $query->where('language_id', $language);
                },
            ],
            'orderBy' => ['id', 'DESC'],
            'param' => [
                'whereIn' => $whereIn,
                'whereInField' => 'id'
            ]
        ];
    }
    public function edit($id) {
        $this->authorize('modules', 'widget.update');
        $widget = $this->widgetRepository->findById($id);
        $widget->description = $widget->description[$this->language];
        $modelClass = loadClass($widget->model);
        $widgetItem = convertArrayByKey($modelClass->findByCondition(
            ...array_values($this->menuItemAgrument($widget->model_id))
        ), ['id', 'name.languages', 'image']);
        $config = $this->config();
        $config['seo'] = __('messages.widget');
        $config['method'] = 'edit';
        $template = 'backend.widget.store';
        return 
            view('backend.dashboard.layout',
            compact('template', 'config','widget', 'widgetItem'));
    }
    public function update($id, UpdateWidgetRequest $request) {
        if($this->widgetService->update($id, $request, $this->language)) {
            return 
                redirect()
                ->route('widget.index')
                ->with('success', 'Cập nhật bản ghi thành công');
        }
        return 
            redirect()
            ->route('widget.index')
            ->with('error', 'Cập nhật bản ghi thất bại');
    }
    public function delete($id) {
        $this->authorize('modules', 'widget.destroy');
        $widget = $this->widgetRepository->findById($id);
        $config['seo'] = __('messages.widget');
        $template = 'backend.widget.delete';
        return
            view('backend.dashboard.layout',
            compact('template', 'config', 'widget'));
    }
    public function destroy($id) {
        if($this->widgetService->destroy($id)) {
            return 
                redirect()
                ->route('widget.index')
                ->with('success', 'Xoá bản ghi thành công');
        }
        return 
            redirect()
            ->route('widget.index')
            ->with('error', 'Xoá bản ghi thất bại. Hãy thử lại');
    }

    private function config() {
        return [
            'js' => [
                'backend/plugin/ckeditor_4/ckeditor.js',
                'backend/plugin/ckfinder_2/ckfinder.js',
                'backend/library/finder.js',
                'backend/library/widget.js',
                'backend/js/plugins/switchery/switchery.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
                
            ],
            'css' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
                'backend/css/plugins/switchery/switchery.css',
            ]
        ];
    }
}
