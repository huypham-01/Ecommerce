<form action="{{ route('attribute.catalogue.index') }}">
    <div class="filter-wrapper">
        <div class="uk-flex uk-flex-middle uk-flex-space-between">
            <div class="perpage">
                @php
                    $perpage = request('perpage') ?: old('perpage'); 
                @endphp
                <div class="uk-flex uk-flex-middle uk-flex-space-between">
                    <select name="perpage" class="form-control input-sm perpage filter mr10 setupSelect2">
                        @for ($i = 1; $i < 20; $i++)
                            <option {{ ($perpage == $i) ? 'selected' : '' }} value="{{ $i }}">{{ $i }} {{ __('messages.perpage') }}</option>
                        @endfor
                    </select>
                </div>
            </div>
    
            <div class="action">
                <div class="uk-flex uk-flex-middle">
                    @php
                        $publish = request('publish') ?: old('publish');
                    @endphp
                    <select name="publish" class="form-control mr10 setupSelect2">
                        @foreach (__('messages.publish') as $key => $value)
                            <option {{ ($publish == $key) ? 'selected' : '' }} value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                    <div class="uk-search uk-flex uk-flex-middle mr10">
                        <div class="input-group uk-flex">
                            <input
                                type="text"
                                name="keyword"
                                value="{{ request('keyword') ?: old('keyword') }}"
                                placeholder="{{ __('messages.searchInput') }}"
                                class="form-control"
                            >
                            <span>
                                <button type="submit" name="search" value="search" class="btn btn-primary mb0 btn-sm">{{ __('messages.search') }}</button>
                            </span>
                        </div>
                    </div>
                    <a href="{{ route('attribute.catalogue.create') }}" class="btn btn-danger"><i class="fa fa-plus mr5"></i>{{ __('messages.postCatalogue.create.title') }}</a>
                </div>
            </div>
        </div>
    </div>
</form>