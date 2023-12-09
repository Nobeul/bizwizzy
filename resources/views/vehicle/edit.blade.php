@extends('layouts.app')
@section('title', __('Edit vehicle'))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>@lang('Edit vehicle')</h1>
</section>

<!-- Main content -->
<section class="content">
@php
  $form_class = empty($duplicate_product) ? 'create' : '';
@endphp
{!! Form::open(['url' => action('VehicleController@update', [$vehicle->id]), 'method' => 'put', 'id' => 'product_add_form','class' => 'product_form ' . $form_class, 'files' => true ]) !!}
    @component('components.widget', ['class' => 'box-primary'])
        <div class="row">
          <div class="col-sm-4">
            <div class="form-group">
              {!! Form::label('name', __('Vehicle Name') . ':*') !!}
              {!! Form::text('name', $vehicle->name, ['class' => 'form-control', 'required', 'placeholder' => __('Vehicle Name')]); !!}
            </div>
          </div>

          <div class="col-sm-4">
            <div class="form-group">
              {!! Form::label('Acquisition Date', __('Acquisition Date') . ':*') !!}
              {!! Form::text('acquisition_date', $vehicle->acquisition_date, ['class' => 'form-control', 'required', 'placeholder' => __( 'Acquisition Date'), 'readonly', 'id' => 'user_dob' ]); !!}
            </div>
          </div>

          <div class="col-sm-4">
            <div class="form-group">
              {!! Form::label('VIN/SN', __('VIN/SN') . ':') !!}
              {!! Form::text('vin_sn', $vehicle->vin_sn, ['class' => 'form-control', 'required', 'placeholder' => __('VIN/SN')]); !!}
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-sm-4">
            <div class="form-group">
              {!! Form::label('License Plate', __('License Plate') . ':*') !!}
              {!! Form::text('license_plate', $vehicle->license_plate, ['class' => 'form-control', 'required', 'placeholder' => __('License Plate') ]); !!}
            </div>
          </div>

          <div class="col-sm-4">
            <div class="form-group">
              {!! Form::label('Type', __('Type') . ':*') !!}
              {!! Form::text('type', $vehicle->type, ['class' => 'form-control', 'required', 'placeholder' => __('Type') ]); !!}
            </div>
          </div>

          <div class="col-sm-4">
            <div class="form-group">
              {!! Form::label('Year', __('Year') . ':*') !!}
              {!! Form::text('year', $vehicle->year, ['class' => 'form-control', 'required', 'placeholder' => __('Year') ]); !!}
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-sm-4">
            <div class="form-group">
              {!! Form::label('Make', __('Make') . ':*') !!}
              {!! Form::text('make', $vehicle->make, ['class' => 'form-control', 'required', 'placeholder' => __('Make') ]); !!}
            </div>
          </div>

          <div class="col-sm-4">
            <div class="form-group">
              {!! Form::label('Model', __('Model') . ':*') !!}
              {!! Form::text('model', $vehicle->model, ['class' => 'form-control', 'required', 'placeholder' => __('Model') ]); !!}
            </div>
          </div>

          <div class="col-sm-4">
            <div class="form-group">
              {!! Form::label('State', __('Registration State/Province') . ':*') !!}
              {!! Form::text('state', $vehicle->state, ['class' => 'form-control', 'required', 'placeholder' => __('State') ]); !!}
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-sm-4">
            <div class="form-group">
              {!! Form::label('Trim', __('Trim') . ':*') !!}
              {!! Form::textarea('trim', $vehicle->trim, ['class' => 'form-control', 'style' => 'resize: none', 'required', 'placeholder' => __('Trim') ]); !!}
            </div>
          </div>
          
          <div class="col-sm-4">
            <div class="form-group">
              {!! Form::label('Photo', __('Photo') . ':') !!}
              {!! Form::file('photo', ['id' => 'upload_image', 'accept' => 'image/*']); !!}
              <small><p class="help-block">@lang('purchase.max_file_size', ['size' => 2])<br> @lang('lang_v1.aspect_ratio_should_be_1_1')</p></small>
            </div>
            @if ($vehicle->photo)
              <img id="previous-image" src="{{ asset('uploads/img/'.$vehicle->photo) }}" width="150" height="125" style="object-fit: cover">
            @endif
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
            $("#upload_image").on('change', function () {
              $("#previous-image").hide();
            });
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