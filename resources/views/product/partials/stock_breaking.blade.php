<form method="POST" action="{{ action('ProductController@breakStock') }}" id="stock-breaking-form">
    @csrf
    <div class="row" style="margin-top: 20px">
        <div class="col-md-4">
            <div class="form-group">
                {!! Form::label('parent_product', __('Select Parent Product') . ':*') !!}
                {!! Form::select('parent_product_id', $products, null, ['class' => 'form-control select2', 'style' => 'width:100%', 'id' => 'parent_product_id', 'placeholder' => __('Please Select')]); !!}
                <span id="parent_product_id-error" style="display: none; color: red">This field is required</span>
            </div>
        </div>
        @php
            if (isset($business_locations['none'])) {
                unset($business_locations['none']);
            }
        @endphp
        <div class="col-md-4">
            <div class="form-group">
                {!! Form::label('business_location_id',  __('purchase.business_location') . ':*') !!}
                {!! Form::select('business_location_id', $business_locations, null, ['class' => 'form-control select2', 'style' => 'width:100%', 'placeholder' => __('Please Select')]); !!}
                <span id="business_location_id-error" style="display: none; color: red">This field is required</span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                {!! Form::label('child_product', __('Select Child Product') . ':*') !!}
                {!! Form::select('child_product_id', [], null, ['class' => 'form-control select2', 'style' => 'width:100%', 'id' => 'child_product_id', 'placeholder' => __('Please Select')]); !!}
                <span id="child_product_id-error" style="display: none; color: red">This field is required</span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                {!! Form::label('packaging_quantity', __('Available Packaging Quantity') . ':') !!}
                {!! Form::text('packaging_quantity', null, ['class' => 'form-control', 'readonly', 'placeholder' => __('Please Select Child Product'), 'id' => 'packaging_quantity']) !!}

            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                {!! Form::label('breaking_quantity', __('Breaking Quantity') . ':*') !!}
                {!! Form::text('breaking_quantity', null, ['class' => 'form-control', 'placeholder' => __('Please enter quantity to break'), 'id' => 'breaking_quantity']) !!}
                <span id="breaking_quantity-error" style="display: none; color: red">This field is required</span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <button type="submit" class="btn btn-primary pull-right" id="stock-breaking-submit">@lang('messages.save')</button>
        </div>
    </div>
</form>