@extends('layouts.app')
@section('title', __('Assigned Products'))

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>{{ __('Assigned Products') }}
            <small>{{ __('List of assigned products') }}</small>
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
                                id="product_link_table" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>{{ __('Product Name') }}</th>
                                        <th>{{ __('Assigned To') }}</th>
                                        <th>{{ __('Assigned Quantity') }}</th>
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
            product_link_table = $('#product_link_table').DataTable({
                processing: true,
                serverSide: true,
                scrollY: "75vh",
                scrollX: true,
                scrollCollapse: true,
                "ajax": {
                    "url": window.location.href,
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
                        data: 'product_name',
                        name: 'parentProduct.name',
                        searchable: true
                    },
                    {
                        data: 'assigned_to',
                        name: 'assignedProduct.name',
                        searchable: true
                    },
                    {
                        data: 'quantity',
                        name: 'quantity',
                        searchable: false
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        searchable: false
                    }
                ],
                fnDrawCallback: function(oSettings) {
                    __currency_convert_recursively($('#product_link_table'));
                },
            });
        });
    </script>
@endsection
