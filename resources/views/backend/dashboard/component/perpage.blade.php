<div class="perpage">
    @php
        $perpage = request('perpage') ?: old('perpage'); 
    @endphp
    <div class="uk-flex uk-flex-middle uk-flex-space-between">
        <select name="perpage" class="form-control input-sm perpage filter mr10 setupSelect2">
            @for ($i = 1; $i < 50; $i++)
                <option {{ ($perpage == $i) ? 'selected' : '' }} value="{{ $i }}">{{ $i }} bản ghi</option>
            @endfor
        </select>
    </div>
</div>