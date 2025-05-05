<div class="ibox">
    <div class="ibox-title">
        <h5>Thông tin chung</h5>
    </div>
    <div class="ibox-content">
        <div class="row mb15">
            <div class="col-lg-6">
                <div class="form-row">
                    <label for="" class="control-label text-left">Tên chương trình <span class="text-danger">(*)</span></label>
                    <input
                        type="text"
                        name="name"
                        value="{{ old('name', ($model->name) ?? '') }}"
                        class="form-control"
                        placeholder="Nhập vào tên khuyến mãi"
                        autocomplete="off"
                    >
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-row">
                    <label for="" class="control-label text-left">Mã khuyến mại</label>
                    <input
                        type="text"
                        name="code"
                        value="{{ old('code', ($model->code) ?? '') }}"
                        class="form-control"
                        placeholder="Nếu mã khuyến mãi để thống hệ thống sẽ tự tạo"
                        autocomplete="off"
                    >
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="form-row">
                    <label for="" class="control-label text-left">Mô tả</label>
                </div>
                <textarea class="form-control" name="description">{{ old('description', ($model->description) ?? '') }}</textarea>
            </div>
        </div>
    </div>
</div>