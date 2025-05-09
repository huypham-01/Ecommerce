<table class="table table-striped table-bordered">
    <thead>
    <tr>
        <th style="width:50px">
            <input type="checkbox" name="checkAll" id="checkAll" class="input-checkbox">
        </th>
        <th>{{ __('messages.attributeCatalogue.table.title') }}</th>
        @include('backend.dashboard.component.languageTh')
        <th class="text-center" style="width:100px">{{ __('messages.tableStatus') }}</th>
        <th class="text-center" style="width:100px">{{ __('messages.tableActive') }}</th>
    </tr>
    </thead>
    <tbody>
        @if(!empty($attributeCatalogues) && is_object($attributeCatalogues))
            @foreach ($attributeCatalogues as $attributeCatalogue)
                <tr>
                    <td>
                        <input type="checkbox" name="check" id="" class="input-checkbox checkBoxItem" value="{{ $attributeCatalogue->id }}">
                    </td>
                    <td>
                        {{ str_repeat('|---', (($attributeCatalogue->level > 0)?($attributeCatalogue->level - 1):0)).$attributeCatalogue->name }}
                    </td>
                    @include('backend.dashboard.component.languageTd', ['model' => $attributeCatalogue, 'modeling' => 'AttributeCatalogue'])
                    <td class="text-center js-switch-{{ $attributeCatalogue->id }}">
                        <input value="{{ $attributeCatalogue->publish }}" type="checkbox" class="js-switch status" data-field="publish" data-model="{{ $config['model'] }}" {{ ($attributeCatalogue->publish == 2) ? 'checked' : '' }} data-modelId="{{ $attributeCatalogue->id }}" />
                    </td>
                    <td class="text-center">
                        <a href="{{ route('attribute.catalogue.edit', $attributeCatalogue->id) }}" class="btn btn-success"><i class="fa fa-edit"></i></a>
                        <a href="{{ route('attribute.catalogue.delete', $attributeCatalogue->id) }}" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                    </td>
                </tr>
            @endforeach
            
        @endif
    </tbody>
</table>