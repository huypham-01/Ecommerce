<table class="table table-striped table-bordered">
    <thead>
    <tr>
        <th>
            <input type="checkbox" name="checkAll" id="checkAll" class="input-checkbox">
        </th>
        <th>Tên Widget</th>
        <th>Từ khoá</th>
        <th>Model</th>
        <th class="text-center">Tình trạng</th>
        <th class="text-center">Thao tác</th>
    </tr>
    </thead>
    <tbody>
        @if(isset($widgets) && is_object($widgets))
            @foreach ($widgets as $widget)
                <tr>
                    <td>
                        <input type="checkbox" name="check" id="" class="input-checkbox checkBoxItem" value="{{ $widget->id }}">
                    </td>
                    <td>
                        <span class="image img-cover"><img src="{{ $widget->image }}" alt=""></span>
                    </td>
                    <td>{{ $widget->name }}</td>
                    <td>{{ $widget->keyword }}</td>
                    <td>{{ $widget->model }}</td>
                    <td class="text-center js-switch-{{ $widget->id }}"><input value="{{ $widget->publish }}" type="checkbox" class="js-switch status" data-field="publish" data-model="{{ $config['model'] }}" {{ ($widget->publish == 2) ? 'checked' : '' }} data-modelId="{{ $widget->id }}" /></td>
                    <td class="text-center">
                        <a href="{{ route('widget.edit', $widget->id) }}" class="btn btn-success"><i class="fa fa-edit"></i></a>
                        <a href="{{ route('widget.delete', $widget->id) }}" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                    </td>
                </tr>
            @endforeach

        @endif
    </tbody>
</table>
{{ 
    $widgets->links('pagination::bootstrap-4')
}}