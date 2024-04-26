@extends('layouts.app')
@section('title', __('Stock Breakings'))

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>{{ __('Stock Breakings') }}
            <small>{{ __('List of stock breakings') }}</small>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                @component('components.filters', ['title' => __('report.filters')])
                    <form method="GET" action="{{ action('ProductController@stockBrokenProductList') }}">
                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::label('location_id', __('purchase.business_location') . ':') !!}
        
                                {!! Form::select('location_id', $business_locations, null, [
                                    'class' => 'form-control select2',
                                    'style' => 'width:100%',
                                    'placeholder' => __('lang_v1.all'),
                                ]) !!}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::label('date_range', __('report.date_range') . ':') !!}
                                {!! Form::text('date_range', null, [
                                    'placeholder' => __('lang_v1.select_a_date_range'),
                                    'class' => 'form-control',
                                    'readonly',
                                ]) !!}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::label('created_by', __('report.user') . ':') !!}
                                {!! Form::select('created_by', $sales_representative, null, [
                                    'class' => 'form-control select2',
                                    'style' => 'width:100%',
                                ]) !!}
                            </div>
                        </div>
                    </form>
                @endcomponent
            </div>
        </div>

        @component('components.widget', ['class' => 'box-primary', 'title' => __( 'lang_v1.all_sales')])
            <div class="row">
                <div class="col-md-12">
                    <!-- Custom Tabs -->
                    <div class="nav-tabs-custom">
                        <div class="tab-content">
                            <div class="tab-pane active" id="product_list_tab">
                                <table class="table table-bordered table-striped ajax_view hide-footer table nowrap"
                                    id="stock_breaking_table" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Parent Product Name') }}</th>
                                            <th>{{ __('Stock Breaking Quantity') }}</th>
                                            <th>{{ __('Child Product Name') }}</th>
                                            <th>{{ __('Child Quantity') }}</th>
                                            <th>{{ __('Location') }}</th>
                                            <th>{{ __('Created At') }}</th>
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
        @endcomponent

    </section>
    <!-- /.content -->

@endsection

@section('javascript')
    <script src="{{ asset('js/product.js?v=' . $asset_v) }}"></script>
    <script src="{{ asset('js/opening_stock.js?v=' . $asset_v) }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {

            //Date range as a button
            $('#date_range').daterangepicker(
                dateRangeSettings,
                function (start, end) {
                    $('#date_range').val(start.format(moment_date_format) + ' ~ ' + end.format(moment_date_format));
                    stock_breaking_table.ajax.reload();
                }
            );
            $('#date_range').on('cancel.daterangepicker', function(ev, picker) {
                $('#date_range').val('');
                stock_breaking_table.ajax.reload();
            });
            
            stock_breaking_table = $('#stock_breaking_table').DataTable({
                processing: true,
                serverSide: true,
                scrollY: "75vh",
                scrollX: true,
                scrollCollapse: true,
                "ajax": {
                    "url": "/stock-broken-list",
                    "data": function(d) {
                        d.location_id = $('#location_id').val();
                        d.created_by = $('#created_by').val();
                        if($('#date_range').val()) {
                            var start = $('#date_range').data('daterangepicker').startDate.format('YYYY-MM-DD');
                            var end = $('#date_range').data('daterangepicker').endDate.format('YYYY-MM-DD');
                            d.start_date = start;
                            d.end_date = end;
                        }
                        d = __datatable_ajax_callback(d);
                    }
                },
                columnDefs: [{
                    "orderable": false,
                    "searchable": false
                }],
                columns: [{
                        data: 'parent_product_name',
                        name: 'parentProduct.name',
                        searchable: true
                    },
                    {
                        data: 'breaking_quantity',
                        name: 'breaking_quantity',
                        searchable: false
                    },
                    {
                        data: 'child_product_name',
                        name: 'assignedProduct.name',
                        searchable: true
                    },
                    {
                        data: 'child_quantity',
                        name: 'child_quantity',
                        searchable: false
                    },
                    {
                        data: 'business_location',
                        name: 'businessLocation.name',
                        searchable: true
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        searchable: false
                    }
                ],
                fnDrawCallback: function(oSettings) {
                    __currency_convert_recursively($('#stock_breaking_table'));
                },
            });

            $(document).on('change', '#location_id, #date_range, #created_by', function() {
                stock_breaking_table.ajax.reload();
            });
        });
    </script>
@endsection
