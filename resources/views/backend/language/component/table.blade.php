<table class="table table-striped table-bordered">
    <thead>
    <tr>
        <th>
            <input type="checkbox" name="checkAll" id="checkAll" class="input-checkbox">
        </th>
        <th style="width: 70px">Ảnh</th>
        <th>Ngôn ngữ</th>
        <th class="text-center">Caninical</th>
        <th class="text-center">Ghi chú</th>
        <th class="text-center">Tình trạng</th>
        <th class="text-center">Thao tác</th>
    </tr>
    </thead>
    <tbody>
        @if(!empty($language) && is_object($language))
            @foreach ($language as $language)
                <tr>
                    <td>
                        <input type="checkbox" name="check" id="" class="input-checkbox checkBoxItem" value="{{ $language->id }}">
                    </td>
                    <td><span class="image img-cover"><img src="{{ $language->image }}" alt=""></span></td>
                    <td>{{ $language->name }}</td>
                    <td>{{ $language->canonical }}</td>
                    <td>{{ $language->description }}</td>
                    <td class="text-center js-switch-{{ $language->id }}"><input value="{{ $language->publish }}" type="checkbox" class="js-switch status" data-field="publish" data-model="Language" {{ ($language->publish == 2) ? 'checked' : '' }} data-modelId="{{ $language->id }}" /></td>
                    <td class="text-center">
                        <a href="{{ route('language.edit', $language->id) }}" class="btn btn-success"><i class="fa fa-edit"></i></a>
                        <a href="{{ route('language.delete', $language->id) }}" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                    </td>
                </tr>
            @endforeach
            
        @endif
    </tbody>
</table>