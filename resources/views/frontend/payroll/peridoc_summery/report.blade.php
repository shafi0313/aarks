@extends('frontend.layout.master')
@section('content')
<?php $p="peps"; $mp="payroll";?>
    <!-- Page Content Start -->
    <section class="page-content">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        {{-- <div class="card-heading">
                            <h3>Customer Ledger</h3>
                        </div> --}}
                        <div class="card-body">
                            <div class="row justify-content-center">
                                <div class="text-center">
                                    <strong style="font-size: 20px;">{{$client->fullname}}</strong>
                                    <p>{{$client->address}}<br>
                                        <u>ABN: {{$client->abn_number}}</u><br />
                                        <strong style="font-size:20px;">Payroll [Summary]</strong><br>
                                        {{$start_date->format('d/m/Y')}} From{{$end_date->format('d/m/Y')}}
                                    </p>
                                </div>
                                <table class="table table-bordered table-sm">
                                    <tr>
                                        <th style="text-align:center;">SN</th>
                                        <th style="text-align:center;">Employee</th>
                                        <th style="text-align:center;">Net Pay</th>
                                        <th style="text-align: center;">Taxes</th>
                                        <th style="text-align:center;">Deductions</th>
                                        <th style="text-align:center;">Wages</th>
                                        <th style="text-align: center;">Superannuation</th>
                                    </tr>
                                    <tbody>
                                        @foreach ($payAccums as $i=>$pay)
<tr>
    <td style="text-align: center" >{{$i+1}}</td>
    <td style="text-align: center" >{{$pay->employee->fullname}}</td>
    <td style="text-align: center">${{number_format($pay->snet_pay,2)}}</td>
    <td style="text-align: center">${{number_format($pay->spayg,2)}}</td>
    <td style="text-align: center">${{number_format($pay->sdeduc,2)}}</td>
    <td style="text-align: center">${{number_format($pay->swages,2)}}</td>
    <td style="text-align: center">${{number_format($pay->ssuper,2)}}</td>
</tr>
                                        @endforeach
<tr>
    <th style="text-align: center" colspan="2">Total = </th>
    <th style="text-align: center">${{number_format($totalAccum->sum('net_pay'),2)}}</th>
    <th style="text-align: center">${{number_format($totalAccum->sum('payg'),2)}}</th>
    <th style="text-align: center">${{number_format($totalAccum->sum('deduc'),2)}}</th>
    <th style="text-align: center">${{number_format($totalAccum->sum('wages'),2)}}</th>
    <th style="text-align: center">${{number_format($totalAccum->sum('super'),2)}}</th>
</tr>
                                    </tbody>

                                </table>
                                <div class="col-md-6"></div>
                                <div class="col-md-6 pull-left">
                                    <form method="post" action="">
                                        <input type="hidden" name="professionid" value="">
                                        <input type="hidden" name="formdate" value="1970-01-01">
                                        <input type="hidden" name="todate" value="1970-01-01">
                                        <div class="form-group" align="left">
                                            <label>To Email:</label>
                                            <input type="email" class="form-control" id="sentto" name="sentto"
                                                placeholder="Email">
                                        </div>
                                        <div class="form-group" align="left">
                                            <label>To Name:</label>
                                            <input type="text" class="form-control" id="sendtoname" name="sendtoname"
                                                placeholder="Name">
                                        </div>
                                        <button type="submit" class="btn btn-success">Send Mail</button>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Page Content End -->

    <!-- Footer Start -->

    <!-- Footer End -->

@stop
