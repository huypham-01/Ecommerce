@include('backend.dashboard.component.breadcrumb', ['title' => $config['seo']['index']['title']])

<form action="{{ route('system.store') }}" method="post" class="box">
    @csrf
    <div class="wrapper wrapper-content animated dafeInRight">
        @foreach ($systemConfig as $key => $val)
        <div class="row">
            <div class="col-lg-4">
                <div class="panel-head">
                    <div class="panel-title">{{ $val['label'] }}</div>
                    <div class="panel-description">
                        {{ $val['description'] }}
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="ibox">
                    @if (count($val['value']))
                    <div class="ibox-content">
                        @foreach ($val['value'] as $keyVal => $item)
                        @php
                            $name = $key.'_'.$keyVal;
                        @endphp
                            <div class="row mb15">
                                <div class="col-lg-12">
                                    <div class="form-row">
                                        <label for="" class="uk-flex uk-flex-space-between">
                                            <span>{{ $item['label'] }}</span>
                                            <span>{!! renderSystemLink($item) !!}</span>
                                        </label>
                                        @switch($item['type'])
                                            @case('text')
                                                {!! renderSystemInput($name, $systems) !!}
                                                @break
                                            @case('images')
                                                {!! renderSystemImages($name, $systems) !!}
                                                @break
                                            @case('textarea')
                                                {!! renderSystemTextarea($name, $systems) !!}
                                                @break
                                            @case('select')
                                                {!! renderSystemSelect($item, $name, $systems) !!}
                                                @break
                                            @case('editor')
                                                {!! renderSystemEditor($name, $systems) !!}
                                                @break
                                            @default
                                                
                                        @endswitch
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
        @include('backend.dashboard.component.button')
    </div>
</form>
