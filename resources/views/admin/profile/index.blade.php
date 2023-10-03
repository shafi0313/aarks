@extends('admin.layout.master')
@section('title', $user->name . ' - Google Authenticator')
@section('content')
    <div class="main-content">
        <div class="main-content-inner">
            <div class="breadcrumbs ace-save-state" id="breadcrumbs">
                <ul class="breadcrumb">
                    <li>
                        <i class="ace-icon fa fa-home home-icon"></i>
                        <a href="#">Home</a>
                    </li>
                    <li class="active">Dashboard</li>
                </ul><!-- /.breadcrumb -->
                <div class="nav-search" id="nav-search">
                    <form class="form-search">
                        <span class="input-icon">
                            <input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input"
                                autocomplete="off" />
                            <i class="ace-icon fa fa-search nav-search-icon"></i>
                        </span>
                    </form>
                </div><!-- /.nav-search -->
            </div>
            <div class="page-content">
                <div class="page-header">
                    <h1>
                        {{ $user->name }} Google Authenticator
                    </h1>
                </div><!-- /.page-header -->

                <div class="row">
                    <div class="col-xs-12">
                        @if (empty($user->two_factor_secret))
                            {{-- Enable Google2Fa --}}
                            <div class="panel panel-default">
                                <div class="panel-heading">Set up Google Authenticator</div>
                                <div class="panel-body" style="text-align: center;">
                                    <p>Set up your two factor authentication by scanning the barcode below. Alternatively,
                                        you can use the code
                                        {{ $secret_key }}</p>
                                    <div>
                                        {!! $QR !!}
                                    </div>
                                    <p>You must set up your Google Authenticator app before continuing. You will be unable
                                        to login otherwise
                                    </p>

                                    <div class="my-5">
                                        <form class="form-horizontal" method="POST"
                                            action="{{ route('admin.2fa.store', $user->id) }}" autocomplete="off">
                                            @csrf
                                            <div class="form-group">
                                                <p>Please enter the <strong>OTP</strong> generated on your Authenticator
                                                    App. <br> Ensure you
                                                    submit
                                                    the current one because it refreshes every 30 seconds.</p>
                                                <label for="code" class="col-md-4 control-label">One Time
                                                    Password</label>
                                                <input type="hidden" name="key" value="{{ $secret_key }}">
                                                <div class="col-md-6">
                                                    <input id="code" type="number" class="form-control" name="code"
                                                        required autofocus>
                                                    @if ($errors->any())
                                                        <b style="color: red">{{ $errors->first() }}</b>
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
                                    <p>Set up your two factor authentication by scanning the barcode below. Alternatively,
                                        you can use the code
                                    </p>
                                    <div class="my-5">
                                        <form class="form-horizontal" method="POST" action="{{ route('admin.2fa.destroy', $user->id) }}"
                                            autocomplete="off">
                                            @csrf
                                            <div class="form-group">
                                                <p>Please enter the <strong>OTP</strong> generated on your Authenticator
                                                    App. <br> Ensure you
                                                    submit
                                                    the current one because it refreshes every 30 seconds.</p>
                                                <label for="code" class="col-md-4 control-label">One Time
                                                    Password</label>
                                                <input type="hidden" name="key" value="{{ $user->two_factor_secret }}">
                                                <div class="col-md-6">
                                                    <input id="code" type="number" class="form-control" name="code"
                                                        required autofocus>
                                                    @if ($errors->any())
                                                        <b style="color: red">{{ $errors->first() }}</b>
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

                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div>
        </div>
    </div>
@endsection
