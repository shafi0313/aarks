<!doctype html>
<html lang="en">

    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
        <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
        <title>Payslip Print</title>
        <style>
            .p_heading span {
                font-size: 20px;
            }

            .p_heading h2 {
                font-size: 30px;
                text-align: right;
            }

            table {
                width: 100%;
            }

            table th,
            table td {
                padding: 1px 2px;
            }

            table thead,
            .t_h {
                text-align: center;
                background: #83f0f5;

            }

            table thead tr th,
            .t_h {
                font-weight: 600;
                font-size: 14px;
            }

            table tr td {
                font-size: 14px;
            }
        </style>
    </head>

    <body>

        <!-- Page Content Start -->
        @foreach ($payslips as $payslip)
        @php
            $yearTo = App\Frontend\PayAccumAmt::where('financial_year', $payslip->pay_accum->financial_year)
            ->where('tran_id', $payslip->pay_accum->tran_id)
            ->where('profession_id', $payslip->pay_accum->profession_id)
            ->where('client_id', $payslip->pay_accum->client_id)
            ->where('employee_card_id', $payslip->pay_accum->employee_card_id)->get();

            $payWages = App\Frontend\PayslipWages::where('tran_id', $payslip->pay_accum->tran_id)->where('status', 1)->get();
            $payDeduc = App\Frontend\PayslipDeduc::where('tran_id', $payslip->pay_accum->tran_id)->where('status', 1)->get();
            $payLeave = App\Frontend\PayslipLeave::where('tran_id', $payslip->pay_accum->tran_id)->where('status', 1)->get();
            $paySuper = App\Frontend\PayslipSuper::where('tran_id', $payslip->pay_accum->tran_id)->where('status', 1)->get();
        @endphp
        <section class="page-content my-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row p_heading align-items-center">
                                    <div class="col-md-2 image">
                                        <div class="">
                                            <img src="{{asset($payslip->client->logo)}}" alt="..." class="img-fluid">
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <span class="">{{$payslip->client->fullname}}</span><br />
                                        ABN : {{$payslip->client->abn_number}}<br />
                                        Address : {{$payslip->client->email}}
                                    </div>
                                    <div class="col-md-4 clearfix">
                                        <h2 class="display-5">Pay Advice</h2>
                                    </div>
                                </div>
                                <br>
                                <br>

                                <div class="row">
                                    <div class="col-md-6 p-0 m-0">
                                        <div class="border">
                                            <div class="text-center">Payments</div>
                                            <table class="table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Description</th>
                                                        <th>Hours</th>
                                                        <th>Rate</th>
                                                        <th>Amount</th>
                                                    </tr>
                                                </thead>
                                                @foreach ($payWages as $wages)
                                                <tr>
                                                    <td>{{$wages->name}}</td>
                                                    <td class="text-right">{{number_format($wages->hours,2)}}</td>
                                                    <td class="text-right">{{number_format($wages->rate,2)}}</td>
                                                    <td class="text-right">{{number_format($wages->amount,2)}}</td>
                                                </tr>
                                                @endforeach
                                            </table>
                                        </div>
                                    </div>

                                    <div class="col-md-3 p-0 m-0">
                                        <div class="border">
                                            <div class="text-center">Deductions</div>
                                            <table class="table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Description</th>
                                                        <th>Amount</th>
                                                    </tr>
                                                </thead>
                                                @foreach ($payDeduc as $deduc)
                                                <tr>
                                                    <td>{{$deduc->name}}</td>
                                                    <td class="text-right">{{number_format($deduc->amount,2)}}</td>
                                                </tr>
                                                @endforeach
                                            </table>
                                            <div class="text-center">Orther Info</>
                                                <table class="table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>Description</th>
                                                            <th>Amt/hrs</th>
                                                        </tr>
                                                    </thead>
                                                    @foreach ($paySuper as $super)
                                                    <tr>
                                                        <td>{{$super->name}}</td>
                                                        <td class="text-right">{{number_format($super->amount,2)}}</td>
                                                    </tr>
                                                    @endforeach
                                                    @foreach ($payLeave as $leave)
                                                    <tr>
                                                        <td>{{$leave->name}}</td>
                                                        <td class="text-right">{{number_format($leave->amount,2)}}</td>
                                                    </tr>
                                                    @endforeach
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3 p-0 m-0">
                                        <div class="border">
                                            <table class="table-bordered">
                                                <tr class="t_h">
                                                    <th colspan="2">Year To Date</th>
                                                </tr>
                                                <tr>
                                                    <td>Gross Pay</td>
                                                    <td class="text-right">
                                                        {{number_format($yearTo->sum('accum_salary'),2)}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Director’s Fees</td>
                                                    <td class="text-right">
                                                        {{number_format($yearTo->sum('accum_director_fee'),2)}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Overtime</td>
                                                    <td class="text-right">
                                                        {{number_format($yearTo->sum('accum_overtime'),2)}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Allowence</td>
                                                    <td class="text-right">
                                                        {{number_format($yearTo->sum('accum_allowence'),2)}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Bonus and Commission</td>
                                                    <td class="text-right">
                                                        {{number_format($yearTo->sum('accum_bonus'),2)}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Lump Sum Tuple</td>
                                                    <td class="text-right">
                                                        {{number_format($yearTo->sum('accum_lump'),2)}}</td>
                                                </tr>
                                                <tr>
                                                    <td>ETP tuple</td>
                                                    <td class="text-right">
                                                        {{number_format($yearTo->sum('accum_etp'),2)}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Salary Secriface</td>
                                                    <td class="text-right">
                                                        {{number_format($yearTo->sum('accum_secriface'),2)}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Exempt Foreign Income</td>
                                                    <td class="text-right">
                                                        {{number_format($yearTo->sum('accum_exempt'),2)}}</td>
                                                </tr>
                                                <tr>
                                                    <td>CDEP</td>
                                                    <td class="text-right">
                                                        {{number_format($yearTo->sum('accum_cdep'),2)}}</td>
                                                </tr>
                                                <tr>
                                                    <td>PAYG W2</td>
                                                    <td class="text-right">
                                                        {{number_format($yearTo->sum('accum_payg'),2)}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Annual Leave</td>
                                                    <td class="text-right">
                                                        {{number_format($yearTo->sum('accum_annual'),2)}}</td>
                                                </tr>

                                                <tr>
                                                    <td>Personal Leave</td>
                                                    <td class="text-right">
                                                        {{number_format($yearTo->sum('accum_personal'),2)}}</td>
                                                </tr>

                                                <tr>
                                                    <td>Superannuation Guarnt</td>
                                                    <td class="text-right">
                                                        {{number_format($yearTo->sum('accum_super'),2)}}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table-bordered text-center">
                                        <thead>
                                            <tr>
                                                <th style="width:12%;">Payment Date</th>
                                                <th style="width:12%;">Start Date</th>
                                                <th style="width:13%;">End Date</th>
                                                <th style="width:13%;">Pay frequancy</th>
                                                <th style="width:20%;">Payment Method</th>
                                                <th style="width:20%;">Gross Pay</th>
                                                <th style="width:20%;">Net Pay</th>
                                            </tr>
                                        </thead>
                                        <tr>
                                            <td>{{$payslip->pay_accum->tran_date->format('d/m/Y')}}</td>
                                            <td>{{$payslip->pay_accum->payperiod_start->format('d/m/Y')}}</td>
                                            <td>{{$payslip->pay_accum->payperiod_end->format('d/m/Y')}}</td>
                                            <td>{{$payslip->pay_accum->pay_period}}</td>
                                            <td>Cash</td>
                                            <td class="text-right">{{number_format($payslip->pay_accum->gross,2)}}</td>
                                            <td class="text-right">{{number_format($payslip->pay_accum->net_pay,2)}}</td>
                                        </tr>
                                    </table>
                                    <table class="table-bordered text-center">
                                        <thead>
                                            <tr>
                                                <th style="width:20%;">Employee Name</th>
                                                <th style="width:12%;">Employment start</th>
                                                <th style="width:10%;">Annual Salary</th>
                                                <th style="width:10%;">Hourly Rate</th>
                                                <th style="width:13%;">Employee Class</th>
                                                <th style="width:16%;">Superfund name</th>

                                            </tr>
                                        </thead>
                                        <tr>
                                            <td>{{$payslip->employee->fullname}}</td>
                                            <td>{{$payslip->employee->start_date->format('d/m/Y')}}</td>
                                            <td class="text-right">
                                                {{number_format($payslip->employee->annual_salary??0,2)}}</td>
                                            <td class="text-right">
                                                {{number_format($payslip->employee->hourly_rate??0,2)}}</td>
                                            <td>{{$payslip->employee->emp_classification}}</td>
                                            <td class="text-right">0.00</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            </div>
            </div>
        </section>
        @endforeach
        <!-- Page Content End -->

        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.4.1.min.js">
        </script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js">
        </script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js">
        </script>
    </body>

</html>
