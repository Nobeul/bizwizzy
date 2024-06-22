<!-- Edit Order tax Modal -->
<div class="modal-dialog modal-lg" role="document">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title">@lang('lang_v1.suspended_sales')</h4>
		</div>
		<div class="modal-body">
			<div class="row">
				<div class="col-md-3">
					<div class="form-group">
						{!! Form::label('sell_list_filter_date_range', __('report.date_range') . ':') !!}
						{!! Form::text('sell_list_filter_date_range', null, ['placeholder' => __('lang_v1.select_a_date_range'), 'class' => 'form-control', 'readonly']); !!}
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						{!! Form::label('created_by',  __('report.user') . ':') !!}
						{!! Form::select('created_by', $sales_representative, null, ['class' => 'form-control select2', 'style' => 'width:100%']); !!}
					</div>
				</div>
			</div>
			<div class="row" id="suspended-list-row">
				@php
					$c = 0;
					$subtype = '';
				@endphp
				@if(!empty($transaction_sub_type))
					@php
						$subtype = '?sub_type='.$transaction_sub_type;
					@endphp
				@endif
				@forelse($sales as $sale)
					@if($sale->is_suspend)
						<div class="col-xs-6 col-sm-3">
							<div class="small-box bg-yellow">
					            <div class="inner text-center">
						            @if(!empty($sale->additional_notes))
						            	<p><i class="fa fa-edit"></i> {{$sale->additional_notes}}</p>
						            @endif
					              <p>{{$sale->invoice_no}}<br>
					              {{@format_date($sale->transaction_date)}}<br>
					              <strong><i class="fa fa-user"></i> {{$sale->name}}</strong></p>
								  <p><strong>Customer Name: {{ $sale->customer_name }}</strong></p>
					              <p><i class="fa fa-cubes"></i>@lang('lang_v1.total_items'): {{count($sale->sell_lines)}}<br>
					              <i class="fas fa-money-bill-alt"></i> @lang('sale.total'): <span class="display_currency" data-currency_symbol=true>{{$sale->final_total}}</span>
					              </p>
					              @if($is_tables_enabled && !empty($sale->table->name))
					              	@lang('restaurant.table'): {{$sale->table->name}}
					              @endif
					              @if($is_service_staff_enabled && !empty($sale->service_staff))
					              	<br>@lang('restaurant.service_staff'): {{$sale->service_staff->user_full_name}}
					              @endif
					            </div>
					            <a href="{{action('SellPosController@edit', ['id' => $sale->id]).$subtype}}" class="small-box-footer bg-blue p-10">
					              @lang('sale.edit_sale') <i class="fa fa-arrow-circle-right"></i>
					            </a>
					            <a href="{{action('SellPosController@destroy', ['id' => $sale->id])}}" class="small-box-footer delete-sale bg-red is_suspended">
					              @lang('messages.delete') <i class="fas fa-trash"></i>
					            </a>
					         </div>
				         </div>
				        @php
				         	$c++;
				        @endphp
					@endif

					@if($c%4==0)
						<div class="clearfix"></div>
					@endif
				@empty
					<p class="text-center">@lang('purchase.no_records_found')</p>
				@endforelse
			</div>
		</div>
		<div class="modal-footer">
		    <button type="button" class="btn btn-default" data-dismiss="modal">@lang('messages.close')</button>
		</div>
	</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->

<script type="text/javascript">
	$(document).ready(function(){
		//Date range as a button
		$('#sell_list_filter_date_range').daterangepicker(
			dateRangeSettings,
			function (start, end) {
				$('#sell_list_filter_date_range').val(start.format(moment_date_format) + ' ~ ' + end.format(moment_date_format));
			}
		);

		$("#created_by").select2();

		$(document).on('change', '#created_by, #sell_list_filter_date_range', function() {
			var created_by = $('#created_by').val();
			var start_date = '';
			var end_date = '';

			if ($('#sell_list_filter_date_range').val()) {
				start_date = $('input#sell_list_filter_date_range').data('daterangepicker').startDate.format('YYYY-MM-DD');
				end_date = $('input#sell_list_filter_date_range').data('daterangepicker').endDate.format('YYYY-MM-DD');
			}
			
			$.ajax({
				method: 'GET',
				url: BASE_URL + '/sells',
				data: {
					suspended_filter: 1,
					suspended: 1,
					created_by: created_by,
					start_date: start_date,
					end_date: end_date
				},
				dataType: 'html',
				success: function(result) {
					if (result) {
						$("#suspended-list-row").empty();
						var appended = $('#suspended-list-row').append(result);
					}
				},
			});
		});
	});
</script>