<table class="table table-striped table-bordered">
    <thead>
    <tr>
        <th>
            <input type="checkbox" name="checkAll" id="checkAll" class="input-checkbox">
        </th>
        <th>Tiêu đề</th>
        <th class="text-center">Caninical</th>
        <th class="text-center">Thao tác</th>
    </tr>
    </thead>
    <tbody>
        @if(!empty($permissions) && is_object($permissions))
            @foreach ($permissions as $permission)
                <tr>
                    <td>
                        <input type="checkbox" name="check" id="" class="input-checkbox checkBoxItem" value="{{ $permission->id }}">
                    </td>
                    
                    <td>{{ $permission->name }}</td>
                    <td>{{ $permission->canonical }}</td>
                    
                    <td class="text-center">
                        <a href="{{ route('permission.edit', $permission->id) }}" class="btn btn-success"><i class="fa fa-edit"></i></a>
                        <a href="{{ route('permission.delete', $permission->id) }}" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                    </td>
                </tr>
            @endforeach
            
        @endif
    </tbody>
</table>
{{ 
    $permissions->links('pagination::bootstrap-4')
}}