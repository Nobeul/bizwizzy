@extends('layouts.app')
@section('title', __('Mpesa Settings'))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>@lang( 'Mpesa Settings' ) 
        {{-- @show_tooltip(__('lang_v1.types_of_service_help_long')) --}}
    </h1>
</section>

<!-- Main content -->
<section class="content">
    @if (isset($message))
        <h4>{{ $message }}</h4>
    @endif

    @component('components.widget', ['class' => 'box-primary'])
        @slot('tool')
            <div class="box-tools">
                @if (empty($gateway))
                    <button type="button" class="btn btn-block btn-primary btn-modal" 
                        data-href="{{action('MpesaController@create')}}" 
                        data-container=".type_of_service_modal">
                        <i class="fa fa-plus"></i> @lang( 'messages.add' )</button>
                @endif
            </div>
        @endslot
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>{{ "Consumer Key" }}</th>
                        <th>{{ "Consumer Secret" }}</th>
                        <th>{{ "Short code" }}</th>
                        {{-- <th>{{ "Till Number" }}</th> --}}
                        <th>{{ "Status" }}</th>
                        <th>{{ "Action" }}</th>
                    </tr>
                </thead>
                <tbody>
                    @if (!empty($gateway))
                        <tr>
                            <td>{{ $gateway->mpesa_consumerkey }}</td>
                            <td>{{ $gateway->mpesa_consumersecret }}</td>
                            <td>{{ $gateway->mpesa_shortcode }}</td>
                            {{-- <td>{{ $gateway->till_number }}</td> --}}
                            <td>{{ ucfirst($gateway->status) }}</td>
                            <td>
                                <button data-href="{{ url('mpesa-settings', $gateway->business_id) }}" onclick="openEditModel()" class="btn btn-xs btn-primary edit_table_button"><i class="glyphicon glyphicon-edit"></i> Edit</button>
                                {{-- <button data-href="{{ url('mpesa-delete', $gateway->id) }}" class="btn btn-xs btn-danger delete_table_button" id="delete-button"><i class="glyphicon glyphicon-trash"></i> Delete</button> --}}
                            </td>
                        </tr>
                    @else
                        <tr>
                            <td colspan="6" style="text-align:center">No data added yet</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    @endcomponent

    <div class="modal fade type_of_service_modal contains_select2" id="edit-modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
        @if (!empty($gateway))
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    @if (isset($enableMessage))
                        <h4 style="color: red; padding: 50px">
                        {{ $enableMessage }}
                        </h4>
                    @else
                        {!! Form::open(['url' => action('MpesaController@edit', [$gateway->id]), 'method' => 'post', 'id' => 'mpesa_settings_store_form' ]) !!}
                    
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">@lang( 'lang_v1.add_type_of_service' )</h4>
                        </div>
                    
                        <div class="modal-body">
                            <div class="row">
                                <div class="form-group col-md-12">
                                    {!! Form::label('name', __( 'Consumer Key' ) . ':*') !!}
                                    {!! Form::text('mpesa_consumerkey', $gateway->mpesa_consumerkey, ['class' => 'form-control', 'required', 'placeholder' => __( 'Mpesa consumer key here' )]); !!}
                                </div>
                        
                                <div class="form-group col-md-12">
                                    {!! Form::label('name', __( 'Consumer Secret' ) . ':*') !!}
                                    {!! Form::text('mpesa_consumersecret', $gateway->mpesa_consumersecret, ['class' => 'form-control', 'required', 'placeholder' => __( 'Mpesa consumer secret' )]); !!}
                                </div>
            
                                <div class="form-group col-md-12">
                                    {!! Form::label('name', __( 'Mpesa Short Code' ) . ':*') !!}
                                    {!! Form::text('mpesa_shortcode', $gateway->mpesa_shortcode, ['class' => 'form-control', 'required', 'placeholder' => __( 'Mpesa short code' )]); !!}
                                </div>
            
                                {{-- <div class="form-group col-md-12">
                                    {!! Form::label('name', __( 'Mpesa Till Number' ) . ':') !!}
                                    {!! Form::text('till_number', $gateway->till_number, ['class' => 'form-control', 'placeholder' => __( 'Mpesa till number' )]); !!}
                                </div> --}}

                                <div class="form-group col-md-12">
                                    {!! Form::label('name', __( 'Status' ) . ':') !!}
                                    {!! Form::select('status', ['active' => 'Active', 'inactive' => 'Inactive'], $gateway->status, ['class' => 'form-control']) !!}
                                </div>
                            </div>
                        </div>
                    
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">@lang( 'messages.save' )</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">@lang( 'messages.close' )</button>
                        </div>
                    @endif
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        @endif
    </div>
</section>
<!-- /.content -->

@endsection

<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script>
    function openEditModel()
    {
        $('#edit-modal').modal('show');
    }

    $(document).on('click', 'button#delete-button', function() {
        swal({
            title: LANG.sure,
            text: LANG.confirm_delete_tax_rate,
            icon: 'warning',
            buttons: true,
            dangerMode: true,
        }).then(willDelete => {
            if (willDelete) {
                var href = $(this).data('href');
                var data = $(this).serialize();

                $.ajax({
                    method: 'DELETE',
                    url: href,
                    dataType: 'json',
                    data: data,
                    success: function(result) {
                        if (result.success == true) {
                            toastr.success(result.msg);
                            // tax_rates_table.ajax.reload();
                            // tax_groups_table.ajax.reload();
                            location.reload(true);
                        } else {
                            toastr.error(result.msg);
                        }
                    },
                });
            }
        });
    });
</script>
