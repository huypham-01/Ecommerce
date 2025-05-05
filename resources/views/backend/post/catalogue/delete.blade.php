@include('backend.dashboard.component.breadcrumb', ['title' => $config['seo'][$config['method']]['title']])
@include('backend.dashboard.component.formError')
<form action="{{ route('post.catalogue.destroy', $postCatalogue->id) }}" method="post" class="box">
    @csrf
    @method('DELETE')
    <div class="wrapper wrapper-content animated dafeInRight">
        <div class="row">
            <div class="col-lg-4">
                <div class="panel-head">
                    <div class="panel-title">{{ __('messages.generalTitle') }}</div>
                    <div class="panel-description">
                        <p>{{ __('messages.generalDescription') }}{{ $postCatalogue->name }}</p>
                        <p>{{ __('messages.msg') }}</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                    <div class="ibox-content">
                        <div class="row mb15">
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">{{ __('messages.postCatalogue.table.title') }}</label>
                                    <input 
                                        type="text" 
                                        name="name"
                                        value="{{ old('name', ($postCatalogue->name) ?? '') }}"
                                        class="form-control"
                                        placeholder=""
                                        autocomplete="off"
                                        readonly
                                    >
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
        <hr>
        <div class="text-right">
            <button class="btn btn-primary button" type="submit" name="delete" value="delete">Quay lại</button>
            <button class="btn btn-danger button" type="submit" name="delete" value="delete">{{ __('messages.deleteButton') }}</button>
        </div>
    </div>
</form>


