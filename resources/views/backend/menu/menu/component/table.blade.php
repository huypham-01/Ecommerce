<table class="table table-striped table-bordered">
    <thead>
    <tr>
        <th>
            <input type="checkbox" name="checkAll" id="checkAll" class="input-checkbox">
        </th>
        <th>Tên menu</th>
        <th>Từ khóa</th>
        <th class="text-center">Tình trạng</th>
        <th class="text-center">Thao tác</th>
    </tr>
    </thead>
    <tbody>
        @if(!empty($menuCatalogues) && is_object($menuCatalogues))
            @foreach ($menuCatalogues as $menuCatalogue)
                <tr>
                    <td>
                        <input type="checkbox" name="check" id="" class="input-checkbox checkBoxItem" value="{{ $menuCatalogue->id }}">
                    </td>
                    <td>{{ $menuCatalogue->name }}</td>
                    <td>{{ $menuCatalogue->keyword }}</td>
                    <td class="text-center js-switch-{{ $menuCatalogue->id }}"><input value="{{ $menuCatalogue->publish }}" type="checkbox" class="js-switch status" data-field="publish" data-model="{{ $config['model'] }}" {{ ($menuCatalogue->publish == 2) ? 'checked' : '' }} data-modelId="{{ $menuCatalogue->id }}" /></td>
                    <td class="text-center">
                        <a href="{{ route('menu.edit', $menuCatalogue->id) }}" class="btn btn-success"><i class="fa fa-edit"></i></a>
                        <a href="{{ route('menu.delete', $menuCatalogue->id) }}" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                    </td>
                </tr>
            @endforeach

        @endif
    </tbody>
</table>
{{ 
    $menuCatalogues->links('pagination::bootstrap-4')
}}