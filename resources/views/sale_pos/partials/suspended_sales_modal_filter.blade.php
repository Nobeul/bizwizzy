@php
    $c = 0;
    $subtype = '';
@endphp
@if (!empty($transaction_sub_type))
    @php
        $subtype = '?sub_type=' . $transaction_sub_type;
    @endphp
@endif
@forelse($sales as $sale)
    @if ($sale->is_suspend)
        <div class="col-xs-6 col-sm-3">
            <div class="small-box bg-yellow">
                <div class="inner text-center">
                    @if (!empty($sale->additional_notes))
                        <p><i class="fa fa-edit"></i> {{ $sale->additional_notes }}</p>
                    @endif
                    <p>{{ $sale->invoice_no }}<br>
                        {{ @format_date($sale->transaction_date) }}<br>
                        <strong><i class="fa fa-user"></i> {{ $sale->name }}</strong>
                    </p>
                    <p><strong>Customer Name: {{ $sale->customer_name }}</strong></p>
                    <p><i class="fa fa-cubes"></i>@lang('lang_v1.total_items'): {{ count($sale->sell_lines) }}<br>
                        <i class="fas fa-money-bill-alt"></i> @lang('sale.total'): <span class="display_currency"
                            data-currency_symbol=true>{{ $sale->final_total }}</span>
                    </p>
                    @if ($is_tables_enabled && !empty($sale->table->name))
                        @lang('restaurant.table'): {{ $sale->table->name }}
                    @endif
                    @if ($is_service_staff_enabled && !empty($sale->service_staff))
                        <br>@lang('restaurant.service_staff'): {{ $sale->service_staff->user_full_name }}
                    @endif
                </div>
                <a href="{{ action('SellPosController@edit', ['id' => $sale->id]) . $subtype }}"
                    class="small-box-footer bg-blue p-10">
                    @lang('sale.edit_sale') <i class="fa fa-arrow-circle-right"></i>
                </a>
                <a href="{{ action('SellPosController@destroy', ['id' => $sale->id]) }}"
                    class="small-box-footer delete-sale bg-red is_suspended">
                    @lang('messages.delete') <i class="fas fa-trash"></i>
                </a>
            </div>
        </div>
        @php
            $c++;
        @endphp
    @endif

    @if ($c % 4 == 0)
        <div class="clearfix"></div>
    @endif
@empty
    <p class="text-center">@lang('purchase.no_records_found')</p>
@endforelse
