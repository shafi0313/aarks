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
    <script src="{{ asset('frontend/assets/js/main.js') }}"></script>

    @stack('script')
    <script>
        $(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            // $("form").on('submit', function(e) {
            //     $(this).find('button[type="submit"]').attr('disabled', 'disabled');
            // });
        });

        $(document).ready(function() {
            // Select 2
            $('.select2Single').select2();

            // Form Enable/Disable
            $("form").on('submit', function(e) {
                var form = $(this);
                var submitButton = $(this).find('button[type="submit"]');
                submitButton.prop('disabled', true);
                setTimeout(function() {
                    submitButton.prop('disabled', false);
                }, 5000);
            });
        });
        // Toast Notification
        function toast(status, header, msg) {
            // $.toast('Here you can put the text of the toast')
            Command: toastr[status](header, msg)
            toastr.options = {
                "closeButton": true,
                "debug": false,
                "newestOnTop": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "preventDuplicates": true,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "2000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            }
        }
    </script>
    @yield('script')
</body>

</html>
