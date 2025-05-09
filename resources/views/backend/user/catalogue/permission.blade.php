@include('backend.dashboard.component.breadcrumb', ['title' => $config['seo']['index']['title']])
<form action="{{ route('user.catalogue.updatePermission') }}" method="post" class="box">
    @csrf
    <div class="wrapper wrapper-content animated dafeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox">
                    <div class="ibox-title">
                        <h4>CẤP QUYỀN</h4>
                    </div>
                    <div class="ibox-content">
                        <table class="table table-striped table-bordered">
                            <tr>
                                <th></th>
                                @foreach ($userCatalogues as $userCatalogue)
                                    <th class="text-center">{{ $userCatalogue->name }}</th>
                                @endforeach
                            </tr>
                            @foreach ($permissions as $permission)
                                <tr>
                                    <td><a href="" class="uk-flex uk-flex-middle uk-flex-space-between">{{ $permission->name }} <span style="color: red";>({{ $permission->canonical }})</span></a></td>
                                    @foreach ($userCatalogues as $userCatalogue)
                                        <td>
                                            <input {{ (collect($userCatalogue->permissions)->contains('id', $permission->id)) ? 'checked' : '' }} type="checkbox" name="permission[{{ $userCatalogue->id }}][]" value="{{ $permission->id }}" class="form-control">
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
                
            </div>
            <div class="col-lg-3">
               
            </div>
        </div>
        <div class="text-right mb15 button-fix">
            <button class="btn btn-primary button" type="submit" name="send" value="send">{{ __('messages.saveButton') }}</button>
        </div>
    </div>
</form>