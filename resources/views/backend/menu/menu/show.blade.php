@include('backend.dashboard.component.breadcrumb', ['title' => $config['seo']['show']['title']])
<div class="wapper wapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-4">
            <div class="panel-title">Danh sách Menu</div>
            <div class="panel-description">
                <p>+ Danh sách Menu giúp bạn dễ dàng kiểm soát bố cục Menu. Bạn có thể thêm mới hoặc cập nhật Menu bằng nút <span class="text-success">Cập nhật Menu</span></p>
                <p>+ Bạn có thể thay đổi vị trí hiển thị của menu bằng cách <span class="text-success">menu đến vị trí mong muốn</span> </p>
                <p>+ Dễ dàng khởi tạo khởi tạo menu con bằng cách nhấn vào nút <span class="text-success">Quản lý menu con</span></p>
                <p>+ <span class="text-danger">Hỗ trợ tới danh mục con cấp 5</span></p>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="ibox">
                <div class="ibox-title">
                    <div class="uk-flex uk-flex-middle uk-flex-space-between">
                        <h5 style="margin:0">{{ $menuCatalogue->name }}</h5>
                        <a href="{{ route('menu.editMenu', ['id' => $id]) }}" class="custom-button">Cập nhật Menu cấp 1</a>
                    </div>
                </div>
                <div class="ibox-content" id="dataCatalogue" data-catalogueId="{{ $id }}">
                    @php
                        $menus = recursive($menus);
                        $menuString = recursive_menu($menus);
                    @endphp
                    @if (count($menus))
                        <div class="dd" id="nestable2">
                            <ol class="dd-list">
                                {!! $menuString !!}
                            </ol>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
