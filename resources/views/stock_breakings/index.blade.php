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

    </section>
    <!-- /.content -->

@endsection

@section('javascript')
    <script src="{{ asset('js/product.js?v=' . $asset_v) }}"></script>
    <script src="{{ asset('js/opening_stock.js?v=' . $asset_v) }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            stock_breaking_table = $('#stock_breaking_table').DataTable({
                processing: true,
                serverSide: true,
                scrollY: "75vh",
                scrollX: true,
                scrollCollapse: true,
                "ajax": {
                    "url": "/stock-broken-list",
                    "data": function(d) {
                        d = __datatable_ajax_callback(d);
                    }
                },
                columnDefs: [{
                    "orderable": false,
                    "searchable": false
                }],
                columns: [
                    {
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
        });
    </script>
@endsection
