<div class="row mb15">
    <div class="col-lg-12">
        <div class="form-row">
            <label for="" class="control-label text-left">{{ __('messages.title') }} <span class="text-danger">(*)</span></label>
            <input 
                type="text" 
                name="translate_name"
                value="{{ old('translate_name', ($model->name) ?? '') }}"
                class="form-control"
                placeholder=""
                autocomplete="off"
            >
        </div>
    </div>
</div>
<div class="row mb30">
    <div class="col-lg-12">
        <div class="form-row">
            <label for="" class="control-label text-left">{{ __('messages.description') }} <span class="text-danger">(*)</span></label>
            <textarea 
                type="text" 
                name="translate_description"
                class="form-control ck-editor"
                placeholder=""
                autocomplete="off"
                id="ck_Description_1"
                data-height="150"
            >
            {{ old('translate_description', ($model->description) ?? '') }}
            </textarea>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="form-row">
            <div class="uk-flex ul-flex-middle uk-flex-space-between">
                <label for="" class="control-label text-left">{{ __('messages.content') }} <span class="text-danger">(*)</span></label>
                <a href="" class="multipleUploadImageCKeditor" data-target="ckContent_1">{{ __('messages.upload') }}</a>
            </div>
            <textarea 
                type="text" 
                name="translate_content"
                class="form-control ck-editor"
                placeholder=""
                autocomplete="off"
                id="ckContent_1"
                data-height="500"
            >{{ old('translate_content', ($model->content) ?? '') }}</textarea>
        </div>
    </div>
</div>