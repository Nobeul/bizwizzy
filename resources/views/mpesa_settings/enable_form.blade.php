@extends('layouts.app')
@section('title', __('Mpesa Settings'))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>@lang( 'Mpesa Request Form' ) 
        {{-- @show_tooltip(__('lang_v1.types_of_service_help_long')) --}}
    </h1>
</section>

<!-- Main content -->
<section class="content">
    
    @component('components.widget', ['class' => 'box-primary'])
        <h4>
            <i>
                Thank you for your interest to enable Mpesa in your system. It is a premium service and to get it enabled you have to pay 1000 ksh to 222 111 555. After the payment is done please fill up the form. Generally it takes two to three days to get verify.
            </i>
        </h4>
        <br><br><br>
        <div class="row col-md-8">
            <form class="form-group" action="" method="post" enctype="multipart/form-data">
                @csrf
                <div class="form-group row">
                    <label class="col-md-4 col-form-label">First Name</label>
                    <div class="col-md-8">
                        <input class="form-control" style="padding: 5px;border-radius: 10px;" type="text" name="first_name" placeholder="Please enter your first name" value="{{ old('first_name') }}" required>
                        @if ($errors->first('first_name'))
                            <p class="text-danger small">{{ $errors->first('first_name') }}
                            </p>
                        @endif
                    </div>
                </div>
                <br>
                <div class="form-group row">
                    <label class="col-md-4 col-form-label">Last Name</label>
                    <div class="col-md-8">
                        <input class="form-control" style="padding: 5px;border-radius: 10px;" type="text" name="last_name" placeholder="Please enter your last name" value="{{ old('last_name') }}" required>
                        @if ($errors->first('last_name'))
                            <p class="text-danger small">{{ $errors->first('last_name') }}
                            </p>
                        @endif
                    </div>
                </div>
                <br>
                <div class="form-group row">
                    <label class="col-md-4 col-form-label">Transaction number</label>
                    <div class="col-md-8">
                        <input class="form-control" style="padding: 5px;border-radius: 10px;" type="text" name="transaction_number" placeholder="Please enter transaction number" value="{{ old('transaction_number') }}" required>
                        @if ($errors->first('transaction_number'))
                            <p class="text-danger small">{{ $errors->first('transaction_number') }}
                            </p>
                        @endif
                    </div>
                </div>
                <br>
                <div class="form-group row">
                    <label class="col-md-4 col-form-label">Payment Screenshot</label>
                    <div class="col-md-8">
                        <input class="form-control" style="padding: 5px;border-radius: 10px;" type="file" name="document" accept="image/*">
                        @if ($errors->first('document'))
                            <p class="text-danger small">{{ $errors->first('document') }}
                            </p>
                        @endif
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
