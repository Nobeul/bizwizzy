@extends('layouts.app')
@section('title', __('Add new vehicle'))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>@lang('Add new vehicle')</h1>
</section>

<!-- Main content -->
<section class="content">
@php
  $form_class = empty($duplicate_product) ? 'create' : '';
@endphp
{!! Form::open(['url' => action('VehicleController@store'), 'method' => 'post', 'id' => 'product_add_form','class' => 'product_form ' . $form_class, 'files' => true ]) !!}
    @component('components.widget', ['class' => 'box-primary'])
        <div class="row">
          <div class="col-sm-4">
            <div class="form-group">
              {!! Form::label('name', __('Driver Name') . ':*') !!}
              {!! Form::text('name', null, ['class' => 'form-control', 'required', 'placeholder' => __('Driver Name')]); !!}
            </div>
          </div>

          <div class="col-sm-4">
            <div class="form-group">
              {!! Form::label('Acquisition Date', __('Acquisition Date') . ':*') !!}
              {!! Form::text('acquisition_date', null, ['class' => 'form-control', 'required', 'placeholder' => __( 'Acquisition Date'), 'readonly', 'id' => 'user_dob' ]); !!}
            </div>
          </div>

          <div class="col-sm-4">
            <div class="form-group">
              {!! Form::label('VIN/SN', __('VIN/SN') . ':') !!}
              {!! Form::text('vin_sn', null, ['class' => 'form-control', 'required', 'placeholder' => __('VIN/SN')]); !!}
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-sm-4">
            <div class="form-group">
              {!! Form::label('License Plate', __('License Plate') . ':*') !!}
              {!! Form::text('license_plate', null, ['class' => 'form-control', 'required', 'placeholder' => __('License Plate') ]); !!}
            </div>
          </div>

          <div class="col-sm-4">
            <div class="form-group">
              {!! Form::label('Type', __('Type') . ':*') !!}
              {!! Form::text('type', null, ['class' => 'form-control', 'required', 'placeholder' => __('Type') ]); !!}
            </div>
          </div>

          <div class="col-sm-4">
            <div class="form-group">
              {!! Form::label('Year', __('Year') . ':*') !!}
              {!! Form::text('year', null, ['class' => 'form-control', 'required', 'placeholder' => __('Year') ]); !!}
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-sm-4">
            <div class="form-group">
              {!! Form::label('Make', __('Make') . ':*') !!}
              {!! Form::text('make', null, ['class' => 'form-control', 'required', 'placeholder' => __('Make') ]); !!}
            </div>
          </div>

          <div class="col-sm-4">
            <div class="form-group">
              {!! Form::label('Model', __('Model') . ':*') !!}
              {!! Form::text('model', null, ['class' => 'form-control', 'required', 'placeholder' => __('Model') ]); !!}
            </div>
          </div>

          <div class="col-sm-4">
            <div class="form-group">
              {!! Form::label('State', __('Registration State/Province') . ':*') !!}
              {!! Form::text('state', null, ['class' => 'form-control', 'required', 'placeholder' => __('State') ]); !!}
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-sm-4">
            <div class="form-group">
              {!! Form::label('kra_pin_no', __('KRA PIN No') . ':*') !!}
              {!! Form::text('kra_pin_no', null, ['class' => 'form-control', 'required', 'placeholder' => __('KRA PIN No') ]); !!}
            </div>
          </div>

          <div class="col-sm-4">
            <div class="form-group">
              {!! Form::label('ownership', __('Ownership') . ':*') !!}
              {!! Form::text('ownership', null, ['class' => 'form-control', 'required', 'placeholder' => __('Ownership') ]); !!}
            </div>
          </div>

          <div class="col-sm-4">
            <div class="form-group">
              {!! Form::label('id_no', __('ID No') . ':*') !!}
              {!! Form::text('id_no', null, ['class' => 'form-control', 'required', 'placeholder' => __('ID No') ]); !!}
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-sm-4">
            <div class="form-group">
              {!! Form::label('Trim', __('Trim') . ':*') !!}
              {!! Form::textarea('trim', null, ['class' => 'form-control', 'style' => 'resize: none', 'required', 'placeholder' => __('Trim') ]); !!}
            </div>
          </div>
          
          <div class="col-sm-4">
            <div class="form-group">
              {!! Form::label('Photo', __('Photo') . ':') !!}
              {!! Form::file('photo', ['id' => 'upload_image', 'accept' => 'image/*']); !!}
              <small><p class="help-block">@lang('purchase.max_file_size', ['size' => 2])<br> @lang('lang_v1.aspect_ratio_should_be_1_1')</p></small>
            </div>
          </div>
        </div>
    @endcomponent
    <div class="row">
    <div class="col-sm-12">
      <input type="hidden" name="submit_type" id="submit_type">
      <div class="text-center">
      <div class="btn-group">
        <button type="submit" value="submit" class="btn btn-primary submit_product_form">@lang('messages.save')</button>
      </div>
      
      </div>
    </div>
  </div>
{!! Form::close() !!}
  
</section>
<!-- /.content -->

@endsection

@section('javascript')
  @php $asset_v = env('APP_VERSION'); @endphp
  <script src="{{ asset('js/product.js?v=' . $asset_v) }}"></script>

    <script type="text/javascript">
        $(document).ready(function(){
            __page_leave_confirmation('#product_add_form');
            onScan.attachTo(document, {
                suffixKeyCodes: [13], // enter-key expected at the end of a scan
                reactToPaste: true, // Compatibility to built-in scanners in paste-mode (as opposed to keyboard-mode)
                onScan: function(sCode, iQty) {
                    $('input#sku').val(sCode);
                },
                onScanError: function(oDebug) {
                    console.log(oDebug); 
                },
                minLength: 2,
                ignoreIfFocusOn: ['input', '.form-control']
                // onKeyDetect: function(iKeyCode){ // output all potentially relevant key events - great for debugging!
                //     console.log('Pressed: ' + iKeyCode);
                // }
            });
        });
    </script>
@endsection