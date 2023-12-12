@extends('admin.layout.master')
@section('title', 'ADT Data Entry')
@section('content')
    <div class="main-content">
        <div class="main-content-inner">
            <div class="breadcrumbs ace-save-state" id="breadcrumbs">
                <ul class="breadcrumb">
                    <li>
                        <i class="ace-icon fa fa-home home-icon"></i>
                        <a href="{{ route('admin.dashboard') }}">Home</a>
                    </li>
                    <li>Period</li>
                    <li style="color: red; font-weight: bold;">
                        @if (empty($client->company))
                            {{ $client->first_name . ' ' . $client->last_name }}
                        @else
                            {{ $client->company }}
                        @endif
                    </li>
                    <li class="active">Add/Edit Period</li>
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
                <div class="row">
                    <div class="col-xs-12">
                        <!-- PAGE CONTENT BEGINS -->
                        <div id="client_datatale">
                            <div class="panel-body" style="min-height:600px;">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">Activity Name: {{ $profession->name }}
                                            <span style="padding-left:100px; color:pink;">Period:
                                                {{ bdDate($period->start_date) }} to
                                                {{ bdDate($period->end_date) }} </span>
                                        </h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class="col-md-12">
                                            <div class="col-md-4">
                                                <h3 style="color:green;">Select Account Name: </h3>
                                                <span style="color: red">If you cannot see any a/c code please update
                                                    profession from the profile menu.</span>
                                            </div>
                                            <div class="col-md-8" style="padding-top:20px;">
                                                <div class="form-group">
                                                    <select class="form-control chosen-select" id="subid" name="subid"
                                                        onchange="location = this.value">
                                                        <option>Select Account Name </option>
                                                        @foreach ($account_codes as $ac_code)
                                                            @if ($ac_code->type == 1)
                                                                <option
                                                                    value="{{ route('sub_pro_show', [$ac_code->id, $ac_code->code, $period->id, $profession->id, $client->id]) }}"
                                                                    style="color: green;">
                                                                    {{ $ac_code->name }}
                                                                </option>
                                                            @else
                                                                <option
                                                                    value="{{ route('sub_pro_show', [$ac_code->id, $ac_code->code, $period->id, $profession->id, $client->id]) }}"
                                                                    style="color: hotpink;">
                                                                    {{ $ac_code->name }}
                                                                </option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12" style="min-height:300px;">
                                            <div id="addatalistpage">
                                                <div class="row">
                                                    <style>
                                                        table.scroll {
                                                            width: 100%;
                                                        }

                                                        table.scroll th,
                                                        table.scroll td,
                                                        table.scroll tr,
                                                        table.scroll thead,
                                                        table.scroll tbody {
                                                            display: block;
                                                        }

                                                        table.scroll thead tr {
                                                            /* fallback */
                                                            width: 100%;
                                                            /* minus scroll bar width */
                                                            width: -webkit-calc(100% - 16px);
                                                            width: -moz-calc(100% - 30px);
                                                            width: calc(100% - 16px);
                                                        }

                                                        table.scroll tr:after {
                                                            content: ' ';
                                                            display: block;
                                                            visibility: hidden;
                                                            clear: both;
                                                        }

                                                        table.scroll tbody {
                                                            height: 190px;
                                                            overflow-x: auto;
                                                            overflow-x: hidden;
                                                        }

                                                        table.scroll tbody td,
                                                        table.scroll thead th {
                                                            float: left;
                                                        }

                                                        thead tr th {
                                                            height: 30px;
                                                            line-height: 20px;
                                                        }

                                                        tbody td:last-child,
                                                        thead th:last-child {
                                                            border-right: none !important;
                                                        }
                                                    </style>
                                                    <div><button type="button"
                                                            class="btn btn-primary btn-lg btn-block"><span class="pull-left"
                                                                style="color:#fff; font-weight: bold">{{ $client_account->name }}
                                                            </span>
                                                        </button></div>
                                                    <strong class="datelockd"></strong>
                                                    <table class="scroll table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th width="13%"
                                                                    style="text-align:center; color:#9966FF;">
                                                                    Date </th>
                                                                <th width="10%"
                                                                    style="text-align:center; color:#9966FF;">
                                                                    Amount </th>
                                                                <th width="8%"
                                                                    style="text-align:center; color:#9966FF;">GST
                                                                </th>
                                                                <th width="9%"
                                                                    style="text-align:center; color:#9966FF;">
                                                                    T/Inv</th>
                                                                <th width="10%"
                                                                    style="text-align:center; color:#9966FF;">
                                                                    Balance</th>
                                                                <th width="10%"
                                                                    style="text-align:center; color:#9966FF;">%
                                                                </th>
                                                                <th width="30%"
                                                                    style="text-align:center; color:#9966FF;">
                                                                    Note</th>
                                                                <th width="5%"
                                                                    style="text-align:center; color:#9966FF;">
                                                                    Action </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($data as $item)
                                                                @if ($item->ac_type == 1)
                                                                    <tr>
                                                                        <td width="12.8%">
                                                                            {{ \Carbon\Carbon::parse($item->trn_date)->format('d/m/Y') }}
                                                                        </td>
                                                                        <td width="9.9%">
                                                                            @php
                                                                                $chard_code = $client_account->code;
                                                                            @endphp
                                                                            @if ($item->chart_id == $chard_code && $item->amount_debit != 0)
                                                                                {{ $item->amount_debit }}
                                                                            @elseif($item->chart_id == $chard_code && $item->amount_credit != 0)
                                                                                {{ $item->amount_credit }} <span
                                                                                    style="color:red; font-weight: bold">R</span>
                                                                            @endif
                                                                            @if ($item->chart_id != $chard_code && $item->amount_credit != 0)
                                                                                {{ $item->amount_credit }}
                                                                            @elseif($item->chart_id != $chard_code && $item->amount_debit != 0)
                                                                                {{ $item->amount_debit }} <span
                                                                                    style="color:red; font-weight: bold">R</span>
                                                                            @endif
                                                                        </td>
                                                                        <td width="7.9%" height="36">
                                                                            @if (
                                                                                $item->total_inv != 0 &&
                                                                                    $item->gst_accrued_debit == 0 &&
                                                                                    $item->gst_accrued_credit == 0 &&
                                                                                    $item->gst_cach_credit == 0)
                                                                                {{ $item->gst_cash_debit }}
                                                                            @endif
                                                                            @if (
                                                                                $item->total_inv != 0 &&
                                                                                    $item->gst_accrued_debit == 0 &&
                                                                                    $item->gst_accrued_credit == 0 &&
                                                                                    $item->gst_cash_debit == 0)
                                                                                {{ $item->gst_cash_credit }}
                                                                            @endif
                                                                            @if ($item->total_inv != 0 && $item->gst_accrued_debit == 0 && $item->gst_cash_credit == 0 && $item->gst_cash_debit == 0)
                                                                                {{ $item->gst_accrued_credit }}
                                                                            @endif
                                                                            @if (
                                                                                $item->total_inv != 0 &&
                                                                                    $item->gst_accrued_credit == 0 &&
                                                                                    $item->gst_cash_credit == 0 &&
                                                                                    $item->gst_cash_debit == 0)
                                                                                {{ $item->gst_accrued_debit }}
                                                                            @endif
                                                                        </td>
                                                                        <td width="8.8%">
                                                                            {{ $item->total_inv == 0 ? 0 : $item->total_inv }}
                                                                        </td>
                                                                        <td width="9.8%">
                                                                            {{ $item->balance == 0 ? 0 : abs($item->balance) }}
                                                                        </td>
                                                                        <td width="9.9%">
                                                                            {{ $item->percent == 0 ? 0 : $item->percent }}
                                                                        </td>
                                                                        <td width="29.6%">{{ $item->narration }}</td>
                                                                        <td width="5%">
                                                                            <form
                                                                                action="{{ route('dataStore.destroy', $item->id) }}"
                                                                                method="POST">
                                                                                @csrf
                                                                                @method('DELETE')
                                                                                <button title="Data Store Delete"
                                                                                    style="background: transparent;border: none;color: red;font-size: 14px;line-height: 0;"
                                                                                    type="submit" class=""><i
                                                                                        class="fa fa-trash-o delete"
                                                                                        aria-hidden="true"></i></button>
                                                                            </form>
                                                                        </td>
                                                                    </tr>
                                                                @elseif($item->ac_type == 2)
                                                                    <tr>
                                                                        <td width="12.8%">
                                                                            {{ \Carbon\Carbon::parse($item->trn_date)->format('d/m/Y') }}
                                                                        </td>
                                                                        <td width="9.9%">
                                                                            @php
                                                                                $chard_code = $client_account->code;
                                                                            @endphp
                                                                            @if ($item->chart_id == $chard_code && $item->amount_debit != 0)
                                                                                {{ $item->amount_debit }} <span
                                                                                    style="color:red; font-weight: bold">R</span>
                                                                            @elseif($item->chart_id == $chard_code && $item->amount_credit != 0)
                                                                                {{ $item->amount_credit }}
                                                                            @endif
                                                                            @if ($item->chart_id != $chard_code && $item->amount_credit != 0)
                                                                                {{ $item->amount_credit }} <span
                                                                                    style="color:red; font-weight: bold">R</span>
                                                                            @elseif($item->chart_id != $chard_code && $item->amount_debit != 0)
                                                                                {{ $item->amount_debit }}
                                                                            @endif
                                                                        </td>
                                                                        <td width="7.9%" height="36">
                                                                            @if (
                                                                                $item->total_inv != 0 &&
                                                                                    $item->gst_accrued_debit == 0 &&
                                                                                    $item->gst_accrued_credit == 0 &&
                                                                                    $item->gst_cash_credit != 0)
                                                                                {{ $item->gst_cash_credit }}
                                                                            @endif
                                                                            @if (
                                                                                $item->total_inv != 0 &&
                                                                                    $item->gst_accrued_debit == 0 &&
                                                                                    $item->gst_accrued_credit == 0 &&
                                                                                    $item->gst_cash_debit != 0)
                                                                                {{ $item->gst_cash_debit }}
                                                                            @endif
                                                                            @if ($item->total_inv != 0 && $item->gst_accrued_debit == 0 && $item->gst_cash_credit == 0 && $item->gst_cash_debit == 0)
                                                                                {{ $item->gst_accrued_credit }}
                                                                            @endif
                                                                            @if (
                                                                                $item->total_inv != 0 &&
                                                                                    $item->gst_accrued_credit == 0 &&
                                                                                    $item->gst_cash_credit == 0 &&
                                                                                    $item->gst_cash_debit == 0)
                                                                                {{ $item->gst_accrued_debit }}
                                                                            @endif
                                                                        </td>
                                                                        <td width="8.8%">
                                                                            {{ $item->total_inv == 0 ? 0 : $item->total_inv }}
                                                                        </td>
                                                                        <td width="9.8%">
                                                                            {{ $item->balance == 0 ? 0 : abs($item->balance) }}
                                                                        </td>
                                                                        <td width="9.9%">
                                                                            {{ $item->percent == 0 ? 0 : $item->percent }}
                                                                        </td>
                                                                        <td width="29.6%">{{ $item->narration }} </td>
                                                                        <td width="5%">
                                                                            <form
                                                                                action="{{ route('dataStore.destroy', $item->id) }}"
                                                                                method="POST">
                                                                                @csrf
                                                                                @method('DELETE')
                                                                                <button title="Data Store Delete"
                                                                                    style="background: transparent;border: none;color: red;font-size: 14px;line-height: 0;"
                                                                                    type="submit" class=""><i
                                                                                        class="fa fa-trash-o delete"
                                                                                        aria-hidden="true"></i></button>
                                                                            </form>
                                                                        </td>
                                                                    </tr>
                                                                @endif
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                    @include('admin.period.add-code')
                                                    {{-- @include('admin.period.new-add-code') --}}
                                                </div>
                                            </div>
                                        </div>
                                        @include('admin.period.fuel_tax_create')
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /Add Payg Modal -->
                        <script>
                            $(function() {
                                readData();
                                readPayg()
                                $("#datepicker").keyup(function() {
                                    let date = $("#datepicker").val();
                                    let startDate = $("#startDate").val();
                                    let endDate = $("#endDate").val();
                                    if (date.length >= 10) {
                                        // console.log(startDate+'=='+endDate+'<==>'+date);
                                        if (startDate <= date && endDate >= date) {
                                            console.log('In Between');
                                        } else {
                                            $('#taxMsg').show().html('Date Must between ' + startDate + ' TO ' + endDate);
                                            console.log('In NOt Between');
                                        }
                                    } else {
                                        $('#taxMsg').hide();
                                    }
                                });
                                $('#fueltaxform').on('submit', function(e) {
                                    e.preventDefault();
                                    form = $(this);

                                    let rate = $("#fuel_rate").val()
                                    if (rate == 0) {
                                        toast('error', 'Fuel tax rate not found in enter date.');
                                        return false;
                                    }
                                    if (checForm(form)) {
                                        $.ajax({
                                            url: form.attr('action'),
                                            method: form.attr('method'),
                                            data: form.serialize(),
                                            success: function(msg) {
                                                if (msg == 1) {
                                                    toast('success', 'Insert Successful');
                                                    $("form").trigger("reset");
                                                } else {
                                                    toast('error', 'Something is wrong');
                                                    $("form").trigger("reset");
                                                }
                                                readData();
                                                console.log(msg);
                                            },
                                            error: err => {
                                                toast('error', 'Fuel Tax Error', err.responseJSON);
                                            }
                                        });
                                    } else {
                                        toast('error', 'Something is wrong');
                                    }
                                });
                                // $('#amount_addForm').on('keyup', 'input', function(e) {
                                //     console.log(e.keyCode);
                                //     e.preventDefault();
                                //     if(e.which == 13){
                                //         let index = parseFloat($this.attr('TabIndex'));
                                //         console.log(index);
                                //             e.preventDefault();
                                //         if($(this).attr('TabIndex') < 7){
                                //             e.preventDefault();
                                //         }else{
                                //             $('[TabIndex=' + (+this.TabIndex + 1) + ']')[0].focus();
                                //         }
                                //     }
                                // });
                            });
                            $("#payg_percenttige").on('keyup', function() {
                                var payg_percenttige = $(this).val();
                                $("#payg_amount").attr('disabled', 'disabled');
                                $("#payg_amount").val('');
                                if (payg_percenttige == 0) {
                                    $("#payg_amount").removeAttr('disabled', 'disabled');
                                }
                            });
                            $("#payg_amount").on('keyup', function() {
                                var payg_amount = $(this).val();
                                $("#payg_percenttige").attr('disabled', 'disabled');
                                $("#payg_percenttige").val('');
                                if (payg_amount == 0) {
                                    $("#payg_percenttige").removeAttr('disabled', 'disabled');
                                }
                            });
                            $('#payg_form').on('submit', function(e) {
                                e.preventDefault();
                                form = $(this);
                                if (checForm(form)) {
                                    $.ajax({
                                        url: form.attr('action'),
                                        method: form.attr('method'),
                                        data: form.serialize(),
                                        success: function(msg) {
                                            if (msg == 1) {
                                                toast('success', 'Insert Successful');
                                                $("form").trigger("reset");
                                            } else {
                                                toast('error', 'Something is wrong');
                                                $("form").trigger("reset");
                                            }
                                            readPayg();
                                        },
                                        error: err => {
                                            toast('error', 'Payg Error', err.responseJSON);
                                        }
                                    });
                                } else {
                                    toast('error', 'Something is wrong');
                                }
                            });

                            function readPayg() {
                                let client_id = "{{ $client->id }}";
                                let period_id = "{{ $period->id }}";
                                let form = $("form");
                                $.ajax({
                                    url: "{{ route('payg.index') }}",
                                    method: 'get',
                                    data: {
                                        client_id: client_id,
                                        period_id: period_id
                                    },
                                    success: function(msg) {
                                        msg = $.parseJSON(msg);
                                        if (msg.status == 'success') {
                                            if (msg.data['percent'] != '') {
                                                form.find("#payg_percenttige").val(msg.data['percent']);
                                            }
                                            if (msg.data['amount'] != '') {
                                                form.find("#payg_amount").val(msg.data['amount']);
                                            }
                                        }
                                    }
                                });
                            }

                            function checForm(form) {
                                let inputList = form.find('input');
                                for (let i = 0; i < inputList.length; i++) {
                                    if (inputList[i].value === 0 || inputList[i].value === null ||
                                        inputList[i].value === ' ') {
                                        return false;
                                    } else {
                                        return true;
                                    }
                                }
                            }

                            function readData() {
                                let client_id = "{{ $client->id }}";
                                let period_id = "{{ $period->id }}";
                                $.ajax({
                                    url: "{{ route('fuel.index') }}",
                                    method: 'get',
                                    data: {
                                        client_id: client_id,
                                        period_id: period_id
                                    },
                                    success: function(data) {
                                        data = $.parseJSON(data);
                                        if (data.status == 'success') {
                                            $('.itrTable').html(data.html);
                                        }
                                    }
                                });
                            }

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
                        <!-- PAGE CONTENT ENDS -->
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.page-content -->
        </div>
    </div><!-- /.main-content -->
    <!-- Script -->
    <script>
        var myTable =
            $('#period-table')
            .DataTable({
                bAutoWidth: false,
                "aoColumns": [{
                        "bSortable": false
                    },
                    null, null, null, null, null,
                    {
                        "bSortable": false
                    }
                ],
                "aaSorting": [],
                select: {
                    style: 'multi'
                }
            });
        $(".datepicker").datepicker({
            dateFormat: "dd/mm/yy",
            changeMonth: true,
            changeYear: true
        });
        $('#financial-year').keyup(function() {
            let year = $(this).val();
            if (year.length == 4) {
                $('#msg').show().html('Your selected financial year july ' + (year - 1) + ' to June ' + year);
            } else {
                $('#msg').hide();
                //$('#msg').show().html('Must be 4 Digit');
            }
        });
    </script>
@stop
