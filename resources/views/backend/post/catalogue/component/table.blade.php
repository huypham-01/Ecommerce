<table class="table table-striped table-bordered">
    <thead>
    <tr>
        <th style="width:50px">
            <input type="checkbox" name="checkAll" id="checkAll" class="input-checkbox">
        </th>
        <th>{{ __('messages.postCatalogue.table.title') }}</th>
        @include('backend.dashboard.component.languageTh')
        <th class="text-center" style="width:100px">{{ __('messages.tableStatus') }}</th>
        <th class="text-center" style="width:100px">{{ __('messages.tableActive') }}</th>
    </tr>
    </thead>
    <tbody>
        @if(!empty($postcatalogues) && is_object($postcatalogues))
            @foreach ($postcatalogues as $postCatalogue)
                <tr>
                    <td>
                        <input type="checkbox" name="check" id="" class="input-checkbox checkBoxItem" value="{{ $postCatalogue->id }}">
                    </td>
                    <td>
                        {{ str_repeat('|---', (($postCatalogue->level > 0)?($postCatalogue->level - 1):0)).$postCatalogue->name }}
                    </td>
                    @include('backend.dashboard.component.languageTd', ['model' => $postCatalogue, 'modeling' => 'PostCatalogue'])
                    <td class="text-center js-switch-{{ $postCatalogue->id }}">
                        <input value="{{ $postCatalogue->publish }}" type="checkbox" class="js-switch status" data-field="publish" data-model="{{ $config['model'] }}" {{ ($postCatalogue->publish == 2) ? 'checked' : '' }} data-modelId="{{ $postCatalogue->id }}" />
                    </td>
                    <td class="text-center">
                        <a href="{{ route('post.catalogue.edit', $postCatalogue->id) }}" class="btn btn-success"><i class="fa fa-edit"></i></a>
                        <a href="{{ route('post.catalogue.delete', $postCatalogue->id) }}" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                    </td>
                </tr>
            @endforeach
            
        @endif
    </tbody>
</table>