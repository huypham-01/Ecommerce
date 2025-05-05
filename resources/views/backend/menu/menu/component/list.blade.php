<div class="row">
    <div class="col-lg-5">
        <div class="ibox">
            <div class="ibox-content">
                <div class="panel-group" id="accordion">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h5 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" class="collapsed">Liên kết tự tạo</a>
                            </h5>
                        </div>
                        <div id="collapseOne" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                            <div class="panel-body">
                                <div class="panel-title">Tạo Menu: Cài đặt Menu mà bạn muốn hiển thị.</div>
                                <div class="panel-description">
                                    <p><small class="text-danger">* Khi khởi tạo Menu bạn phải chắn chắn rằng đường đãn của menu có hoạt động. Đường dẫn trên website được khởi tạo tại các module: Bài viết, Sản phẩm,...</small></p>
                                    <p><small class="text-danger">* Tiêu đề và đường dẫn của menu không được bỏ trống.</small></p>
                                    <p><small class="text-danger">* Hệ thống chỉ hỗ trợ tối đa 5 cấp menu</small></p>
                                    <a href="#" style="color:black; border-color:rgb(40, 41, 41); display:inline-block !important" title="" class="btn btn-default add-menu m-b m-r right">Thêm đường dẫn</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @foreach (__('module.model') as $key => $val) 
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#{{ $key }}" data-model="{{ $key }}" class="collapsed menu-module" aria-expanded="false">{{ $val }}</a>
                                </h4>
                            </div>
                            <div id="{{ $key }}" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                                <div class="panel-body">
                                    <form action="" method="get" data-model="{{ $key }}" class="search-model">
                                        <div class="form-row">
                                            <input 
                                                type="text"
                                                value=""
                                                class="form-control search-menu"
                                                name="keyword"
                                                placeholder="Nhập 2 ký tự..."
                                            >
                                        </div>
                                    </form>
                                    <div class="menu-list mt20">
                                        

                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="ibox">
            <div class="ibox-content">
                <div class="row">
                    <div class="col-lg-4">
                        <label for="">Tên menu</label>
                    </div>
                    <div class="col-lg-4">
                        <label for="">Đường dẫn</label>
                    </div>
                    <div class="col-lg-2">
                        <label for="">Vị trí</label>
                    </div>
                    <div class="col-lg-2">
                        <label for="">Xoá</label>
                    </div>
                </div>
                <div class="hr-line-dashed" style="margin: 25px 0;"></div>
                <div class="menu-wrapper">
                    @php
                        $menu = old('menu', ($menuList) ?? null);
                    @endphp
                    <div class="notification text-center {{ (is_array($menu) && count($menu)) ? 'none' : '' }}">
                        <h4 style="font-weight:500;font-size:16px;color:black">Danh sách liên kết này chưa có bất kì đường dẫn nào.</h4>
                        <p style="color: rgb(64, 68, 68);margin-top: 10px">Hãy nhấn vào <span style="color: blue">"Thêm đường dẫn"</span> để bắt đầu thêm.</p>
                    </div>
                    @if (is_array($menu) && count($menu))
                        @foreach ($menu['name'] as $key => $val)
                            <div class="row mb10 menu-item">
                                <div class="col-lg-4">
                                    <input 
                                        type="text" 
                                        value="{{ $val }}" 
                                        class="form-control" 
                                        name="menu[name][]"
                                    >
                                </div>
                                <div class="col-lg-4">
                                    <input 
                                        type="text" 
                                        value="{{ $menu['canonical'][$key] }}" 
                                        class="form-control" 
                                        name="menu[canonical][]"
                                    >
                                </div>
                                <div class="col-lg-2">
                                    <input 
                                        type="text" 
                                        value="{{ $menu['order'][$key] }}" 
                                        class="form-control int text-right" 
                                        name="menu[order][]"
                                    >
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-row text-center"><a class="delete-menu"><img src="backend/Close.png"></a>
                                    </div>
                                    <input 
                                        type="text" 
                                        class="hidden" 
                                        name="menu[id][]"
                                        value="{{ $menu['id'][$key] }}"
                                    >
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>