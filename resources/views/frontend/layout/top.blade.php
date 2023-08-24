<meta charset="UTF-8">
<meta name='viewport'
    content='width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no'>
<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'Home') | {{ config('app.name') }}</title>

<link rel="stylesheet" href="{{ asset('frontend/assets/bootstrap/css/bootstrap.min.css') }}">
{{-- <link rel="stylesheet" href="{{ asset('frontend/assets/fontawesome/css/all.min.css') }}"> --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
    integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
{{-- <link rel="stylesheet" href="{{ asset('frontend/assets/animatecss/animate.min.css') }}"> --}}
<link rel="stylesheet" href="{{ asset('frontend/assets/data_table/datatables.min.css') }}" />
<link rel="stylesheet"
    href="{{ asset('frontend/assets/bootstrap-datepicker/dist/css/bootstrap-datepicker3.standalone.min.css') }}">
<link rel="stylesheet" href="{{ asset('frontend/assets/css/style.css') }}">
<link rel="stylesheet" href="{{ asset('admin/assets/css/font-awesome.min.css') }}">
<script src="{{ asset('frontend/assets/js/jquery3.4.1.js') }}"></script>
<script src="{{ asset('admin/assets/cdn/toastr.min.js') }}"></script>
<link rel="stylesheet" href="{{ asset('admin/assets/cdn/toastrv2.1.3.css') }}">
{{-- Summer Note --}}
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
{{-- For Multi select --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" />
<link rel="stylesheet" href="{{ asset('css/both-side.css') }}">


<link rel="shortcut icon" href="{{ asset('favicon.png') }}" type="image/x-icon">
@yield('style')
