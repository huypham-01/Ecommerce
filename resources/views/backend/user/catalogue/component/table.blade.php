<table class="table table-striped table-bordered">
    <thead>
    <tr>
        <th>
            <input type="checkbox" name="checkAll" id="checkAll" class="input-checkbox">
        </th>
        <th>Tên nhóm thành viên</th>
        <th class="text-center">Số thành viên</th>
        <th>Mô tả</th>
        <th class="text-center">Tình trạng</th>
        <th class="text-center">Thao tác</th>
    </tr>
    </thead>
    <tbody>
        @if(!empty($usercatalogues) && is_object($usercatalogues))
            @foreach ($usercatalogues as $usercatalogue)
                <tr>
                    <td>
                        <input type="checkbox" name="check" id="" class="input-checkbox checkBoxItem" value="{{ $usercatalogue->id }}">
                    </td>
                    <td>{{ $usercatalogue->name }}</td>
                    <td class="text-center">{{ $usercatalogue->users_count }} người</td>
                    <td>{{ $usercatalogue->description }}</td>
                    <td class="text-center js-switch-{{ $usercatalogue->id }}"><input value="{{ $usercatalogue->publish }}" type="checkbox" class="js-switch status" data-field="publish" data-model="{{ $config['model'] }}" {{ ($usercatalogue->publish == 2) ? 'checked' : '' }} data-modelId="{{ $usercatalogue->id }}" /></td>
                    <td class="text-center">
                        <a href="{{ route('user.catalogue.edit', $usercatalogue->id) }}" class="btn btn-success"><i class="fa fa-edit"></i></a>
                        <a href="{{ route('user.catalogue.delete', $usercatalogue->id) }}" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                    </td>
                </tr>
            @endforeach
            
        @endif
    </tbody>
</table>
{{ 
    $usercatalogues->links('pagination::bootstrap-4')
}}