<table class="table table-striped table-bordered">
    <thead>
    <tr>
        <th style="width:50px">
            <input type="checkbox" name="checkAll" id="checkAll" class="input-checkbox">
        </th>
        <th>{{ __('messages.productCatalogue.table.title') }}</th>
        @include('backend.dashboard.component.languageTh')
        <th class="text-center" style="width:100px">{{ __('messages.tableStatus') }}</th>
        <th class="text-center" style="width:100px">{{ __('messages.tableActive') }}</th>
    </tr>
    </thead>
    <tbody>
        @if(!empty($productCatalogues) && is_object($productCatalogues))
            @foreach ($productCatalogues as $productCatalogue)
                <tr>
                    <td>
                        <input type="checkbox" name="check" id="" class="input-checkbox checkBoxItem" value="{{ $productCatalogue->id }}">
                    </td>
                    <td>
                        {{ str_repeat('|---', (($productCatalogue->level > 0)?($productCatalogue->level - 1):0)).$productCatalogue->name }}
                    </td>
                    @include('backend.dashboard.component.languageTd', ['model' => $productCatalogue, 'modeling' => 'ProductCatalogue'])
                    <td class="text-center js-switch-{{ $productCatalogue->id }}">
                        <input value="{{ $productCatalogue->publish }}" type="checkbox" class="js-switch status" data-field="publish" data-model="{{ $config['model'] }}" {{ ($productCatalogue->publish == 2) ? 'checked' : '' }} data-modelId="{{ $productCatalogue->id }}" />
                    </td>
                    <td class="text-center">
                        <a href="{{ route('product.catalogue.edit', $productCatalogue->id) }}" class="btn btn-success"><i class="fa fa-edit"></i></a>
                        <a href="{{ route('product.catalogue.delete', $productCatalogue->id) }}" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                    </td>
                </tr>
            @endforeach
            
        @endif
    </tbody>
</table>