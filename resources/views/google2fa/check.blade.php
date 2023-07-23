<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Enter OPT</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <link rel="stylesheet" href="{{asset('frontend/assets/bootstrap/css/bootstrap.min.css')}}">
    </head>

    <body class="">
        <div class="container">
            <div class="row justify-content-center text-center">
                <div class="col-md-8 ">
                    <div class="card">
                        <div class="card-header">Two Factor Authentication</div>
                        <div class="card-body">
                            @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif

                            Enter the pin from Google Authenticator app:<br /><br />
                            <form class="form-horizontal" action="{{ route('2faVerify') }}" method="POST">
                                {{ csrf_field() }}
                                <div class="form-group{{ $errors->has('one_time_password') ? ' has-error' : '' }}">
                                    <label for="one_time_password" class="control-label">OTP</label>
                                    <input id="one_time_password" name="one_time_password" class="form-control"
                                        type="number" required />
                                </div>
                                <button class="btn btn-primary" type="submit">Authenticate</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>

</html>
