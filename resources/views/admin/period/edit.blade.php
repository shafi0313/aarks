@extends('admin.layout.master')
@section('title','Period List')
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
                    @if(empty($client->company))
                    {{$client->first_name.' '.$client->last_name}}
                    @else
                    {{$client->company}}
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
                                    <h3 class="panel-title">Activity Name: {{$profession->name}}
                                        <span style="padding-left:100px; color:pink;">Period:
                                            {{ $period->start_date->format(aarks('frontend_date_format'))}} to
                                            {{ $period->end_date->format(aarks('frontend_date_format'))}} </span>
                                    </h3>
                                </div>
                                <div class="panel-body">
                                    <div class="col-md-12">
                                        <div class="col-md-4">
                                            <h3 style="color:green;">Select Account Name: </h3>
                                        </div>
                                        <div class="col-md-8" style="padding-top:20px;">
                                            <div class="form-group">
                                                <select class="form-control chosen-select" id="subid" name="subid"
                                                    onchange="location = this.value">
                                                    <option>
                                                        <p>Select Account Name</p>
                                                    </option>
                                                    @foreach ($account_codes as $ac_code)
                                                    @if ($ac_code->type ==1)
                                                    <option
                                                        value="{{route('sub_pro_show',[$ac_code->id,$ac_code->code,$period->id,$profession->id,$client->id])}}"
                                                        style="color: green;">
                                                        {{$ac_code->name}}
                                                    </option>
                                                    @else
                                                    <option
                                                        value="{{route('sub_pro_show',[$ac_code->id,$ac_code->code,$period->id,$profession->id,$client->id])}}"
                                                        style="color: hotpink;">
                                                        {{$ac_code->name}}
                                                    </option>
                                                    @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12" style="min-height:300px;">
                                        <div id="addatalistpage">
                                        </div>
                                    </div>
                                    @include('admin.period.fuel_tax_create')
                                </div>
                            </div>
                        </div>
                    </div>

                <script>
                    $(function(){
                        readData();
                        readPayg()

                        $("#payg_percenttige").on('keyup', function(){
                            var payg_percenttige = $(this).val();
                                $("#payg_amount").attr('disabled', 'disabled');
                                $("#payg_amount").val('');
                            if(payg_percenttige ==''){
                                $("#payg_amount").removeAttr('disabled', 'disabled');
                            }
                        });
                        $("#payg_amount").on('keyup', function(){
                            var payg_amount = $(this).val();
                                $("#payg_percenttige").attr('disabled', 'disabled');
                                $("#payg_percenttige").val('');
                            if(payg_amount ==''){
                                $("#payg_percenttige").removeAttr('disabled', 'disabled');
                            }
                        });

                        $('#payg_form').on('submit', function (e) {
                            e.preventDefault();
                            form = $(this);
                            if (checForm(form)) {
                                $.ajax({
                                    url: form.attr('action'),
                                    method: form.attr('method'),
                                    data: form.serialize(),
                                    success: function (msg) {
                                        if (msg == 1){
                                            toast('success','Insert Successful');
                                            $("form").trigger("reset");
                                        }else {
                                            toast('error','Something is wrong');
                                            $("form").trigger("reset");
                                        }
                                        readPayg();
                                    }
                                });
                            } else {
                                toast('error','Something is wrong');
                            }
                        });
                        $("#datepicker").keyup(function(){
                            let date = $("#datepicker").val();
                            let startDate = $("#startDate").val();
                            let endDate = $("#endDate").val();
                            if(date.length == 10){
                                if (startDate <= date && endDate >= date) {
                                    console.log('In Between');
                                } else {
                                    $('#taxMsg').show().html('Date Must between '+startDate+' TO '+endDate);
                                    console.log('In NOt Between');
                                }
                            }else{
                                $('#taxMsg').hide();
                            }
                        });
                        $('#fueltaxform').on('submit', function (e) {
                            e.preventDefault();
                            form = $(this);
                            let rate = $("#fuel_rate").val()
                            if(rate == 0){
                                toast('error','Fuel tax rate not found in enter date.');
                                return false;
                            }
                            if (checForm(form)) {
                                $.ajax({
                                    url: form.attr('action'),
                                    method: form.attr('method'),
                                    data: form.serialize(),
                                    success: function (msg) {
                                        if (msg == 1){
                                            toast('success','Insert Successful');
                                            $("form").trigger("reset");
                                        }else {
                                            toast('error','Something is wrong');
                                            $("form").trigger("reset");
                                        }
                                        readData();
                                    }
                                });
                            } else {
                                toast('error','Something is wrong');
                            }
                        });
                    });

                    function checForm(form) {
                        let inputList = form.find('input');
                        for (let i = 0; i < inputList.length; i++) {
                            if (inputList[i].value==='' || inputList[i].value===null ||
                            inputList[i].value===' ' ) {
                                return false;
                            } else {
                                return true;
                            }
                        }
                    }

                    function readPayg(){
                        let client_id = "{{$client->id}}";
                        let period_id = "{{$period->id}}";
                        let form = $("form");
                        $.ajax({
                            url:"{{route('payg.index')}}",
                            method: 'get',
                            data:{
                                client_id:client_id,
                                period_id:period_id
                            },
                            success: function (msg) {
                                msg = $.parseJSON(msg);
                                if (msg.status == 'success') {
                                    if(msg.data['percent'] !== ''){
                                        form.find("#payg_percenttige").val(msg.data['percent']);
                                    }
                                    if(msg.data['amount'] !== ''){
                                        form.find("#payg_amount").val(msg.data['amount']);
                                    }
                                }
                            }
                        });
                    }

                    function readData(){
                        let client_id = "{{$client->id}}";
                        let period_id = "{{$period->id}}";
                        $.ajax({
                            url:"{{route('fuel.index')}}",
                            method: 'get',
                            data:{
                                client_id:client_id,
                                period_id:period_id
                            },
                            success: function (data) {
                                data = $.parseJSON(data);
                                if (data.status == 'success') {
                                    $('.itrTable').html(data.html);
                                }
                            }
                        });
                    }

                    function toast(status,header,msg) {
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
@stop
