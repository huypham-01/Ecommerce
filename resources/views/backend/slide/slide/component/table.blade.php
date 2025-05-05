<table class="table table-striped table-bordered">
    <thead>
    <tr>
        <th>
            <input type="checkbox" name="checkAll" id="checkAll" class="input-checkbox">
        </th>
        <th>Tên nhóm</th>
        <th>Từ khoá</th>
        <th>Danh sách hình ảnh</th>
        <th class="text-center">Tình trạng</th>
        <th class="text-center">Thao tác</th>
    </tr>
    </thead>
    <tbody>
        @if(!empty($slides) && is_object($slides))
            @foreach ($slides as $slide)
                <tr>
                    <td>
                        <input type="checkbox" name="check" id="" class="input-checkbox checkBoxItem" value="{{ $slide->id }}">
                    </td>
                    <td>{{ $slide->name }}</td>
                    <td>{{ $slide->keyword }}</td>
                    <td class="image-cover">
                        @foreach ($slide->item as $key => $value)
                        @foreach ($value as $item)
                            <span class="image-conver"><img src="{{ $item['image'] }}" alt=""></span>
                        @endforeach
                    @endforeach
                    </td>
                    
                    
                    <td class="text-center js-switch-{{ $slide->id }}"><input value="{{ $slide->publish }}" type="checkbox" class="js-switch status" data-field="publish" data-model="{{ $config['model'] }}" {{ ($slide->publish == 2) ? 'checked' : '' }} data-modelId="{{ $slide->id }}" /></td>
                    <td class="text-center">
                        <a href="{{ route('slide.edit', $slide->id) }}" class="btn btn-success"><i class="fa fa-edit"></i></a>
                        <a href="{{ route('slide.delete', $slide->id) }}" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                    </td>
                </tr>
            @endforeach

        @endif
    </tbody>
</table>
{{ 
    $slides->links('pagination::bootstrap-4')
}}