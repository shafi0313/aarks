<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta charset="utf-8" />
    <title>@yield('title') | {{ config('app.name') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="description" content="overview &amp; stats" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;400;700;900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif !important;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
        integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="{{ asset('admin/assets/cdn/jquery.min.js') }}"></script>
    {{-- <!-- bootstrap & fontawesome --> --}}
    <link rel="stylesheet" href="{{ asset('admin/assets/css/bootstrap.min.css') }}" />

    <link rel="stylesheet" href="{{ asset('admin/assets/font-awesome/4.5.0/css/font-awesome.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/flag.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}" />

    {{-- <!-- Toastr --> --}}
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/assets/cdn/toastr.css') }}">

    {{-- <!-- Calendar --> --}}
    <link rel="stylesheet" href="{{ asset('admin/assets/css/jquery-ui.custom.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('admin/assets/css/fullcalendar.min.css') }}" />

    {{-- <!-- text fonts --> --}}
    <link rel="stylesheet" href="{{ asset('admin/assets/css/fonts.googleapis.com.css') }}" />

    {{-- <!-- ace styles --> --}}
    <link rel="stylesheet" href="{{ asset('admin/assets/css/ace.min.css') }}" class="ace-main-stylesheet"
        id="main-ace-style" />

    <link rel="shortcut icon" href="{{ asset('favicon.png') }}" type="image/x-icon">

    {{-- <!-- Toastr CSS--> --}}
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.css">
    {{-- Data Table --}}
    <link rel="stylesheet" href="{{ asset('frontend/assets/data_table/datatables.min.css') }}" />
    <script src="{{ asset('frontend/assets/data_table/datatables.min.js') }}"></script>

    <link rel="stylesheet" href="{{ asset('admin/assets/cdn/toastrv2.1.3.css') }}">

    {{-- <!-- ace settings handler --> --}}
    <script src="{{ asset('admin/assets/js/ace-extra.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('admin/assets/cdn/jquery-ui.css') }}">
    <script src="{{ asset('admin/assets/cdn/jquery-ui.js') }}"></script>
    <script src="{{ asset('admin/assets/cdn/toastr.min.js') }}"></script>

    {{-- JS ALERT --}}
    <link rel="stylesheet" href="{{ asset('admin/assets/cdn/jquery-confirm.min.css') }}">
    <script src="{{ asset('admin/assets/cdn/jquery-confirm.min.js') }}"></script>
    <script src="{{ asset('admin/assets/cdn/moment.min.js') }}"></script>

    {{-- NOTIFY ALERT --}}
    <link rel="stylesheet" href="{{ asset('admin/assets/noty/noty.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/assets/noty/themes/relax.css') }}">
    <script src="{{ asset('admin/assets/noty/noty.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('admin/assets/css/custom.css') }}" />
    {{-- For Multi select --}}
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css">

    @yield('style')
    <script>
        window.date_format = "{{ aarks('js_date_format') }}";
    </script>
    <style>
        @media print {
            .main-content .page-content * {
                display: block;
                color: red
            }

            .main-content .page-content .reportH {
                display: block;
                min-height: 100vh;
                width: 100%;
                padding-top: 40px;
            }
        }

        .page-break {
            page-break-after: always;
        }
    </style>
    <meta name="google-site-verification" content="GvrFPEa1um25IIRHLEmufMuJ1H_HTw3cgdpBuL-lhsA" />
</head>

<body class="no-skin">
    {{-- <!-- Header --> --}}
    @include('admin.layout.header')
    {{-- <!-- /Header --> --}}

    @include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])
    <div class="main-container ace-save-state" id="main-container">
        <script type="text/javascript">
            try {
                ace.settings.loadState('main-container')
            } catch (e) {}
        </script>

        {{-- <!-- Navigation --> --}}
        @include('admin.layout.navigation')
        {{-- <!-- /Navigation --> --}}

        {{-- <!-- PAGE CONTENT BEGINS --> --}}
        @yield('content')
        {{-- <!-- PAGE CONTENT ENDS --> --}}

        @include('admin.common._delete')
        {{-- <!-- Footer --> --}}
        @include('admin.layout.footer')
        {{-- <!-- /Footer --> --}}
        @include('admin.layout.includes.data_table_js')

        <script src="{{ asset('admin/assets/js/jquery-2.1.4.min.js') }}"></script>
        <script src="{{ asset('admin/assets/js/bootstrap.min.js') }}"></script>
        <script type="text/javascript">
            if ('ontouchstart' in document.documentElement) document.write(
                "<script src='{{ asset('public/admin/assets/js/jquery.mobile.custom.min.js') }}'>" + "<" + "/script>");
        </script>

        <script src="{{ asset('admin/assets/js/ace-elements.min.js?v=' . assetVersion('ace_elements_min_js')) }}"></script>
        <script src="{{ asset('admin/assets/js/ace.min.js?v=' . assetVersion('ace_min_js')) }}"></script>
        <script src="{{ asset('admin/assets/js/aarks.js?v=' . assetVersion('aarks_js')) }}"></script>
        <script src="{{ asset('admin/assets/js/main.js') }}"></script>
        <script src="{{ asset('js/custom.js') }}"></script>
        @include('admin.layout.alert')

        @yield('script')
        @stack('custom_scripts')
    </div>
</body>

</html>
