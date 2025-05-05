@include('backend.dashboard.component.breadcrumb', ['title' => $config['seo'][$config['method']]['title']])
@include('backend.dashboard.component.formError')
@php
    $url = ($config['method'] == 'create') ? route('generate.store') : route('generate.update', $generate->id);
@endphp
<form action="{{ $url }}" method="post" class="box">
    @csrf
    <div class="wrapper wrapper-content animated dafeInRight">
        <div class="row mb15">
            <div class="col-lg-4">
                <div class="panel-head">
                    <div class="panel-title">Thông tin chung</div>
                    <div class="panel-description">
                        <p>- Nhập tên Module</p>
                        <p>- Lưu ý: Thông tin <span class="text-danger">(*)</span> không được để trống</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                    <div class="ibox-content">
                        <div class="row mb15">
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Tên Module <span class="text-danger">(*)</span></label>
                                    <input 
                                        type="text" 
                                        name="name"
                                        value="{{ old('name', ($generate->name) ?? '') }}"
                                        class="form-control"
                                        placeholder=""
                                        autocomplete="off"
                                    >
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Tên chức năng <span class="text-danger">(*)</span></label>
                                    <input 
                                        type="text" 
                                        name="module"
                                        value="{{ old('module', ($generate->module) ?? '') }}"
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
                                    <label for="" class="control-label text-left">Loại Module <span class="text-danger">(*)</span></label>
                                    <select name="module_type" id="" class="setupSelect2 form-control">
                                        <option value="0">Chọn loại Module</option>
                                        <option value="catalogue">Module danh mục</option>
                                        <option value="detail">Module chi tiết</option>
                                        <option value="difference">Module Khác</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Đường dẫn <span class="text-danger">(*)</span></label>
                                    <input 
                                        type="text" 
                                        name="path"
                                        value="{{ old('path', ($generate->path) ?? '') }}"
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
        <div class="row">
            <div class="col-lg-4">
                <div class="panel-head">
                    <div class="panel-title">Thông tin về schema</div>
                    <div class="panel-description">
                        <p>- Schema là tên các trường của đối tượng nằm trong cơ sở dữ liệu</p>
                        <p>- Schema sẽ có dạng sau: </p>
                        <p>Schema::create('Tên bảng thuộc tính', function (Blueprint $table) {</p>
                        <p>    $table->Tên trường;</p>
                        <p>});</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                    <div class="ibox-content">
                        <div class="row mb15">
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Schema <span class="text-danger">(*)</span></label>
                                    <textarea 
                                        name="schema"
                                        value="{{ old('schema', ($generate->schema) ?? '') }}"
                                        class="form-control schema"
                                    ></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
        <div class="text-right">
            <button class="btn btn-primary button" type="submit" name="send" value="send" >Thêm mới</button>
        </div>
    </div>
</form>

