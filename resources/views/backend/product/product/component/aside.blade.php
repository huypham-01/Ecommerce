<div class="ibox">
    <div class="ibox-title">
        <h4>CHỌN DANH MỤC CHA</h4>
    </div>
    <div class="ibox-content">
        <div class="row mb15">
            <div class="col-lg-12">
                <div class="form-row">
                    <span class="text-danger notice">*Chọn Root nếu không có danh mục cha</span>
                    <select name="product_catalogue_id" class="form-control setupSelect2">
                        @foreach ($dropdown as $key => $val)
                            <option {{ $key == old('product_catalogue_id', (isset($product->product_catalogue_id)) ? $product->product_catalogue_id : '') ? 'selected' : '' }} value="{{ $key }}">{{ $val }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        @php
            $catalogue = [];
            if(isset($product)) {
                foreach ($product->product_catalogues as $key => $val) {
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
                            <option @if (is_array(old('catalogue', (isset($catalogue) && count($catalogue)) ? $catalogue : [])) && isset($product->product_catalogue_id) && $key !== $product->product_catalogue_id && in_array($key, old('catalogue', (isset($catalogue)) ? $catalogue : []))) selected @endif value="{{ $key }}">{{ $val }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="ibox">
    <div class="ibox-title">
        <h4>THÔNG TIN CHUNG</h4>
    </div>
    <div class="ibox-content">
        <div class="row mb15">
            <div class="col-lg-12">
                <div class="form-row">
                    <label for="">Mã sản phẩm</label>
                    <input 
                        type="text"
                        name="code"
                        value="{{ old('code',($product->code) ?? null )}}"
                        class="form-control"
                    >
                </div>
            </div>
        </div>
        <div class="row mb15">
            <div class="col-lg-12">
                <div class="form-row">
                    <label for="">Xuất xứ</label>
                    <input 
                        type="text"
                        name="made_in"
                        value="{{ old('made_in',($product->made_in) ?? null )}}"
                        class="form-control"
                    >
                </div>
            </div>
        </div>
        <div class="row mb15">
            <div class="col-lg-12">
                <div class="form-row">
                    <label for="">Giá sản phẩm</label>
                    <input 
                        type="text"
                        name="price"
                        value="{{ old('price', (isset($product)) ? number_format($product->price, 0, ',', '.') : '')}}"
                        class="form-control int"
                    >
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
                        <img src="{{ (old('image', ($product->image) ?? '')) ? old('image', ($product->image) ?? '') : 'backend/img/imgnf.jpeg' }}" alt="">
                    </span>
                    <input type="hidden" name="image" value="{{ old('image', ($product->image) ?? '') }}">
                </div>
            </div>
        </div>
    </div>
</div>
@include('backend.dashboard.component.publish', ['model' => ($product) ?? null])