<table class="table table-striped table-bordered">
    <thead>
    <tr>
        <th>
            <input type="checkbox" name="checkAll" id="checkAll" class="input-checkbox">
        </th>
        <th style="width: 70px">Ảnh</th>
        <th>Tên Module</th>
        <th class="text-center">Thao tác</th>
    </tr>
    </thead>
    <tbody>
        @if(!empty($generate) && is_object($generate))
            @foreach ($generate as $generate)
                <tr>
                    <td>
                        <input type="checkbox" name="check" id="" class="input-checkbox checkBoxItem" value="{{ $generate->id }}">
                    </td>
                    <td><span class="image img-cover"><img src="{{ $generate->image }}" alt=""></span></td>
                    <td>{{ $generate->name }}</td>
                    <td class="text-center">
                        <a href="{{ route('generate.edit', $generate->id) }}" class="btn btn-success"><i class="fa fa-edit"></i></a>
                        <a href="{{ route('generate.delete', $generate->id) }}" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                    </td>
                </tr>
            @endforeach
            
        @endif
    </tbody>
</table>