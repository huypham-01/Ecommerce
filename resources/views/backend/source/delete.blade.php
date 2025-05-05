@include('backend.dashboard.component.breadcrumb', ['title' => $config['seo']['create']['title']])

<form action="{{ route('source.destroy', $source->id) }}" method="post" class="box">
    @csrf
    @method('DELETE')
    <div class="wrapper wrapper-content animated dafeInRight">
        <div class="row">
            <div class="col-lg-4">
                <div class="panel-head">
                    <div class="panel-title">Thông tin chung</div>
                    <div class="panel-description">
                        <p>- Bạn đang muốn xoá bản ghi có tên là: {{ $source->name }}</p>
                        <p>- Lưu ý: Xoá không thể khôi phục lại. Bạn có chắc chắn muốn thực hiện thao tác này</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="ibox-content">
                    <div class="row mb15">
                        <div class="col-lg-12">
                            <div class="form-row">
                                <label for="" class="control-label text-left">Tên Widget <span class="text-danger">(*)</span></label>
                                <input 
                                    type="text" 
                                    name="name"
                                    value="{{ old('name', ($source->name) ?? '') }}"
                                    class="form-control"
                                    placeholder=""
                                    autocomplete="off"
                                    readonly
                                >
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="text-right">
            <button class="btn btn-primary button" type="submit" name="delete" value="delete">Quay lại</button>
            <button class="btn btn-danger button" type="submit" name="delete" value="delete">Xoá dữ liệu</button>
        </div>
    </div>
</form>


