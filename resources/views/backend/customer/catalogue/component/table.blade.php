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
        @if(!empty($customercatalogues) && is_object($customercatalogues))
            @foreach ($customercatalogues as $customercatalogue)
                <tr>
                    <td>
                        <input type="checkbox" name="check" id="" class="input-checkbox checkBoxItem" value="{{ $customercatalogue->id }}">
                    </td>
                    <td>{{ $customercatalogue->name }}</td>
                    <td class="text-center">{{ $customercatalogue->customers_count }} người</td>
                    <td>{{ $customercatalogue->description }}</td>
                    <td class="text-center js-switch-{{ $customercatalogue->id }}"><input value="{{ $customercatalogue->publish }}" type="checkbox" class="js-switch status" data-field="publish" data-model="{{ $config['model'] }}" {{ ($customercatalogue->publish == 2) ? 'checked' : '' }} data-modelId="{{ $customercatalogue->id }}" /></td>
                    <td class="text-center">
                        <a href="{{ route('customer.catalogue.edit', $customercatalogue->id) }}" class="btn btn-success"><i class="fa fa-edit"></i></a>
                        <a href="{{ route('customer.catalogue.delete', $customercatalogue->id) }}" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                    </td>
                </tr>
            @endforeach
            
        @endif
    </tbody>
</table>
{{ 
    $customercatalogues->links('pagination::bootstrap-4')
}}