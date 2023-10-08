@extends('frontend.layout.master')
@section('title', client()->company)
@section('content')
    <?php $p = 'ul';
    $mp = 'setting'; ?>
    <div class="container">
        <div class="row mt-5">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">Set up Google Authenticator</div>
                    <div class="card-body" style="text-align: center;">
                        @if ($client->two_factor_secret)
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
                                    <a href="{{ URL()->previous() }}" class="btn w-100 btn-warning">
                                        BACK
                                    </a>
                                </div>
                            </div>
                        @else
                            <p>Set up your two factor authentication by scanning the barcode below. Alternatively, you can
                                use the code {{ $secret_key }}</p>
                            <div>
                                {!! $QR !!}
                            </div>
                            <p>You must set up your Google Authenticator app before continuing. You will be unable to login
                                otherwise</p>

                            <div class="my-5">
                                <form class="form-horizontal" method="POST"
                                    action="{{ route('profile.2fa.store', $client->id) }}" autocomplete="off">
                                    @csrf
                                    <div class="form-group row justify-content-center">
                                        <input type="hidden" name="key" value="{{ $secret_key }}">
                                        <div class="col-lg-12">
                                            <p>Please enter the <strong>OTP</strong> generated on your Authenticator App.
                                                <br>
                                                Ensure you submit the current one because it refreshes every 30 seconds.
                                            </p>
                                            @if ($errors->any())
                                                <p><b style="color: red">{{ $errors->first() }}</b></p>
                                            @endif
                                        </div>
                                        <div class="col-md-6">
                                            <label for="code" class="control-label">One Time Password</label>
                                            <input id="code" type="number" class="form-control" name="code"
                                                required autofocus>
                                        </div>
                                    </div>

                                    <div class="form-group row justify-content-center">
                                        <div class="col-lg-6">
                                            <a href="{{ URL()->previous() }}" class="btn btn-warning">
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
    </div>
@endsection
