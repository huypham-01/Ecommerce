<div class="ibox">
    <div class="ibox-title">
        <h4>CHỌN DANH MỤC CHA</h4>
    </div>
    <div class="ibox-content">
        <div class="row mb15">
            <div class="col-lg-12">
                <div class="form-row">
                    <span class="text-danger notice">*Chọn Root nếu không có danh mục cha</span>
                    <select name="{module}_catalogue_id" class="form-control setupSelect2">
                        @foreach ($dropdown as $key => $val)
                            <option {{ $key == old('{module}_catalogue_id', (isset(${module}->{module}_catalogue_id)) ? ${module}->{module}_catalogue_id : '') ? 'selected' : '' }} value="{{ $key }}">{{ $val }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        @php
            $catalogue = [];
            if(isset(${module})) {
                foreach (${module}->{module}_catalogues as $key => $val) {
                    $catalogue[] = $val->id;
                }
            }
        @endphp
        <div class="row">
            <div class="col-lg-12">
                <div class="form-row">
                    <label class="form-lable">Danh mục phụ</label>
                    <select multiple name="catalogue[]" class="form-control setupSelect2">
                        @foreach ($dropdown as $key => $val)
                            <option @if (is_array(old('catalogue', (isset($catalogue) && count($catalogue)) ? $catalogue : [])) && isset(${module}->{module}_catalogue_id) && $key !== ${module}->{module}_catalogue_id && in_array($key, old('catalogue', (isset($catalogue)) ? $catalogue : []))) selected @endif value="{{ $key }}">{{ $val }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="ibox">
    <div class="ibox-title">
        <h4>CHỌN ẢNH ĐẠI DIÊN</h4>
    </div>
    <div class="ibox-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="form-row">
                    <span class="image img-cover image-target">
                        <img src="{{ (old('image', (${module}->image) ?? '')) ? old('image', (${module}->image) ?? '') : 'backend/img/imgnf.jpeg' }}" alt="">
                    </span>
                    <input type="hidden" name="image" value="{{ old('image', (${module}->image) ?? '') }}">
                </div>
            </div>
        </div>
    </div>
</div>
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
                                <option {{ $key == old('publish', (isset(${module}->publish)) ? ${module}->publish : '') ? 'selected' : '' }} value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <select name="follow" class="form-control setupSelect2">
                        @foreach (__('messages.follow') as $key => $value)
                            <option {{ $key == old('follow', (isset(${module}->follow)) ? ${module}->follow : '') ? 'selected' : '' }} value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>