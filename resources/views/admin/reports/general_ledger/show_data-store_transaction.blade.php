@extends('admin.layout.master')
@section('title','Data Store Transaction View')
@section('content')

<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="{{ route('admin.dashboard') }}">Home</a>
                </li>

                <li>
                    <a href="#">Business Activity</a>
                </li>
                <li>
                    <a href="#"></a>
                </li>
            </ul><!-- /.breadcrumb -->
        </div>

        <div class="page-content">
            <div class="row">
                <div class="col-xs-12">
                    <h1>Data Store Transaction View</h1>
                    <div class="row">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="center">SN</th>
                                    <th>code</th>
                                    <th>amount debit</th>
                                    <th>amount credit</th>
                                    <th>gst accrued debit</th>
                                    <th>gst accrued credit</th>
                                    <th>gst cash debit</th>
                                    <th>gst cash credit</th>
                                    <th>net amount debit</th>
                                    <th>net amount credit</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $x=1 @endphp
                                @foreach ($datas as $i=> $data)
                                <tr>
                                    <td>{{$x++}} </td>
                                    <td class="text-right">{{$data->chart_id}} </td>
                                    <td class="text-right">{{ number_format($data->amount_debit,2) }} </td>
                                    <td class="text-right">{{ number_format($data->amount_credit,2) }} </td>
                                    <td class="text-right">{{ number_format($data->gst_accrued_debit,2) }} </td>
                                    <td class="text-right">{{ number_format($data->gst_accrued_credit,2) }} </td>
                                    <td class="text-right">{{ number_format($data->gst_cash_debit,2) }} </td>
                                    <td class="text-right">{{ number_format($data->gst_cash_credit,2) }} </td>
                                    <td class="text-right">{{ number_format($data->net_amount_debit,2) }} </td>
                                    <td class="text-right">{{ number_format($data->net_amount_credit,2) }} </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- PAGE CONTENT ENDS -->
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.page-content -->
    </div>
</div><!-- /.main-content -->
@endsection
