<div class="ibox source-setting source-normal">
    <div class="ibox-title">
            <h5>Cài đặt cơ bản</h5>
    </div>
    <div class="ibox-content">
        <div class="row mb15">
            <div class="col-lg-12 mb15">
                <div class="form-row">
                    <label for="" class="control-label text-left">Nguồn khách hàng <span class="text-danger">(*)</span></label>
                    <input 
                        type="text" 
                        name="name"
                        value="{{ old('name', ($source->name) ?? '') }}"
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
                        value="{{ old('keyword', ($source->keyword) ?? '') }}"
                        class="form-control"
                        placeholder=""
                        autocomplete="off"
                    >
                </div>
            </div>
        </div>
    </div>
</div>