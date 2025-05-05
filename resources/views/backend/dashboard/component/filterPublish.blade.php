@php
    $publish = request('publish') ?: old('publish');
@endphp
<select name="publish" class="form-control mr10 setupSelect2">
    @foreach (__('messages.publish') as $key => $value)
        <option {{ ($publish == $key) ? 'selected' : '' }} value="{{ $key }}">{{ $value }}</option>
    @endforeach
</select>