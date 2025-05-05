@include('backend.dashboard.component.breadcrumb', ['title' => $config['seo'][$config['method']]['title']])
@include('backend.dashboard.component.formError')
@php
    $url = ($config['method'] == 'create') ? route('product.store') : route('product.update', $product->id);
@endphp
<form action="{{ $url }}" method="POST" class="box">
    @csrf
    <div class="wrapper wrapper-content animated dafeInRight">
        <div class="row">
            <div class="col-lg-9">
                <div class="ibox">
                    <div class="ibox-title">
                        <h4>THÔNG TIN CHUNG</h4>
                    </div>
                    <div class="ibox-content">
                        @include('backend.dashboard.component.content', ['model' => ($product) ?? null])
                    </div>
                </div>
                @include('backend.dashboard.component.ablum', ['model' => ($product) ?? null])
                @include('backend.product.product.component.variant', ($attributeCatalogue) ?? null)
                @include('backend.dashboard.component.seo', ['model' => ($product) ?? null])
            </div>
            <div class="col-lg-3">
                @include('backend.product.product.component.aside')
            </div>
        </div>
        @include('backend.dashboard.component.button')
    </div>
</form>

