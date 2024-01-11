@extends('layouts.app')
@section('title', __('Mpesa Settings'))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>@lang( 'Mpesa Request' ) 
        {{-- @show_tooltip(__('lang_v1.types_of_service_help_long')) --}}
    </h1>
</section>

<!-- Main content -->
<section class="content">
    
    @component('components.widget', ['class' => 'box-primary'])
        <div class="row col-md-8">
            <form class="form-group" action="" method="post" enctype="multipart/form-data">
                @csrf
                <div class="form-group row">
                    <label class="col-md-4 col-form-label">First Name</label>
                    <div class="col-md-8">
                        <input class="form-control" style="padding: 5px;border-radius: 10px;" type="text" value="{{ $request->first_name }}" placeholder="Please enter your first name" readonly>
                    </div>
                </div>
                <br>
                <div class="form-group row">
                    <label class="col-md-4 col-form-label">Last Name</label>
                    <div class="col-md-8">
                        <input class="form-control" style="padding: 5px;border-radius: 10px;" type="text" value="{{ $request->last_name }}" placeholder="Please enter your last name" readonly>
                    </div>
                </div>
                <br>
                <div class="form-group row">
                    <label class="col-md-4 col-form-label">Business Name</label>
                    <div class="col-md-8">
                        <input class="form-control" style="padding: 5px;border-radius: 10px;" type="text" value="{{ $request->business->name }}" placeholder="Please enter transaction number" readonly>
                    </div>
                </div>
                <br>
                <div class="form-group row">
                    <label class="col-md-4 col-form-label">Transaction number</label>
                    <div class="col-md-8">
                        <input class="form-control" style="padding: 5px;border-radius: 10px;" type="text" value="{{ $request->transaction_number }}" placeholder="Please enter transaction number" readonly>
                    </div>
                </div>
                <br>
                <div class="form-group row">
                    <label class="col-md-4 col-form-label">Status</label>
                    <div class="col-md-8">
                        <select name="status" class="form-control">
                            <option value="pending" {{ $request->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ $request->status == 'approved' ? 'selected' : '' }}>Approve</option>
                            <option value="rejected" {{ $request->status == 'rejected' ? 'selected' : '' }}>Reject</option>
                        </select> 
                        @if ($errors->first('status'))
                            <p class="text-danger small">{{ $errors->first('status') }}
                            </p>
                        @endif
                    </div>
                </div>
                <br>
                <div class="form-group row">
                    <label class="col-md-4 col-form-label">Payment Screenshot</label>
                    <div class="col-md-8">
                        <img src="{{ asset('mpesa_requests/'.$request->document) }}" height="300px" width="fit-content" /><br>
                        <a href="{{ asset('mpesa_requests/'.$request->document) }}" download>Download</a>
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

<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script>
    
</script>
