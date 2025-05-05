@include('backend.dashboard.component.breadcrumb', ['title' => $config['seo'][$config['method']]['title']])@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach 
    </ul>
</div>
    
@endif
@php
    $url = ($config['method'] == 'create') ? route('permission.store') : route('permission.update', $permission->id);
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
                                    <label for="" class="control-label text-left">Tiêu đề <span class="text-danger">(*)</span></label>
                                    <input 
                                        type="text" 
                                        name="name"
                                        value="{{ old('name', ($permission->name) ?? '') }}"
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
                                        value="{{ old('canonical', ($permission->canonical) ?? '') }}"
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

