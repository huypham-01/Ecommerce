@csrf
@method('DELETE')
<div class="wrapper wrapper-content animated dafeInRight">
    <div class="row">
        <div class="col-lg-4">
            <div class="panel-head">
                <div class="panel-title">{{ __('messages.generalTitle') }}</div>
                <div class="panel-description">
                    <p>{{ __('messages.generalDescription') }}{{ $model->name }}</p>
                    <p>{{ __('messages.msg') }}</p>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
                <div class="ibox-content">
                    <div class="row mb15">
                        <div class="col-lg-12">
                            <div class="form-row">
                                <label for="" class="control-label text-left">{{ __('messages.model.table.title') }}</label>
                                <input 
                                    type="text" 
                                    name="name"
                                    value="{{ old('name', ($model->name) ?? '') }}"
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
    @include('backend.dashboard.component.button')
</div>