@if (empty($user->two_factor_secret))
{{-- Enable Google2Fa --}}
<div class="panel panel-default">
    <div class="panel-heading">Set up Google Authenticator</div>
    <div class="panel-body" style="text-align: center;">
        <p>Set up your two factor authentication by scanning the barcode below. Alternatively, you can use the code {{
            $secret_key }}</p>
        <div>
            {!! $QR !!}
        </div>
        <p>You must set up your Google Authenticator app before continuing. You will be unable to login otherwise</p>

        <div class="my-5">
            <form class="form-horizontal" method="POST" action="{{route('2fa.store')}}" autocomplete="off">
                @csrf
                <div class="form-group">
                    <p>Please enter the <strong>OTP</strong> generated on your Authenticator App. <br> Ensure you submit
                        the current one because it refreshes every 30 seconds.</p>
                    <label for="code" class="col-md-4 control-label">One Time Password</label>
                    <input type="hidden" name="key" value="{{ $secret_key }}">
                    <div class="col-md-6">
                        <input id="code" type="number" class="form-control" name="code" required autofocus>
                        @if($errors->any())
                        <b style="color: red">{{$errors->first()}}</b>
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-6 col-md-offset-4">
                        <button type="submit" class="btn btn-primary">
                            Validate Opt
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@else
<div class="panel panel-default">
    <div class="panel-heading">Turn Off Google Authenticator</div>
    <div class="panel-body" style="text-align: center;">
        <p>Set up your two factor authentication by scanning the barcode below. Alternatively, you can use the code</p>
        <div class="my-5">
            <form class="form-horizontal" method="POST" action="{{route('2fa.destroy')}}" autocomplete="off">
                @csrf
                <div class="form-group">
                    <p>Please enter the <strong>OTP</strong> generated on your Authenticator App. <br> Ensure you submit
                        the current one because it refreshes every 30 seconds.</p>
                    <label for="code" class="col-md-4 control-label">One Time Password</label>
                    <input type="hidden" name="key" value="{{ $user->two_factor_secret }}">
                    <div class="col-md-6">
                        <input id="code" type="number" class="form-control" name="code" required autofocus>
                        @if($errors->any())
                        <b style="color: red">{{$errors->first()}}</b>
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-6 col-md-offset-4">
                        <button type="submit" class="btn btn-danger w-100">
                            Turn Off
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
