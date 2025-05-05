<div class="ibox">
    <div class="ibox-title">
        <h4>CẤU HÌNH NÂNG CAO</h4>
    </div>
    <div class="ibox-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="form-row">
                    <div class="mb15">
                        <select name="publish" class="form-control setupSelect2">
                            @foreach (__('messages.publish') as $key => $value)
                                <option {{ $key == old('publish', (isset($product->publish)) ? $product->publish : '') ? 'selected' : '' }} value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <select name="follow" class="form-control setupSelect2">
                        @foreach (__('messages.follow') as $key => $value)
                            <option {{ $key == old('follow', (isset($product->follow)) ? $product->follow : '') ? 'selected' : '' }} value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>