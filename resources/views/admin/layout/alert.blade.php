<script>
    $(function() {
        @if ($errors->any())
            @foreach ($errors->all() as $i => $error)
                new Noty({
                text: '{{ $error }}',
                type: 'error',
                theme: 'relax',
                timeout: 3000
                }).show();
            @endforeach
        @endif

        @if (session('error'))
            new Noty({
            text: '{{ session('error') }}',
            type: 'error',
            theme: 'relax',
            timeout: 3000
            }).show();
        @endif
        @if (session('success'))
            new Noty({
            text: '{{ session('success') }}',
            type: 'success',
            theme: 'relax',
            timeout: 3000
            }).show();
        @endif
        @if (session('warning'))
            new Noty({
            text: '{{ session('warning') }}',
            type: 'warning',
            theme: 'relax',
            timeout: 3000
            }).show();
        @endif
    });
</script>
