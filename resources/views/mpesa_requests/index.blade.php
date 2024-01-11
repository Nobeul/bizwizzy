@extends('layouts.app')
@section('title', __('Mpesa Request List'))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>@lang( 'Mpesa Requests' ) 
        {{-- @show_tooltip(__('lang_v1.types_of_service_help_long')) --}}
    </h1>
</section>

<!-- Main content -->
<section class="content">
    @if (isset($message))
        <h4>{{ $message }}</h4>
    @endif

    @component('components.widget', ['class' => 'box-primary'])
        {{-- @slot('tool')
            <div class="box-tools">
                @if (empty($gateway))
                    <button type="button" class="btn btn-block btn-primary btn-modal" 
                        data-href="{{action('MpesaController@create')}}" 
                        data-container=".type_of_service_modal">
                        <i class="fa fa-plus"></i> @lang( 'messages.add' )</button>
                @endif
            </div>
        @endslot --}}
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>{{ "S/L" }}</th>
                        <th>{{ "Customer Name" }}</th>
                        <th>{{ "Transaction Number" }}</th>
                        <th>{{ "Business Name" }}</th>
                        <th>{{ "Status" }}</th>
                        <th>{{ "Action" }}</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($requests) > 0)
                        @foreach ($requests as $key => $request)
                            <tr>
                                <td>{{ ++$key }}</td>
                                <td>{{ $request->first_name }} {{ $request->last_name }}</td>
                                <td>{{ $request->transaction_number }}</td>
                                <td>{{ optional($request->business)->name }}</td>
                                <td>{{ ucfirst($request->status) }}</td>
                                <td>
                                    <a href="{{ url('mpesa-request', optional($request->business)->id) }}" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> Edit</a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6" style="text-align:center">No data added yet</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    @endcomponent
</section>
<!-- /.content -->

@endsection

<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script>
   
</script>
