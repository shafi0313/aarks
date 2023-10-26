@extends('admin.layout.master')
@section('title','Prepare Budget/Budget Entry')
@section('content')

<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="{{ route('admin.dashboard') }}">Home</a>
                </li>
                <li class="active">Prepare Budget/Budget Entry</li>
                <li class="active">Select</li>
            </ul>
        </div>

        <div class="page-content" style="margin-top: 100px;">
            <form action="{{route('advance_report.budget.report')}}" class="was-validated" method="get"
                autocomplete="off" autocapitalize="off">
                <div class="row col-lg-offset-3">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="select-client">
                                <h2>Select Client</h2>
                            </label>
                            <select class="form-control" id="client_id" name="client_id" required>
                                <option> Select a Client</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="select-profession">
                                <h2>Select Profession</h2>
                            </label>
                            <select class="form-control" id="profession_id" onchange="getDates()" name="profession_id"
                                required>
                                <option> Select a Profession</option>
                            </select>
                            {{-- <div id="profession-container"></div> --}}
                        </div>

                        <div class="form-group">
                            <label>
                                <h2>Date</h2>
                            </label>
                            <table class="table table-bordered table-hover">
                                <tbody id="date-container"></tbody>
                            </table>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Show Report</button>
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
                    q   : $.trim(params.term),
                    type: 'getClient'
                };
            },
            processResults: function (data) {
                return {
                    results : data
                };
            },
        }
    });
    $('#profession_id').select2({
        ajax: {
            url           : '{{route("select-two")}}',
            type          : 'get',
            dataType      : 'json',
            delay         : 250,
            cache         : true,
            data    : function(params) {
                return {
                    q        : $.trim(params.term),
                    client_id: $('#client_id').val(),
                    type     : 'getProfession'
                };
            },
            processResults: function (data) {
                return {
                    results : data
                };
            },
        }
    });
    function getDates() {
        $.ajax({
            url: '{{route("select-two")}}',
            type: 'get',
            dataType: 'json',
            data: {
                client_id: $('#client_id').val(),
                profession_id: $('#profession_id').val(),
                type: 'getDates'
            },
            success: function (res) {
                if(!res.tr){
                    alert('Please prepare the budget first!')
                }
                $('#date-container').html(res.tr);
            }
        });
    }
</script>
@endsection
