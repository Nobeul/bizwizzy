<div class="table-responsive">
    <table class="table table-bordered table-striped" id="profit_by_products_table">
        <thead>
            <tr>
                <th>{{ __('Revenue') }}</th>
                <th>@lang('lang_v1.gross_profit')</th>
            </tr>
        </thead>
        <tfoot>
            <tr class="bg-gray font-17 footer-total">
                <td><strong>{{ __('Gross profit') }}:</strong></td>
                <td class="footer_total"></td>
            </tr>
        </tfoot>
    </table>

    <p class="text-muted">
        @lang('lang_v1.profit_note')
    </p>
</div>

<br><br>
<div class="table-responsive clearfix">
    <h4 style="font-weight:bold">Expense Summary</h4>
    <table class="table table-bordered table-striped" id="expense_by_products_table">
        <thead>
            <tr>
                <th>{{ __('Expense Categories') }}</th>
                <th>{{ __('Total Expense') }}</th>
            </tr>
        </thead>
        <tfoot>
            <tr class="bg-gray font-17 footer-total">
                <td><strong>{{ __('Total') }}:</strong></td>
                <td class="footer_total" id="expense-total-pnl"></td>
            </tr>
        </tfoot>
    </table>
</div>