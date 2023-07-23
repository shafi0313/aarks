@extends('admin.layout.master')
@section('title','Bank Reconcilation')
@section('content')

<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="{{ route('admin.dashboard') }}">Home</a>
                </li>
                <li>Transaction List</li>
                <li class="active">Bank Reconcilation</li>
            </ul>
        </div>

        <div class="page-content" style="margin-top: 100px;">
            <form action="{{route('bank_recon.report')}}" class="was-validated" method="get" autocomplete="off" autocapitalize="off">
                <div class="row col-lg-offset-3">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="select-client">
                                <h2>Select Client</h2>
                            </label>
                            <select class="form-control" id="client_id" name="client_id" required onchange="getCodes(this.value)">
                                <option> Select a Client</option>
                            </select>
                        </div>
                        <div class="form-group">
                        <div id="codes-container"></div>
                        </div>

                            <div class="date-group d-none">
                            <div class="form-group">
                                <label>
                                    <h2>Form Date</h2>
                                </label>
                                <input required type="test" id="from_date" name="from_date" class="form-control date-picker datepicker"
                                    data-date-format="dd/mm/yyyy" Placeholder="DD/MM/YYYY" />
                            </div>
                            <div class="form-group">
                                <label><h2>To Date</h2></label>
                                <input required type="test" id="to_date" name="to_date" class="form-control date-picker datepicker"
                                    data-date-format="dd/mm/yyyy" Placeholder="DD/MM/YYYY" />
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary w-100" >Show Report</button>
                            </div>
                            </div>
                    </div>
                </div><!-- /.row -->
            </form>
        </div><!-- /.page-content -->
    </div>
</div>
<script>
    $(".datepicker").datepicker({
        dateFormat: "dd/mm/yy",
        changeMonth: true,
        changeYear: true
    });
    $('#client_id').select2({
        ajax: {
            url     : '{{route("select-two")}}',
            type    : 'get',
            dataType: 'json',
            delay   : 250,
            cache   : true,
            data    : function(params) {
                return {
                    type: 'getClient',
                    q   : $.trim(params.term)
                };
            },
            processResults: function (data) {
                return {
                    results : data
                };
            },
        }
    });
    // Get List of professions for client
    function getCodes(client_id) {
        // replace existing string with new string
        var url = '{{route("bank_recon.codes", ":id")}}';
        url = url.replace(':id', client_id);
        $.ajax({
            url     : url,
            type    : 'get',
            dataType: 'json',
            delay   : 250,
            cache   : true,
            data    : {
                client_id: client_id
            },
            success: function (res) {
                $('#codes-container').html(res.data);
            }
        });
    }
</script>
@endsection

