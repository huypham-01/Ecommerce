<div class="ibox">
    <div class="ibox-title">
        <div class="uk-flex uk-flex-middle uk-flex-space-between">
            <h5>ALBUM ẢNH</h5>
            <div class="upload-album"><a href="" class="upload-picture">Chọn hình</a></div>
        </div>
    </div>
    <div class="ibox-content">
        @php
            $album = (isset($model->album) && is_array($model->album)) ? $model->album : ((!empty($model->album)) ? json_decode($model->album) : []);
            $gallery = (isset($album) && count($album)) ? $album : old('album');
        @endphp
        <div class="row">
            <div class="col-lg-12">
                @if (!isset($gallery) || count($gallery) == 0)
                    <div class="click-to-upload">
                        <div class="icon">
                            <a href="" class="upload-picture">
                                <svg xmlns="http://www.w3.org/2000/svg" style="width: 80px; height: 80px; fill: #d3dbe2; margin-bottom: 10px" class="bi bi-images" viewBox="0 0 16 16">
                                    <path d="M4.502 9a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3"/>
                                    <path d="M14.002 13a2 2 0 0 1-2 2h-10a2 2 0 0 1-2-2V5A2 2 0 0 1 2 3a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v8a2 2 0 0 1-1.998 2M14 2H4a1 1 0 0 0-1 1h9.002a2 2 0 0 1 2 2v7A1 1 0 0 0 15 11V3a1 1 0 0 0-1-1M2.002 4a1 1 0 0 0-1 1v8l2.646-2.354a.5.5 0 0 1 .63-.062l2.66 1.773 3.71-3.71a.5.5 0 0 1 .577-.094l1.777 1.947V5a1 1 0 0 0-1-1h-10"/>
                                </svg>
                            </a>
                        </div>
                        <div class="small-text">Sử dụng nút chọn hình hoặc nhấn vào đây để thêm hình ảnh</div>
                    </div>
                @endif
                <div class="upload-list {{ (isset($gallery) && count($gallery)) ? '' : 'hidden' }}">
                    <ul id="sortable" class="clearfix data-album sortui ui-sortable">
                        @if (isset($gallery) && count($gallery))
                            @foreach ($gallery as $key => $value)
                                <li class="ui-state-default">
                                    <div class="thumb">
                                        <span class="span image img-scaledown">
                                            <img src="{{ $value }}" alt="{{ $value }}">
                                            <input type="hidden" name="album[]" value="{{ $value }}">
                                        </span>
                                        <button class="delete-image"><i class="fa fa-trash"></i></button>
                                    </div>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>