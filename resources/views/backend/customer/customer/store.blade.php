@include('backend.dashboard.component.breadcrumb', ['title' => $config['seo'][$config['method']]['title']])
@include('backend.dashboard.component.formError')
    
@php
    $url = ($config['method'] == 'create') ? route('customer.store') : route('customer.update', $customer->id);
@endphp
<form action="{{ $url }}" method="post" class="box">
    @csrf
    <div class="wrapper wrapper-content animated dafeInRight">
        <div class="row">
            <div class="col-lg-4">
                <div class="panel-head">
                    <div class="panel-title">Thông tin chung</div>
                    <div class="panel-description">
                        <p>- Nhập thông tin của người dùng</p>
                        <p>- Lưu ý: Thông tin <span class="text-danger">(*)</span> không được để trống</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="ibox-content">
                    <div class="row mb15">
                        <div class="col-lg-6">
                            <div class="form-row">
                                <label for="" class="control-label text-left">Họ và tên <span class="text-danger">(*)</span></label>
                                <input 
                                    type="text" 
                                    name="name"
                                    value="{{ old('name', ($customer->name) ?? '') }}"
                                    class="form-control"
                                    placeholder=""
                                    autocomplete="off"
                                >
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-row">
                                <label for="" class="control-label text-left">Email <span class="text-danger">(*)</span></label>
                                <input 
                                    type="text" 
                                    name="email"
                                    value="{{ old('email', ($customer->email) ?? '') }}"
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
                                <label for="" class="control-label text-left">Nhóm khách hàng<span class="text-danger"> (*)</span></label>
                                <select name="customer_catalogue_id" class="form-control">
                                    <option value="0">[Chọn nhóm khách hàng]</option>
                                    @foreach ($customerCatalogues as $key => $item)
                                        <option {{ 
                                            $item->id == old('customer_catalogue_id', (isset($customer->customer_catalogue_id)) ? $customer->customer_catalogue_id : '') ? 'selected' : '' }} value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-row">
                                <label for="" class="control-label text-left">Nguồn khách hàng<span class="text-danger"> (*)</span></label>
                                <select name="source_id" class="form-control">
                                    <option value="0">[Chọn nguồn khách]</option>
                                    @foreach ($sources as $key => $item)
                                        <option {{ 
                                            $item->id == old('source_id', (isset($customer->source_id)) ? $customer->source_id : '') ? 'selected' : '' }} value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                    </div>
                    <div class="row mb15">
                        <div class="col-lg-6">
                            <div class="form-row">
                                <label for="" class="control-label text-left">Ngày sinh</label>
                                <input 
                                    type="date" 
                                    name="birthday"
                                    value="{{ old('birthday', (isset($customer->birthday)) ? date('Y-m-d', strtotime($customer->birthday)) : '') }}"
                                    class="form-control"
                                    placeholder=""
                                    autocomplete="off"
                                >
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-row">
                                <label for="" class="control-label text-left">Ảnh đại diện</label>
                                <input 
                                    type="text" 
                                    name="image"
                                    value="{{ old('image', ($customer->image) ?? '') }}"
                                    class="form-control upload-image"
                                    placeholder=""
                                    autocomplete="off"
                                    data-type="Images"
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-lg-4">
                <div class="panel-head">
                    <div class="panel-title">Thông tin liên hệ</div>
                    <div class="panel-description">
                        <p>- Nhập thông tin lên hệ của người dùng</p>
                        <p>- Lưu ý: Thông tin <span class="text-danger">(*)</span> không được để trống</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                    <div class="ibox-content">
                        <div class="row mb15">
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Thành phố</label>
                                    <select name="province_id" class="form-control setupSelect2 province location" data-target="districts"> 
                                        <option value="0">[Chọn thành phố]</option>
                                        @if (isset($provinces))
                                            @foreach ($provinces as $province)
                                                <option @if (old('province_id') == $province->code) selected @endif value="{{ $province->code }}">{{ $province->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Quận/Huyện</label>
                                    <select name="district_id" class="form-control districts setupSelect2 location" data-target="wards"> 
                                        <option value="0">[Chọn Quận/Huyện]</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mb15">
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Chọn Phường/Xã</label>
                                    <select name="ward_id" class="form-control setupSelect2 wards"> 
                                        <option value="0">[Chọn Phường/Xã]</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Đia chỉ</label>
                                    <input 
                                        type="text" 
                                        name="address"
                                        value="{{ old('address', ($customer->address) ?? '') }}"
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
                                    <label for="" class="control-label text-left">Số điện thoại<span class="text-danger"> (*)</span></label>
                                    <input 
                                        type="text" 
                                        name="phone"
                                        value="{{ old('phone', ($customer->phone) ?? '') }}"
                                        class="form-control"
                                        placeholder=""
                                        autocomplete="off"
                                    >
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Ghi chú</label>
                                    <input 
                                        type="text" 
                                        name="description"
                                        value="{{ old('description', ($customer->description) ?? '') }}"
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


