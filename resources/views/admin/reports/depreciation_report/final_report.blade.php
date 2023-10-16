@extends('admin.layout.master')
@section('title','Depreciation Report')
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
                <li>Depreciation Report</li>
                <li>{{ $profession->name }}</li>
                <li class="active">{{ clientName($client) }}</li>
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
                <div class="col-md-12">
                    <div class="pull-right tableTools-container">
                        <div class="btn-group btn-overlap">
                            <a href="{{ route('depreciation_report.print',[$client->id, $profession->id, $year]) }}" class="btn btn-primary btn-white btn-round">
                                <i class="ace-icon fa fa-print bigger-110 blue"></i>
                                <span class="bigger-110">Print</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="" align="center">
                        <div style="font-size:30px;"> {{$client->fullname}}</div>
                        <br />
                        <div style="font-size: 25px;">ABN {{$client->abn_number}}<br />
                            <b>Depreciation Schedule for the year ended 30 June, {{$year}}</b>
                        </div>
                        <table class="table table-hover table-bordered table-sm" style="width:100%">
                            <thead>
                                <tr>
                                    <th style="text-align:center" rowspan="2">Asset Name</th>
                                    <th style="text-align:center" colspan="3">Cost</th>
                                    <th style="text-align:center" colspan="3">ADDITION</th>
                                    <th style="text-align:center" colspan="2">DISPOSAL</th>
                                    <th style="text-align:center" rowspan="2">M</th>
                                    <th style="text-align:center" colspan="3">DEPRECIATION</th>
                                    <th style="text-align:center" colspan="2">PROFIT</th>
                                    <th style="text-align:center" colspan="2">LOSS</th>
                                </tr>

                                <tr>
                                    <td style="text-align:center">P.Date</td>
                                    <td style="text-align:center">Total</td>
                                    <td style="text-align:center">OWDV</td>

                                    <td style="text-align:center">Date</td>
                                    <td style="text-align:center">Rate</td>
                                    <td style="text-align:center">Value</td>

                                    <td style="text-align:center">Date</td>
                                    <td style="text-align:center">Consid</td>
                                    <td style="text-align:center">Rate</td>
                                    <td style="text-align:center">Deprec</td>
                                    <td style="text-align:center">CWDV</td>
                                    {{-- <td style="text-align:center">Upto +</td> --}}
                                    <td style="text-align:center">Above</td>
                                    <td style="text-align:center">Total</td>
                                    <td style="text-align:center">Priv</td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($depreciations as $item)
                                <tr>
                                    <td colspan="17">
                                        <strong class="dep_gropu text-success">{{$item->asset_group}}</strong> <br>
                                    </td>
                                </tr>
                                @php
                                $depAssetNames = $item->asset_names;
                                $above = $profit_total = $cost_total = $owdv = $value = $consid = $deprec = $cwdv = 0;
                                @endphp
                                {{-- Current Year Report --}}
                                @foreach ($depAssetNames as $asset_name)
                                @if ($asset_name->owdv_value_date != null)
                                @php
                                $purch_date1 = $asset_name->purchase_date->format('d/m/Y');
                                $purch_date2 = $asset_name->owdv_value_date->format('Y-m-d');

                                if (in_array($asset_name->purchase_date->format('m'), range(1, 6))) {
                                $year = $asset_name->purchase_date->format('Y');
                                } else {
                                $year = $asset_name->purchase_date->format('Y')+1;
                                }

                                $above += $asset_name->profit??0;
                                $profit_total += $asset_name->loss??0;
                                $cost_total += $asset_name->total_value??0;
                                $owdv += $asset_name->owdv_value??0;
                                // $value += $asset_name->purchase_value??0;
                                $consid += $asset_name->disposal_value??0;
                                $deprec += $asset_name->dep_amt??0;
                                $cwdv += $asset_name->cwdv_value??0;

                                if ($asset_name->year == $year){
                                    $value += $asset_name->purchase_value??0;
                                }

                                @endphp
                                {{-- @if ($purch_date2 <= $year.'-06-30' ) --}} <tr>
                                    <td style="text-align:right">
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        {{$asset_name->asset_name}}
                                    </td>
                                    <td style="text-align:center">{{$purch_date1}}</td>
                                    <td style="text-align:center">{{number_format($asset_name->total_value, 2)}}</td>
                                    <td style="text-align:center">{{number_format($asset_name->owdv_value, 2)}}</td>
                                    @if ($asset_name->year == $year)
                                    <td style="text-align:center">{{$purch_date1}}</td>
                                    <td style="text-align:center">{{number_format($asset_name->purchase_rate, 2)}}</td>
                                    <td style="text-align:center">{{number_format($asset_name->purchase_value, 2)}}</td>
                                    @else
                                    <td style="text-align:center">0</td>
                                    <td style="text-align:center">0</td>
                                    <td style="text-align:center">0</td>
                                    @endif
                                    <td style="text-align:center">
                                        {{optional($asset_name->disposal_date)->format('d/m/Y')}}</td>
                                    <td style="text-align:center">{{number_format($asset_name->disposal_value, 2)}}</td>
                                    <td style="text-align:center">{{$asset_name->dep_method}}</td>
                                    <td style="text-align:center">{{number_format($asset_name->dep_rate, 2)}}</td>
                                    <td style="text-align:center">{{number_format($asset_name->dep_amt, 2)}}</td>
                                    <td style="text-align:center">{{number_format($asset_name->cwdv_value, 2)}}</td>

                                    <td style="text-align:center">
                                        {{number_format($above, 2)}}
                                    </td>
                                    <td style="text-align:center">
                                        {{number_format($profit_total, 2)}}
                                    </td>
                                    <td style="text-align:center">{{number_format($profit_total, 2)}}</td>

                                    {{-- <td style="text-align:center">{{$asset_name->id}}</td> --}}
                                    </tr>
                                    {{-- @endif --}}
                                    @endif

                                    @endforeach

                                    <tr>
                                        <td style="text-align:right">TOTAL</td>
                                        <td></td>
                                        <td style="text-align:center">{{number_format($cost_total, 2)}}</td>
                                        <td style="text-align:center">{{number_format($owdv, 2)}}</td>
                                        <td></td>
                                        <td></td>
                                        <td style="text-align:center">{{number_format($value, 2)}}</td>
                                        <td></td>
                                        <td style="text-align:center">{{number_format($consid, 2)}}</td>
                                        <td></td>
                                        <td></td>
                                        <td style="text-align:center">{{number_format($deprec, 2)}}</td>
                                        <td style="text-align:center">{{number_format($cwdv, 2)}}</td>
                                        <td style="text-align:center">{{number_format($above, 2)}}</td>
                                        <td style="text-align:center">{{number_format($profit_total, 2)}}</td>
                                        <td style="text-align:center"></td>
                                    </tr>
                                    @endforeach
                            </tbody>
                            <tbody>
                        </table>
                    </div>
                </div>
            </div><!-- /.row -->
        </div><!-- /.page-content -->
    </div>
</div><!-- /.main-content -->
@endsection
