@include('backend.dashboard.component.breadcrumb', ['title' => $config['seo'][$config['method']]['title']])
@include('backend.dashboard.component.formError')
@php
    $url = ($config['method'] == 'create') ? route('language.store') : route('language.update', $language->id);
@endphp
<form action="{{ $url }}" method="post" class="box">
    @csrf
    <div class="wrapper wrapper-content animated dafeInRight">
        <div class="row">
            <div class="col-lg-4">
                <div class="panel-head">
                    <div class="panel-title">Thông tin chung</div>
                    <div class="panel-description">
                        <p>- Nhập thông tin của nhóm thành viên</p>
                        <p>- Lưu ý: Thông tin <span class="text-danger">(*)</span> không được để trống</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                    <div class="ibox-content">
                        <div class="row mb15">
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Tên ngôn ngữ <span class="text-danger">(*)</span></label>
                                    <input 
                                        type="text" 
                                        name="name"
                                        value="{{ old('name', ($language->name) ?? '') }}"
                                        class="form-control"
                                        placeholder=""
                                        autocomplete="off"
                                    >
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Canonical <span class="text-danger">(*)</span></label>
                                    <input 
                                        type="text" 
                                        name="canonical"
                                        value="{{ old('canonical', ($language->canonical) ?? '') }}"
                                        class="form-control"
                                        placeholder=""
                                        autocomplete="off"
                                    >
                                </div>
                            </div>
                        </div>
                        <div class="row mb15">
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Ảnh đại diện</label>
                                    <input 
                                        type="text" 
                                        name="image"
                                        value="{{ old('image', ($language->image) ?? '') }}"
                                        class="form-control upload-image"
                                        placeholder=""
                                        autocomplete="off"
                                        data-type="Images"
                                    >
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Ghi chú</label>
                                    <input 
                                        type="text" 
                                        name="description"
                                        value="{{ old('description', ($language->description) ?? '') }}"
                                        class="form-control"
                                        placeholder=""
                                        autocomplete="off"
                                    >
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
        <div class="text-right">
            <button class="btn btn-primary button" type="submit" name="send" value="send" >Lưu lại</button>
        </div>
    </div>
</form>

