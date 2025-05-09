@include('backend.dashboard.component.breadcrumb', ['title' => $config['seo'][$config['method']]['title']])
@include('backend.dashboard.component.formError')
    
@php
    $url = ($config['method'] == 'create') ? route('promotion.store') : route('promotion.update', $promotion->id);
@endphp
<form action="{{ $url }}" method="post" class="box">
    @csrf
    <div class="wrapper wrapper-content animated dafeInRight promotion-wrapper">
        <div class="row">
            <div class="col-lg-8">
                @include('backend.promotion.component.general', ['model' => ($promotion) ?? null])
                @include('backend.promotion.promotion.component.detail')
                
            </div>
            @include('backend.promotion.component.aside', ['model' => ($promotion) ?? null])
        </div>
        @include('backend.dashboard.component.button')
    </div>
</form>
@include('backend.promotion.promotion.component.popup')

<input type="hidden" class="preload_promotionMethod" value="{{ old('method', ($promotion->method) ?? null) }}">
<input type="hidden" class="preload_select-product-and-quantity" value="{{ old('module_type', ($promotion->module_type) ?? null) }}">
<input type="hidden" class="input_order_amount_range" value="{{ json_encode(old('promotion_order_amount_range')) }}">
<input type="hidden" class="input_product_and_quantity" value="{{ json_encode(old('product_and_quantity')) }}">
<input type="hidden" class="input_object" value="{{ json_encode(old('object')) }}">

