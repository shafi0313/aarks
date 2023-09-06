<!DOCTYPE html>
<html lang="en">

<head>
    @include('frontend.layout.top')
    <meta name="google-site-verification" content="GvrFPEa1um25IIRHLEmufMuJ1H_HTw3cgdpBuL-lhsA" />
</head>

<body>
    <div class="">
        @include('frontend.layout.header')
        @if (auth()->guard('client')->check())
            @include('frontend.layout.navigation')
        @endif
    </div>
    <div class="main-content">
        @yield('content')
    </div>
    @include('frontend.layout.footer')

    {{-- <!-- Script --> --}}
    <script src="{{ asset('frontend/assets/js/popper.js') }}"></script>
    <script src="{{ asset('frontend/assets/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/data_table/datatables.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    @include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])
    <script src="{{ asset('admin/assets/js/moment.min.js') }}"></script>
    {{-- Summer Note --}}
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    {{-- <!-- Laravel Javascript Validation --> --}}
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/main.js') }}"></script>

    @stack('script')

    @yield('script')
    <script src="{{ asset('js/custom.js') }}"></script>
</body>

</html>
