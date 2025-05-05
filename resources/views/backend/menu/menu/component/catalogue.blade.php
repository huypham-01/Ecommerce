<div class="row">
    <div class="col-lg-5">
        <div class="panel-head">
            <div class="panel-title">+ Vị trí Menu</div>
            <div class="panel-description">
                <p>+ Website có các vị trí hiển thị cho từng Menu</p>
                <p>+ Lựa chọn vị trí mà bạn muốn hiển thị</p>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="ibox">
            <div class="ibox-content">
                <div class="row">
                    <div class="col-lg-12 mb10">
                        <div class="uk-flex uk-flex-middle uk-flex-space-between">
                            <div class="font-bold">Chọn vị trí hiển thị <span class="text-danger">(*)</span></div>
                            <button 
                                type="button" 
                                name="" 
                                class="createMenuCatalogue btn btn-danger"
                                data-toggle="modal"
                                data-target="#createMenuCatalogue"
                                >Tạo vị trí hiển thị
                            </button>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        @if (count($menuCatalogues))
                            <select class="setupSelect2" name="menu_catalogue_id" id="">
                                <option value="0">[Chọn vị trí hiển thị]</option>
                                @foreach ($menuCatalogues as $key => $value)
                                    <option {{ (isset($menuCatalogue) && $menuCatalogue->id == $value->id) ? 'selected' : '' }} value="{{ $value->id }}">{{ $value->name }}</option>
                                @endforeach
                            
                        </select>
                        @endif
                    </div>
                    {{-- <div class="col-lg-6">
                        <select class="setupSelect2" name="type" id="">
                            <option value="none">[Chọn kiểu menu]</option>
                            @foreach (__('module.type') as $key => $value)
                                <option value="{{ $menuCatalogue->id }}">{{ $menuCatalogue->name }}</option>
                            @endforeach
                        </select>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
</div>