@extends('layouts.app')
@section('title', __('Mpesa Transactions'))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>{{ __('Mpesa Transactions') }}
        <small>{{ __('Manage your Mpesa Transactions') }}</small>
    </h1>
</section>

<!-- Main content -->
<section class="content">
<div class="row">
    <div class="col-md-12">
    @component('components.filters', ['title' => __('report.filters')])
        <div class="col-md-3">
            <div class="form-group">
                {!! Form::label('cashier', __('Select Cashier') . ':') !!}
                <select name="cashier_id" id="cashier_id" class="form-control select2" placeholder="{{ __('lang_v1.all') }}">
                    <option value="">{{ __('Select Cashier') }}</option>
                    @foreach ($cashiers as $cashier)
                        <option value="{{ $cashier->id }}" {{ request()->cashier_id == $cashier->id ? 'selected' : '' }}>{{ optional($cashier)->surname }} {{ optional($cashier)->first_name }} {{ optional($cashier)->last_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        {{-- <div class="col-md-3">
            <br>
            <div class="form-group">
                {!! Form::select('active_state', ['active' => __('business.is_active'), 'inactive' => __('lang_v1.inactive')], null, ['class' => 'form-control select2', 'style' => 'width:100%', 'id' => 'active_state', 'placeholder' => __('lang_v1.all')]); !!}
            </div>
        </div> --}}
    @endcomponent
    </div>
</div>
@can('vehicles.view')
    <div class="row">
        <div class="col-md-12">
           <!-- Custom Tabs -->
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active">
                        <a href="#product_list_tab" data-toggle="tab" aria-expanded="true"><i class="fa fa-cubes" aria-hidden="true"></i> {{ __('All KRA Transactions') }} </a>
                    </li>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane active" id="product_list_tab">
                        @can('vehicles.create')                            
                            <a class="btn btn-primary pull-right" href="{{action('VehicleController@create')}}">
                                        <i class="fa fa-plus"></i> @lang('messages.add')</a>
                            <br><br>
                        @endcan
                        <table class="table table-bordered table-striped ajax_view hide-footer table nowrap" id="mpesa_transactions" style="width:100%">
                            <thead>
                                <tr>
                                    <th>{{ __('User Name') }}</th> 
                                    <th>{{ __('Transaction ID') }}</th> 
                                    <th>{{ __('Amount') }}</th> 
                                    <th>{{ __('Accepted By Cashier') }}</th> 
                                    <th>{{ __('Status') }}</th> 
                                    <th>{{ __('Date') }}</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endcan
</section>
<!-- /.content -->

@endsection

@section('javascript')
    <script type="text/javascript">
        $(document).ready( function(){
            mpesa_transactions = $('#mpesa_transactions').DataTable({
                processing: true,
                serverSide: true,
                scrollY: "75vh",
                scrollX: true,
                scrollCollapse: true,
                "ajax": {
                    "url": "/mpesa-transaction-list",
                    "data": function ( d ) {
                        d.cashier_id = $('#cashier_id').val();
                        d = __datatable_ajax_callback(d);
                    }
                },
                columnDefs: [ {
                    "orderable": false,
                    "searchable": false
                } ],
                columns: [
                        { data: 'first_name', name: 'first_name', searchable: true },
                        { data: 'transaction_id', name: 'transaction_id', searchable: true },
                        { data: 'transaction_amount', name: 'transaction_amount', searchable: false },
                        { data: 'accepted_by_cashier', name: 'cashier_name', searchable: false },
                        { data: 'status', name: 'status', searchable: true },
                        { data: 'transaction_time', name: 'transaction_time', searchable: false }
                    ],
                    fnDrawCallback: function(oSettings) {
                        __currency_convert_recursively($('#mpesa_transactions'));
                    },
            });

            $(document).on('change', '#cashier_id', 
                function() {
                    mpesa_transactions.ajax.reload();
            });
        });
    </script>
@endsection