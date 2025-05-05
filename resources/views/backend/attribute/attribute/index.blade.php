
@include('backend.dashboard.component.breadcrumb', ['title' => $config['seo']['index']['title']])
<div class="row mb20">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>{{ $config['seo']['index']['table'] }}</h5>
                @include('backend.dashboard.component.toolbox', ['model' => 'Attribute'])
            </div>
            <div class="ibox-content">
                @include('backend.attribute.attribute.component.filter')
                @include('backend.attribute.attribute.component.table')
            </div>
        </div>
    </div>
</div>
{{-- <script>
     $(document).ready(function() {
        var elem = document.querySelector('.js-switch');
        var switchery = new Switchery(elem, { color: '#1AB394' });
     });
</script> --}}