@extends('admin.layout.master')
@section('title', 'GST Reconciliation for TR')
@section('content')

    <div class="main-content">
        <div class="main-content-inner">
            <div class="breadcrumbs ace-save-state" id="breadcrumbs">
                <ul class="breadcrumb">
                    <li>
                        <i class="ace-icon fa fa-home home-icon"></i>
                        <a href="{{ route('admin.dashboard') }}">Home</a>
                    </li>
                    <li>Reports</li>
                    <li class="active">GST Reconciliation for TR</li>
                </ul>
            </div>

            <div class="page-content" style="margin-top: 100px;">
                <form action="#" name="topform">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="select-client">
                                    <h2>Select Client</h2>
                                </label>
                                <select class="form-control" id="select-client" onchange="getProfession(this.value)">
                                    <option> Select a Client</option>
                                </select>
                            </div>
                        </div><!-- /.col -->
                        <div class="col-lg-4">
                            <div id="profession-container"></div>
                        </div><!-- /.col -->
                        <div class="col-lg-4">
                            <div id="period-container"></div>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </form>
            </div><!-- /.page-content -->
        </div>
    </div>
    <script>
        $('#select-client').select2({
            ajax: {
                url: '{{ route('gst_recon.client') }}',
                type: 'get',
                dataType: 'json',
                delay: 250,
                cache: true,
                processResults: function(data) {
                    return {
                        results: data
                    };
                },
            }
        });
        // Get List of professions for client
        function getProfession(client_id) {
            // replace existing string with new string
            var url = '{{ route('gst_recon.profession', ':id') }}';
            url = url.replace(':id', client_id);
            $.ajax({
                url: url,
                type: 'get',
                dataType: 'json',
                delay: 250,
                cache: true,
                data: {
                    client_id: client_id
                },
                success: function(res) {
                    $('#profession-container').html(res.data);
                }
            });
        }
        // Get List of period for profession
        function getPeriod(client = 1, profession = 2) {
            // replace existing string with new string
            var url = '{{ route('gst_recon.period', [':client', ':profession']) }}';
            url = url.replace(':client', client);
            url = url.replace(':profession', profession);
            $.ajax({
                url: url,
                type: 'get',
                dataType: 'json',
                delay: 250,
                cache: true,
                data: {
                    client: client,
                    profession: profession
                },
                success: function(res) {
                    $('#period-container').html(res.data);
                }
            });
        }
    </script>
@endsection
