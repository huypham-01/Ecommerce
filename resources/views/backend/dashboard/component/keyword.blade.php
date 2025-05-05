<div class="uk-search uk-flex uk-flex-middle mr10">
    <div class="input-group uk-flex">
        <input
            type="text"
            name="keyword"
            value="{{ request('keyword') ?: old('keyword') }}"
            placeholder="Nhập từ khoá tìm kiếm"
            class="form-control"
        >
        <span>
            <button type="submit" name="search" value="search" class="btn btn-primary mb0 btn-sm">Tìm kiếm</button>
        </span>
    </div>
</div>