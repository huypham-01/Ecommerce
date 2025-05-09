<div class="ibox">
    <div class="ibox-title">
        <h4>{{ __('messages.seo') }}</h4>
        <div class="ibox-content">
            <div class="seo-container">
                <div class="meta-title">
                    {{ (old('meta_title', ($model->meta_title) ?? '')) ? old('meta_title', ($model->meta_title) ?? '') :  __('messages.seoTitle') }}
                </div>
                <div class="canonical">
                    {{ (old('canonical', ($model->canonical) ?? '')) ? config('app.url').(old('canonical', ($model->canonical) ?? '' )).config('apps.general.suffix') : __('messages.seoCanonical') }}
                </div>
                <div class="meta-description">
                    {{ (old('meta-description', ($model->meta_description) ?? '' )) ? old('meta-description', ($model->meta_description) ?? '' ) : __('messages.seoDescription') }}
                </div>
            </div>
        </div>
        <div class="seo-wrapper">
            <div class="row mb15">
                <div class="col-lg-12">
                    <div class="form-row">
                        <label for="" class="control-label text-left">
                            <div class="uk-flex uk-flex-middle uk-flex-space-between">
                                <span>{{ __('messages.seoMetaTitle') }}</span>
                                <span class="count_meta-title">0 {{ __('messages.characters') }}</span>
                            </div>
                        </label>
                        <input 
                            type="text" 
                            name="translate_meta_title"
                            value="{{ old('translate_meta_title', ($model->meta_title) ?? '') }}"
                            class="form-control"
                            placeholder=""
                            autocomplete="off"
                        >
                    </div>
                </div>
            </div>
            <div class="row mb15">
                <div class="col-lg-12">
                    <div class="form-row">
                        <label for="" class="control-label text-left">
                            <span>{{ __('messages.seoMetaKeyword') }}</span>
                        </label>
                        <input 
                            type="text" 
                            name="translate_meta_keyword"
                            value="{{ old('translate_meta_keyword', ($model->meta_keyword) ?? '') }}"
                            class="form-control"
                            placeholder=""
                            autocomplete="off"
                        >
                    </div>
                </div>
            </div>
            <div class="row mb15">
                <div class="col-lg-12">
                    <div class="form-row">
                        <label for="" class="control-label text-left">
                            <div class="uk-flex uk-flex-middle uk-flex-space-between">
                                <span>{{ __('messages.seoMetaDescription') }}</span>
                                <span class="count_meta-description">0 {{ __('messages.characters') }}</span>
                            </div>
                        </label>
                        <textarea 
                            type="text" 
                            name="translate_meta_description"
                            class="form-control"
                            placeholder=""
                            autocomplete="off"
                        >{{ old('translate_meta_description', ($model->meta_description) ?? '') }}</textarea>
                    </div>
                </div>
            </div>
            <div class="row mb15">
                <div class="col-lg-12">
                    <div class="form-row">
                        <label for="" class="control-label text-left">
                            <span>{{ __('messages.seoMetaCanonical') }} <span class="text-danger">(*)</span></span>
                        </label>
                        <div class="input-wrapper">
                            <input 
                                type="text" 
                                name="translate_canonical"
                                value="{{ old('translate_canonical', ($model->canonical) ?? '') }}"
                                class="form-control seo-canonical"
                                placeholder=""
                                autocomplete="off"
                            >
                            <span class="baseUrl">{{ config('app.url') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>