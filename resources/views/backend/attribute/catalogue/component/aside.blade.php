<div class="ibox">
    <div class="ibox-title">
        <h4>{{ __('messages.parent') }}</h4>
    </div>
    <div class="ibox-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="form-row">
                    <span class="text-danger notice">*{{ __('messages.parentNotice') }}</span>
                    <select name="parent_id" class="form-control setupSelect2">
                        @foreach ($dropdown as $key => $val)
                            <option {{ $key == old('parent_id', (isset($attributeCatalogue->parent_id)) ? $attributeCatalogue->parent_id : '') ? 'selected' : '' }} value="{{ $key }}">{{ $val }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="ibox">
    <div class="ibox-title">
        <h4>{{ __('messages.image') }}</h4>
    </div>
    <div class="ibox-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="form-row">
                    <span class="image img-cover image-target">
                        <img src="{{ (old('image', ($attributeCatalogue->image) ?? '')) ? old('image', ($attributeCatalogue->image) ?? '') : 'backend/img/imgnf.jpeg' }}" alt="">
                    </span>
                    <input type="hidden" name="image" value="{{ old('image', ($attributeCatalogue->image) ?? '') }}">
                </div>
            </div>
        </div>
    </div>
</div>
<div class="ibox">
    <div class="ibox-title">
        <h4>{{ __('messages.advange') }}</h4>
    </div>
    <div class="ibox-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="form-row">
                    <div class="mb15">
                        <select name="publish" class="form-control setupSelect2">
                            @foreach ( __('messages.publish') as $key => $value)
                                <option {{ $key == old('publish', (isset($attributeCatalogue->publish)) ? $attributeCatalogue->publish : '') ? 'selected' : '' }} value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <select name="follow" class="form-control setupSelect2">
                        @foreach ( __('messages.follow') as $key => $value)
                            <option {{ $key == old('follow', (isset($postCatalogue->follow)) ? $postCatalogue->follow : '') ? 'selected' : '' }} value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>