<div class="modal-dialog" role="document">
    <div class="modal-content">
        @if (isset($enableMessage))
            <h4 style="color: red; padding: 50px">
              {{ $enableMessage }} <a href="{{ url('/mpesa-request-enable') }}">Click here</a> to enable Mpesa into your system.
            </h4>
        @else
            {!! Form::open(['url' => action('MpesaController@create'), 'method' => 'post', 'id' => 'mpesa_settings_store_form' ]) !!}
        
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">@lang( 'lang_v1.add_type_of_service' )</h4>
            </div>
        
            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-md-12">
                        {!! Form::label('name', __( 'Consumer Key' ) . ':*') !!}
                        {!! Form::text('mpesa_consumerkey', null, ['class' => 'form-control', 'required', 'placeholder' => __( 'Mpesa consumer key here' )]); !!}
                    </div>
              
                    <div class="form-group col-md-12">
                        {!! Form::label('name', __( 'Consumer Secret' ) . ':*') !!}
                        {!! Form::text('mpesa_consumersecret', null, ['class' => 'form-control', 'required', 'placeholder' => __( 'Mpesa consumer secret' )]); !!}
                    </div>

                    <div class="form-group col-md-12">
                        {!! Form::label('name', __( 'Mpesa Short Code' ) . ':*') !!}
                        {!! Form::text('mpesa_shortcode', null, ['class' => 'form-control', 'required', 'placeholder' => __( 'Mpesa short code' )]); !!}
                    </div>

                    <div class="form-group col-md-12">
                        {!! Form::label('name', __( 'Status' ) . ':') !!}
                        {!! Form::select('status', ['active' => 'Active', 'inactive' => 'Inactive'], null, ['class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
        
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">@lang( 'messages.save' )</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">@lang( 'messages.close' )</button>
            </div>
        
            {{-- {!! Form::close() !!} --}}
      
        @endif
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->