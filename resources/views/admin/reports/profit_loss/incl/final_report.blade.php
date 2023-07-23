@extends('admin.layout.master')
@section('title', 'Profit & Loss GST Include')
@section('content')



    <div class="main-content">
        <div class="main-content-inner">
            <div class="breadcrumbs ace-save-state" id="breadcrumbs">
                <ul class="breadcrumb">
                    <li>
                        <i class="ace-icon fa fa-home home-icon"></i>
                        <a href="{{ route('admin.dashboard') }}">Home</a>
                    </li>
                    <li>Report</li>
                    <<li>{{ $client->fullname }}</li>
                        <li class="active">{{ $profession->name }}</li>
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
                    <div class="col-lg-12">
                        <!-- PAGE CONTENT BEGINS -->
                        <div class="row" id="printarea">
                            <div class="col-lg-12">
                                <div align="center">
                                    @include('admin.reports.profit_loss.header')

                                    <div align="center" style="padding-top:20px;">
                                        <strong style="font-weight:800; font-size:18px;">{{ $client->full_name }}
                                        </strong>
                                        <br />
                                        <strong>ABN : {{ $client->abn_number }}</strong><br />
                                        <span> {{ $client->street_address }}, {{ $client->suburb }},
                                            {{ $client->state }}</span><br>
                                        <strong style="font-weight:800; font-size:18px;"><b>Profit and Loss
                                                Statement</b></strong><br />
                                        <strong><b><u>For the Period From {{ $from_date }} -
                                                    {{ $to_date }}</u></b></strong>
                                        <div align="center" style="padding-top:0px;">
                                            @include('admin.reports.profit_loss.incl.table')
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-11">
                                <div style="padding-right:200px;">

                                </div>
                            </div>

                            <div class="col-md-1">
                                <div style="padding-right:200px; padding-left:10px;">
                                    <button onclick="printDiv('printarea')" class="btn btn-primary">Print</button>
                                </div>
                            </div>
                        </div>
                        <div style="margin-top:20px;">
                            <div class="col-md-6"></div>
                            <div class="col-md-6" style="padding-top:20px;">
                                <form method="POST"
                                    action="https://www.aarks.com.au/Profit_loss_gst_exclude/sendMailExcludeGST">
                                    <input type="hidden" name="startDate2" value="2020-01-21" />
                                    <input type="hidden" name="endStart2" value="2020-03-25" />
                                    <input type="hidden" name="client_id2" value="628" />
                                    <input type="hidden" name="professionid2" value="14" />

                                    <div class="form-group">
                                        <input type="text" class="form-control" id="subject" name="subject"
                                            placeholder="Subject" required>
                                    </div>

                                    <div class="form-group">
                                        <input type="email" class="form-control" id="tomailaddress" name="tomailaddress"
                                            placeholder="To Email" required>
                                    </div>
                                    <button type="submit" class="btn btn-success pull-right">Send Mail</button>
                                </form>
                            </div>

                        </div>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.page-content -->
        </div>
    </div><!-- /.main-content -->

    @include('admin.reports.profit_loss.print_div')

@endsection
