@include('backend.dashboard.component.breadcrumb', ['title' => $config['seo'][$config['method']]['title']])
@include('backend.dashboard.component.formError')
    
@php
    $url = ($config['method'] == 'create') ? route('widget.store') : route('widget.update', $widget->id);
@endphp
<form action="{{ $url }}" method="post" class="box">
    @csrf
    <div class="wrapper wrapper-content animated dafeInRight">
        <div class="row">
            <div class="col-lg-9">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Thông tin widget</h5>
                    </div>
                    <div class="ibox-content widgetContent">
                        @include('backend.dashboard.component.content', ['offTitle' => true, 'offContent' => true, 'model' => ($widget) ?? null])
                    </div>
                </div>
                @include('backend.dashboard.component.ablum', ['model' => ($widget) ?? null])
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Cấu hình nội dung widget</h5>
                    </div>
                    <div class="ibox-content model-list">
                        <div class="labelText">Chọn module</div>
                        @foreach (__('module.model') as $key => $val)
                            <div class="model-item uk-flex uk-flex-middle">
                            <input 
                                type="radio"
                                id="{{ $key }}"
                                name="model"
                                class="input-radio"
                                value="{{ $key }}"
                                {{ (old('model', ($widget->model) ?? null) ==  $key) ? 'checked' : '' }}
                            >
                            <label for="{{ $key }}">{{ $val }}</label>
                        </div>
                        @endforeach
                        <div class="search-model-box">
                            <i class="fa fa-search"></i>
                            <input type="text"
                            class="form-control search-model">
                            <div class="ajax-search-result"></div>
                        </div>
                        @php
                            $modelItem = old('modelItem', ($widgetItem) ?? null) 
                        @endphp
                        <div class="search-model-result">
                            @if (!is_null($modelItem) && count($modelItem))
                                @foreach ($modelItem['id'] as $key => $val)
                                    <div class="search-result-item" id="model-{{ $val }}" data-modelid="{{ $val }}">
                                        <div class="uu uk-lex-middle uk-flex-space-between">
                                            <div class="uu uk-flex-middle">
                                                <span class="image img-cover"><img src="{{ $modelItem['image'][$key] }}" alt=""></span>
                                                <span class="name">{{ $modelItem['name'][$key] }}</span>
                                                <div class="hidden">
                                                    <input type="text" name="modelItem[id][]" value="{{ $val }}">
                                                    <input type="text" name="modelItem[name][]" value="{{ $modelItem['name'][$key] }}">
                                                    <input type="text" name="modelItem[image][]" value="{{ $modelItem['image'][$key] }}">
                                                </div>
                                            </div>
                                            <div class="deleted">
                                                <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="20" height="20" viewBox="0 0 50 50"><path d="M 7.71875 6.28125 L 6.28125 7.71875 L 23.5625 25 L 6.28125 42.28125 L 7.71875 43.71875 L 25 26.4375 L 42.28125 43.71875 L 43.71875 42.28125 L 26.4375 25 L 43.71875 7.71875 L 42.28125 6.28125 L 25 23.5625 Z"></path>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                @include('backend.widget.component.aside')
            </div>
        </div>
        @include('backend.dashboard.component.button')
    </div>
</form>


