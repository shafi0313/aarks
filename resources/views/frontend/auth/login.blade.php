<!DOCTYPE html>
<html lang="en" xml:lang="en">
    <meta http-equiv="content-type" content="text/html;charset=UTF-8" />

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="icon" href="{{asset('favicon.png')}}" type="image/x-icon">
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="{{asset('favicon.png')}}" type="image/x-icon" />
        <link rel="shortcut icon" href="{{asset('favicon.png')}}" type="image/x-icon" />
        <link href="https://fonts.googleapis.com/css?family=PT+Sans:400,700" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i"
            rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800" rel="stylesheet">
        <title>ADVANCED ACCOUNTING &amp; RECORD KEEPING SOFTWARE (AARKS)</title>
        <link rel="stylesheet" href="{{asset('admin/assets/font-awesome/4.5.0/css/font-awesome.min.css')}}"
            type="text/css" />
        <link rel="stylesheet" href="{{asset('newfolder/front/assets/css/bootstrap.min.css')}}" type="text/css" />
        <link rel="stylesheet" href="{{asset('frontend/assets/css/style-login.css')}}" type="text/css" />
    </head>

    <body>
        <div class="wrapper login-page style-3" id="top">
            <div class="cp-container">
                <div class="form-part">
                    <div class="cp-header">
                        <div class="logo" align="center">
                            <a href="{{url('/')}}"><img class="light"
                                    src="{{asset('frontend/assets/images/logo/focus-icon.png')}}" alt="BRAND LOGO"></a>
                        </div>
                    </div>
                    <div class="cp-heading" align="center">
                        <h6>Welcome Back :)</h6>
                        @if ($errors->any())
                        <span class="text-danger">
                            <strong>{{$errors->first()}}</strong>
                        </span>
                        @endif
                    </div>
                    <div class="cp-body">
                        <form method="POST" action="{{route('login')}}">
                            @csrf
                            <div class="form-group username-field">
                                <div class="form-field">
                                    <input id="email" type="email" class="form-control "
                                        placeholder="Enter Email Address" name="email" required {{-- value="f@f.com" --}}
                                        autocomplete="email" autofocus>
                                </div>
                            </div>
                            <div class="form-group password-field">
                                <div class="form-field">
                                    <input id="password" type="password" class="form-control "
                                        placeholder="Enter Password" name="password" required {{-- value="password" --}}
                                        autocomplete="current-password">
                                </div>
                            </div>
                            <div class="form-group password-field">
                                <div class="form-field">
                                    <input type="text" maxlength="6" minlength="6" class="form-control"
                                        placeholder="Enter Two Factor Authentication Code" name="code" {{-- value="123456" --}}>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <p class="text-left remember-me-checkbox ">
                                        <label for="remember">
                                            <input type="checkbox" name="remember" id="remember">Remember Me
                                        </label>
                                    </p>
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-success" name="login_form">Login</button>
                            </div>
                            <div class="form-group">
                                <p class="text-center">
                                    @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                    @endif
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </body>

</html>
