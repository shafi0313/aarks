@extends('admin.layout.master')
@section('title','Business Analysis Details')
@section('content')

<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="{{ route('admin.dashboard') }}">Home</a>
                </li>
                <li class="active">Business Analysis Details</li>
                <li class="active">Select</li>
            </ul>
        </div>

        <div class="page-content" style="margin-top: 100px;">
            <form action="{{route('advance_report.business_analysis.report')}}" class="was-validated" method="get"
                autocomplete="off" autocapitalize="off">
                <div class="row col-lg-offset-3">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="select-client">
                                <h2>Select Client</h2>
                            </label>
                            <select required class="form-control" id="client_id" name="client_id" required>
                                <option value=""> Select a Client</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="select-profession">
                                <h2>Select Profession</h2>
                            </label>
                            <select required class="form-control" id="profession_id"  name="profession_id"
                                required>
                                <option value=""> Select a Profession</option>
                            </select>
                            {{-- <div id="profession-container"></div> --}}
                        </div>
                        <div class="form-group">
                            <label for="year">
                                <h2>Select Financial Year</h2>
                            </label>
                            <select required class="form-control" id="year"  name="year"
                                required>
                                <option value=""> Select a Financial Year</option>
                                {{-- Last 10 years --}}
                                @for ($i = date("Y"); $i >= date("Y",strtotime("-10 years")); $i--)
                                <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                            {{-- <div id="year-container"></div> --}}
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Show Report</button>
                    </div>
                </div><!-- /.row -->
            </form>
        </div><!-- /.page-content -->
    </div>
</div>
<script>
    $('#year').select2();
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
</script>
@endsection
