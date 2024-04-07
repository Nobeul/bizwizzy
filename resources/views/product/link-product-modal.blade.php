@extends('layouts.app')
@section('title', __('Assign Product'))

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>{{ __('Assign Product') }} : <span>{{ $product->name }}</span></h1> 
    </section>

    <!-- Main content -->
    <section class="content">
        {!! Form::open([
            'url' => action('ProductController@updateLinkProduct', [$product->id]),
            'method' => 'POST',
            'id' => 'link_product_form'
        ]) !!}
        @component('components.widget', ['class' => 'box-primary'])
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        {!! Form::label('select_product', __('Select Product')) !!}
                        <select name="select-product" id="select-product" class="form-control select2">
                            <option value="">{{ __('Please Select') }}</option>
                            @foreach ($product_list as $key => $product)
                                <option value="{{ $key }}" data-name="{{ $product }}">{{ $product }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="clearfix"></div>
        @endcomponent

        @component('components.widget', ['class' => 'box-primary'])
            <div class="row">
                <div class="col-sm-12">
                    <table class="table bg-gray product-list-table">
                        <thead>
                            <tr class="bg-green">
                                <th>#</th>
                                <th>{{ __('Child Product Name') }}</th>
                                <th>{{ __('Packaging Quantity') }}</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="link-tbody">

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <button type="submit" class="btn btn-primary pull-right"
                        id="link-product-form-submit">@lang('messages.save')</button>
                </div>
            </div>
        @endcomponent
        {!! Form::close() !!}
    </section>
    <!-- /.content -->
@endsection

@section('javascript')
    <script src="{{ asset('js/product.js?v=' . $asset_v) }}"></script>
    <script type="text/javascript">
        var count = 0;
        $(document).ready(function() {
            __page_leave_confirmation('#link_product_form');
            $("#select-product").on('change', function() {
                var selected_product = parseInt($(this).val());
                if (! isNaN(selected_product)) {
                    var existingRow = false;
                    $('.product-list-table tbody tr').each(function() {
                        if ($(this).find('td:first-child').data('id') == selected_product) {
                            console.log($(this).find('td:first-child').data('id'), selected_product);
                            existingRow = true;
                            let quantity = isNaN(parseFloat($(this).find('td .link-product-quantity').val())) ? 0 : parseFloat($(this).find('td .link-product-quantity').val());
                            $(this).find('td .link-product-quantity').val(++quantity);
                            return false;
                        }
                    });
    
                    if (! existingRow) {
                      count++;
                      var html =
                          `
                            <tr>
                              <td data-id="${$(this).find('option:selected').val()}" data-name="${$(this).find('option:selected').text()}">${count}</td>
                              <td>${$(this).find('option:selected').data("name")}</td>
                              <td>
                                <input type="number" name="quantity[${$(this).val()}]" class="form-control link-product-quantity" value="1" min="1" required/>
                              </td>
                              <td><a href="#" class="delete-link-product" style="color:inherit"><i class="fa fa-trash mt-1"></i></a></td>
                            </tr>
                          `;
                      $('#link-tbody').append(html);
                    }
                }
            });

            $("#link-product-form-submit").on('click', function() {
                validateProductQuantity();
            });

            $("#link_product_form").on('click', ".delete-link-product", function() {
                $(this).closest('tr').remove();
            });

        });
        
        $(document).ready(function() {
            $(document).on('change', '.link-product-quantity', function() {
                validateProductQuantity();
            });
        });

        function validateProductQuantity() {
            $(".link-product-quantity").each(function(index, element) {
                var error_msg_td = $(this).closest('tr').find('.link-product-quantity').closest('td');
                let quantity = parseFloat($(element).val());
                if (isNaN(quantity) || quantity <= 0) {
                    error_msg_td.find('label.error').remove();
                    error_msg_td.append('<label class="error "> Invalid quantity</label>');
                    disable_link_product_form_actions();
                } else {
                    error_msg_td.find('label.error').remove();
                    enable_link_product_form_actions();
                }
            });
        }

        function disable_link_product_form_actions() {
            if (!window.navigator.onLine) {
                return false;
            }
            $('#link-product-form-submit').attr('disabled', 'true');
        }
        
        function enable_link_product_form_actions() {
            $('#link-product-form-submit').removeAttr('disabled');
        }

    </script>
@endsection
