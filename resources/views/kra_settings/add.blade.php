@extends('layouts.app')
@section('title', __('KRA Settings'))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>{{ __('KRA Settings') }}</h1>
</section>

<!-- Main content -->
<section class="content">
    @if (isset($message))
        <h4>{{ $message }}</h4>
    @endif

    @component('components.widget', ['class' => 'box-primary'])
    <div class="row col-md-8">
        <form class="form-group" action="{{ url('kra-settings') }}" method="post">
            @csrf
            <div class="form-group row">
                <label class="col-md-4 col-form-label">{{ __('Cash Endpoint') }}</label>
                <div class="col-md-8">
                    <input class="form-control" style="padding: 5px;border-radius: 10px;" type="text" name="cash_endpoint" value="{{ optional($settings)->cash_endpoint }}" placeholder="Enter cash endoint here" required>
                    @error('cash_endpoint')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <br>

            <div class="form-group row">
                <label class="col-md-4 col-form-label">{{ __('Invoice Endpoint') }}</label>
                <div class="col-md-8">
                    <input class="form-control" style="padding: 5px;border-radius: 10px;" type="text" name="invoice_endpoint" value="{{ optional($settings)->invoice_endpoint }}" placeholder="Enter invoice endoint here" required>
                </div>
            </div>
            <br>

            <div class="form-group row">
                <label class="col-md-4 col-form-label">{{ __('Sell Return Endpoint') }}</label>
                <div class="col-md-8">
                    <input class="form-control" style="padding: 5px;border-radius: 10px;" type="text" name="sell_return_endpoint" value="{{ optional($settings)->sell_return_endpoint }}" placeholder="Enter sell return endoint here" required>
                </div>
            </div>
            <br>

            <div class="form-group row">
                <label class="col-md-4 col-form-label">{{ __('Token') }}</label>
                <div class="col-md-8">
                    <input class="form-control" style="padding: 5px;border-radius: 10px;" type="text" name="token" value="{{ optional($settings)->token }}" placeholder="Enter token here" required>
                </div>
            </div>
            <br>
            
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
    @endcomponent
</section>
<!-- /.content -->

@endsection