<div class="col-lg-4">
    <div class="ibox">
        <div class="ibox-title">
            <h5>Thời gian áp dụng chương trình</h5>
        </div>
        <div class="ibox-content">
            <div class="form-row mb15">
                <label for="" class="control-label text-left">Ngày bắt đầu</label>
                <div class="form-date">
                    <input
                        type="text"
                        name="startDate"
                        value="{{ old('startDate', ($model->startDate) ?? '') }}"
                        class="form-control datapicker"
                        placeholder=""
                        autocomplete="off"
                    >
                    <span><i class="fa fa-calendar"></i></span>
                </div>
            </div>
            <div class="form-row mb15">
                <label for="" class="control-label text-left">Ngày kết thúc</label>
                <div class="form-date">
                    <input
                        type="text"
                        name="endDate"
                        value="{{ old('endDate', ($model->endDate) ?? '') }}"
                        class="form-control datapicker"
                        placeholder=""
                        autocomplete="off"
                        @if (old('neverEndDate', ($model->neverEndDate) ?? null) == 'accept')
                            disabled
                        @endif
                    >
                    <span><i class="fa fa-calendar"></i></span>
                </div>
            </div>
            <div class="form-row">
                <div class="uk-flex uk-flex-middle">
                    <input 
                        type="checkbox"
                        name="neverEndDate"
                        value="accept"
                        class=""
                        id="neverEnd"
                        @if (old('neverEndDate', ($model->neverEndDate) ?? null) == 'accept')
                                checked="checked"
                        @endif
                    >
                    <label class="fix-label ml5" for="neverEnd">Không có ngày kết thúc</label>
                </div>
            </div>
        </div>
    </div>
    <div class="ibox">
        <div class="ibox-title">
            <h5>Nguồn khách áp dụng</h5>
        </div>
        @php
            $sourceStatus = old('source', ($model->sourceStatus) ?? null);
        @endphp
        <div class="ibox-content">
            <div class="setting-value">
                <div class="nav-setting-item uk-flex ukflex-middle">
                    <input 
                        class="chooseSource" 
                        type="radio" 
                        value="all" 
                        name="source" 
                        id="allSource" 
                        {{ (old('source', $model->sourceStatus ?? '') === 'all' || !old('source')) ? 'checked' : '' }}
                    >
                    <label class="fix-label ml5" for="allSource">Áp dụng cho toàn bộ nguồn khách</label>
                </div>
                <div class="nav-setting-item uk-flex ukflex-middle">
                    <input 
                        class="chooseSource" 
                        type="radio" 
                        value="choose" 
                        name="source" 
                        id="chooseSource"
                        {{ (old('source', $model->sourceStatus ?? '') === 'choose') ? 'checked' : '' }}
                    >
                    <label class="fix-label ml5" for="chooseSource">Chọn nguồn khách áp dụng</label>
                </div>
            </div>
            @if ($sourceStatus)
            @php
                $sourceValue = old('sourceValue', ($model->sourceValue) ?? []);
            @endphp
                <div class="source-wrapper">
                    <select name="sourceValue[]" id="" class="multipleSelect2" multiple>
                        @foreach ($sources as $ket => $val)
                            <option 
                                value="{{ $val->id }}" 
                                {{ (in_array($val->id, $sourceValue)) ? 'selected' : '' }}
                            >
                            {{ $val->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
        </div>
    </div>
    <div class="ibox">
        <div class="ibox-title">
            <h5>Đối tượng áp dụng</h5>
        </div>
        <div class="ibox-content">
            <div class="setting-value">
                <div class="nav-setting-item uk-flex ukflex-middle">
                    <input 
                        class="chooseApply" 
                        type="radio" 
                        value="all" 
                        name="applyStatus" 
                        id="allApply"
                        {{ (old('applyStatus', $model->applyStatus ?? '') === 'all' || !old('applyStatus')) ? 'checked' : '' }}
                    >
                    <label class="fix-label ml5" for="allApply">Áp dụng cho toàn bộ đối tượng</label>
                </div>
                <div class="nav-setting-item uk-flex ukflex-middle">
                    <input 
                        class="chooseApply" 
                        type="radio" 
                        value="choose" 
                        name="applyStatus" 
                        id="chooseApply" 
                        {{ (old('applyStatus', $model->applyStatus ?? '') === 'choose') ? 'checked' : '' }}
                    >
                    <label class="fix-label ml5" for="chooseApply">Chọn đối tượng áp dụng</label>
                </div>
            </div>
            @php
                $applyStatus = old('applyStatus', ($model->applyStatus) ?? null);
                $applyValue = old('applyValue', ($model->applyValue) ?? []);
            @endphp
            @if ($applyStatus)
                <div class="apply-wrapper">
                    <select name="applyValue[]" id="" class="multipleSelect2 conditionItem" multiple>
                        @foreach (__('module.applyStatus') as $key => $val)
                            <option 
                                value="{{ $val['id'] }}" 
                                {{ (in_array($val['id'], $applyValue)) ? 'selected' : '' }}
                            >
                            {{ $val['name'] }}</option>
                        @endforeach
                    </select>
                    <div class="wrapper-condition"></div>
                </div>
            @endif
        </div>
    </div>
</div>
<input type="hidden" class="input-product-and-quantity" value="{{ json_encode(__('module.item')) }}">
<input type="hidden" class="applyStatusList" value="{{ json_encode(__('module.applyStatus')) }}">
<input type="hidden" class="conditionItemSelected" value="{{ json_encode($applyValue) }}">
@if (count($applyValue))
    @foreach ($applyValue as $key => $val)
        <input type="hidden" class="condition_input_{{ $val }}" value="{{ json_encode(old($val)) }}">
    @endforeach
@endif