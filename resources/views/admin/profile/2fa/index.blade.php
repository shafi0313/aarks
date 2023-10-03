@extends('admin.layout.master')
@section('title', $admin->name.' - Profile')
@section('content')
<div class="main-content">
    <div class="main-content-inner">
        <div class="panel panel-default">
            <div class="panel-heading">Set up Google Authenticator</div>
            @if ($admin->two_factor_secret)
            <div class='row'>
                <div class="col-lg-12 text-center">
                    <style>
                        svg {
                            width: 450px;
                            height: 450px;
                        }
                    </style>
                    {!! $QR !!}
                </div>
                <div class="col-lg-12 text-center">
                    <a href="{{route('client.index')}}" class="btn w-100 btn-warning">
                        BACK
                    </a>
                </div>
            </div>
            @else
            <div class="panel-body" style="text-align: center;">
                <p>Set up your two factor authentication by scanning the barcode below. Alternatively, you can use the
                    code {{
                    $secret_key }}</p>
                <div>
                    {!! $QR !!}
                </div>
                <p>You must set up your Google Authenticator app before continuing. You will be unable to login
                    otherwise</p>

                <div class="my-5">
                    <form class="form-horizontal" method="POST" action="{{route('admin.2fa.store', $admin->id)}}"
                        autocomplete="off">
                        @csrf
                        <div class="form-group">
                            <input type="hidden" name="key" value="{{ $secret_key }}">

                            <p>Please enter the <strong>OTP</strong> generated on your Authenticator App. <br> Ensure
                                you submit
                                the current one because it refreshes every 30 seconds.</p>
                            @if($errors->any())
                            <p><b style="color: red">{{$errors->first()}}</b></p>
                            @endif
                            <label for="code" class="col-md-4 control-label">One Time Password</label>
                            <div class="col-md-6">
                                <input id="code" type="number" class="form-control" name="code" required autofocus>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 col-lg-offset-3">
                                <a href="{{URL()->previous()}}" class="btn btn-warning">
                                    BACK
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    Validate Opt
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
