@include('backend.dashboard.component.breadcrumb', ['title' => $config['seo'][$config['method']]['title']])
@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach 
    </ul>
</div>
@endif
@php
    $url = ($config['method'] == 'create') ? route('post.catalogue.store') : route('post.catalogue.update', $postCatalogue->id);
@endphp
<form action="{{ $url }}" method="post" class="box">
    @csrf
    <div class="wrapper wrapper-content animated dafeInRight">
        <div class="row">
            <div class="col-lg-9">
                <div class="ibox">
                    <div class="ibox-title">
                        <h4>{{ __('messages.tableHeading') }}</h4>
                    </div>
                    <div class="ibox-content">
                        @include('backend.dashboard.component.content', ['model' => ($postCatalogue) ?? null])
                    </div>
                </div>
                @include('backend.dashboard.component.ablum', ['model' => ($postCatalogue) ?? null])
                @include('backend.dashboard.component.seo', ['model' => ($postCatalogue) ?? null])
            </div>
            <div class="col-lg-3">
                @include('backend.post.catalogue.component.aside', ['model' => ($postCatalogue) ?? null])
            </div>
        </div>
        <div class="text-right mb15 button-fix">
            <button class="btn btn-primary button" type="submit" name="send" value="send">{{ __('messages.saveButton') }}</button>
        </div>
    </div>
</form>

