@extends('layouts.app')
@section('title', __('Voided Products'))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>{{ __('Deselected Product Report') }}</h1>
</section>

<!-- Main content -->
<section class="content">
<div class="row">
    <div class="col-md-12">
        @component('components.filters', ['title' => __('report.filters')])
            <div class="col-md-3">
                <div class="form-group">
                    {!! Form::label('type', __('Products') . ':') !!}
                    {!! Form::select('brand_id', $products, null, ['class' => 'form-control select2', 'style' => 'width:100%', 'id' => 'product_id', 'placeholder' => __('lang_v1.all')]); !!}
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    {!! Form::label('type', __('Users') . ':') !!}
                    {!! Form::select('brand_id', $users, null, ['class' => 'form-control select2', 'style' => 'width:100%', 'id' => 'user_id', 'placeholder' => __('lang_v1.all')]); !!}
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    {!! Form::label('type', __('Locations') . ':') !!}
                    {!! Form::select('brand_id', $locations, null, ['class' => 'form-control select2', 'style' => 'width:100%', 'id' => 'location_id', 'placeholder' => __('lang_v1.all')]); !!}
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    {!! Form::label('expense_date_range', __('report.date_range') . ':') !!}
                    {!! Form::text('date_range', null, ['placeholder' => __('lang_v1.select_a_date_range'), 'class' => 'form-control', 'id' => 'deselect_date_range', 'readonly']); !!}
                </div>
            </div>
        @endcomponent
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <!-- Custom Tabs -->
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active">
                    <a href="#product_list_tab" data-toggle="tab" aria-expanded="true"><i class="fa fa-cubes" aria-hidden="true"></i> {{ __('All Deselected Products') }} </a>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane active" id="product_list_tab">
                    <table class="table table-bordered table-striped ajax_view hide-footer table nowrap" id="deselected_product_report_table" style="width:100%">
                        <thead>
                            <tr>
                                <th>{{ 'Product' }}</th> 
                                <th>{{ 'Created By' }}</th> 
                                <th>{{ 'Location Name' }}</th> 
                                <th>{{ 'Quantity' }}</th> 
                                <th>{{ 'Amount' }}</th>
                                <th>{{ 'Created At' }}</th>
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

</section>
<!-- /.content -->

@endsection

@section('javascript')
    <script type="text/javascript">
        $(document).ready( function() {
            deselected_product_report_table = $('#deselected_product_report_table').DataTable({
                processing: true,
                serverSide: true,
                scrollY: "75vh",
                scrollX: true,
                scrollCollapse: true,
                "ajax": {
                    "url": "/deselect-reports",
                    "data": function ( d ) {
                        if($('#deselect_date_range').val()) {
                            var start = $('#deselect_date_range').data('daterangepicker').startDate.format('YYYY-MM-DD');
                            var end = $('#deselect_date_range').data('daterangepicker').endDate.format('YYYY-MM-DD');
                            d.start_date = start;
                            d.end_date = end;
                        }

                        d.product_id = $('#product_id').val();
                        d.user_id = $('#user_id').val();
                        d.business_location_id = $('#location_id').val();
                    }
                },
                columnDefs: [{
                    "orderable": false,
                    "searchable": false
                }],
                columns: [
                        { data: 'product_name', name: 'product_name', searchable: true },
                        { data: 'user_name', name: 'user_name', searchable: true },
                        { data: 'location_name', name: 'location_name', searchable: true },
                        { data: 'quantity', name: 'quantity', searchable: false, sortable: false },
                        { data: 'total_amount', name: 'total_amount', searchable: false, sortable: false },
                        { data: 'created_at', name: 'created_at', searchable: false, sortable: false }
                    ],
                    fnDrawCallback: function(oSettings) {
                        __currency_convert_recursively($('#deselected_product_report_table'));
                    },
            });
        });

        $(document).on('change', '#product_id, #user_id, #location_id', function () {
            deselected_product_report_table.ajax.reload();
        });
    </script>
@endsection