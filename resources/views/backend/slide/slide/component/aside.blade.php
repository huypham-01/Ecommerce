<div class="ibox slide-setting slide-normal">
                    <div class="ibox-title">
                         <h5>Cài đặt cơ bản</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="row mb15">
                            <div class="col-lg-12 mb15">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Tên slide <span class="text-danger">(*)</span></label>
                                    <input 
                                        type="text" 
                                        name="name"
                                        value="{{ old('name', ($slide->name) ?? '') }}"
                                        class="form-control"
                                        placeholder=""
                                        autocomplete="off"
                                    >
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Từ khoá <span class="text-danger">(*)</span></label>
                                    <input 
                                        type="text" 
                                        name="keyword"
                                        value="{{ old('keyword', ($slide->keyword) ?? '') }}"
                                        class="form-control"
                                        placeholder=""
                                        autocomplete="off"
                                    >
                                </div>
                            </div>
                        </div>
                        <div class="row mb15">
                            <div class="col-lg-12">
                                <div class="slide-setting">
                                    <div class="setting-item">
                                        <div class="uk-flex uk-flex-middle">
                                            <label for="" class="control-label text-left setting-text">Chiều rộng</label>
                                            <div class="setting-value">
                                                <input 
                                                    type="text"
                                                    name="setting[width]"
                                                    class="form-control int"
                                                    value="{{ old('setting.width', ($slide->setting['width']) ?? null) }}"
                                                >
                                                <span class="px">px</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="slide-setting">
                                    <div class="setting-item">
                                        <div class="uk-flex uk-flex-middle">
                                            <label for="" class="control-label text-left setting-text">Chiều cao</label>
                                            <div class="setting-value">
                                                <input 
                                                    type="text"
                                                    name="setting[height]"
                                                    class="form-control int"
                                                    value="{{ old('setting.height', ($slide->setting['height']) ?? null) }}"
                                                >
                                                <span class="px">px</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="slide-setting">
                                    <div class="setting-item">
                                        <div class="uk-flex uk-flex-middle">
                                            <label for="" class="control-label text-left setting-text">Hiệu ứng</label>
                                            <div class="setting-value">
                                                <select name="setting[animation]" id="" class="form-control">
                                                    @foreach (__('module.effect') as $key => $val)
                                                        <option {{ $key == old('setting.animation', ($slide->setting['animation']) ?? null) ? 'selected' : '' }} value="{{ $key }}">{{ $val }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="slide-setting">
                                    <div class="setting-item">
                                        <div class="uk-flex uk-flex-middle">
                                            <label for="" class="control-label text-left setting-text">Mũi tên</label>
                                            <div class="setting-value">
                                                <input 
                                                    type="checkbox"
                                                    name="setting[arrow]"
                                                    value="accept"
                                                    @if (!old() || old('setting.arrow', ($slide->setting['arrow']) ?? null) == 'accept')
                                                        checked="checked"
                                                    @endif
                                                >
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="slide-setting">
                                    <div class="setting-item">
                                        <div class="uk-flex uk-flex-middle">
                                            <label for="" class="control-label text-left setting-text">Điều hướng </label>
                                            <div class="setting-value">
                                                @foreach (__('module.navigare') as $key => $val)
                                                    <div class="nav-setting-item uk-flex ukflex-middle">
                                                    <input 
                                                        {{ old('setting.navigate') }}
                                                        type="radio" 
                                                        value="{{ $key }}"
                                                        name="setting[navigate]" 
                                                        id="navitate_{{ $key }}"
                                                        {{ old('setting.navigate', (!old()) ? 'dots' : ($slide->setting['navigate']) ?? null) === $key ? 'checked' : '' }}
                                                    >
                                                    <label for="navitate_{{ $key }}">{{ $val }}</label>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="ibox slide-setting slide-advance">
                    <div class="ibox-title uk-flex uk-flex-middle uk-flex-space-between">
                         <h5>Cài đặt nâng cao</h5>
                         <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                         </div>
                    </div>
                    <div class="ibox-content">
                        <div class="setting-item">
                            <div class="uk-flex uk-flex-middle">
                                <label for="" class="control-label text-left setting-text">Tự động chạy</label>
                                <div class="setting-value">
                                    <input 
                                        type="checkbox"
                                        name="setting[autoplay]"
                                        value="accept"
                                        @if (!old() || old('setting.autoplay', ($slide->setting['autoplay']) ?? null) == 'accept')
                                            checked="checked"
                                        @endif
                                    >
                                </div>
                            </div>
                        </div>
                        <div class="setting-item">
                            <div class="uk-flex uk-flex-middle">
                                <label for="" class="control-label text-left setting-text">Dừng khi khi <br>trỏ chuột</label>
                                <div class="setting-value">
                                    <input 
                                        type="checkbox"
                                        name="setting[pauseHover]"
                                        value="accept"
                                        @if (!old() || old('setting.pauseHover', ($slide->setting['autoplay']) ?? null) == 'accept')
                                            checked="checked"
                                        @endif
                                    >
                                </div>
                            </div>
                        </div>
                        <div class="setting-item">
                            <div class="uk-flex uk-flex-middle">
                                <label for="" class="control-label text-left setting-text">Chuyển ảnh</label>
                                <div class="setting-value">
                                    <input 
                                        type="text"
                                        name="setting[animationDelay]"
                                        class="form-control int"
                                        value="{{ old('setting.animationDelay',($slide->setting['animationDelay']) ?? null ) }}"
                                    >
                                    <span class="px">ms</span>
                                </div>
                            </div>
                        </div>
                        <div class="setting-item">
                            <div class="uk-flex uk-flex-middle">
                                <label for="" class="control-label text-left setting-text">Tốc độ <br> hiệu ứng</label>
                                <div class="setting-value">
                                    <input 
                                        type="text"
                                        name="setting[animationSpeed]"
                                        class="form-control int"
                                        value={{ old('setting.animationSpeed', ($slide->setting['animationSpeed']) ?? null) }}
                                    >
                                    <span class="px">ms</span>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
                <div class="ibox short-code">
                    <div class="ibox-title">
                        <h5>Short Code</h5>
                    </div>
                    <div class="ibox-content">
                        <textarea name="short_code" class="textarea form-control">{{ old('short_code', ($slide->short_code) ?? null) }}</textarea>
                    </div>
                </div>