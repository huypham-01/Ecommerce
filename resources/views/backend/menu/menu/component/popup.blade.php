<!-- Modal -->
<div class="modal fade" id="createMenuCatalogue" tabindex="-1" role="dialog" aria-labelledby="createMenuCatalogueTitle" aria-hidden="true">
    <form action="" class="form create-menu-catalogue" method="">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="createMenuCatalogueTitle">Thêm mới vị trí hiển thị của menu</h4>
                    <small class="font-bold">Nhập đầy đủ thông tin để tạo mới vị trí hiển thị của menu</small>
                </div>
                <div class="modal-body">
                    <div class="form-error text-success"></div>
                    <div class="row">
                    <div class="col-lg-12 mb10">
                        <label for="">Tên vị trí hiển thị</label>
                        <input type="text" class="form-control" name="name" value="">
                        <div class="error name"></div>
                    </div>
                    <div class="col-lg-12">
                        <label for="">Từ khoá</label>
                        <input type="text" class="form-control" name="keyword" value="">
                        <div class="error keyword"></div>
                    </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="create" name="create" class="btn btn-primary">Lưu lại</button>
                </div>
            </div>
        </div>
    </form>
</div>
