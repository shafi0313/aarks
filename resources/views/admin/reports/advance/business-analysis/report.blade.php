@extends('admin.layout.master')
@section('title', 'Monthly Business Analysis Details P & L')
@section('content')
    <div class="main-content">
        <div class="main-content-inner">
            <div class="breadcrumbs ace-save-state" id="breadcrumbs">
                <ul class="breadcrumb">
                    <li>
                        <i class="ace-icon fa fa-home home-icon"></i>
                        <a href="{{ route('admin.dashboard') }}">Home</a>
                    </li>
                    <li class="active">Monthly Business Analysis Details P & L</li>
                    <li class="active">Report</li>
                </ul>
            </div>
            <style>
                .table>tbody>tr>td,
                .table>tbody>tr>th,
                .table>tfoot>tr>td,
                .table>tfoot>tr>th,
                .table>thead>tr>td,
                .table>thead>tr>th {
                    padding: 1px 5px;
                    vertical-align: middle;
                }

                .form-control {
                    height: 28px;
                }

                .text-danger {
                    color: red;
                }
            </style>
            <div class="page-content" style="margin-top: 50px;">
                <div class="row">
                    <div class="col-lg-2 text-center pull-right">
                        {{-- <a href="{{ request()->fullUrl() . '&print=true' }}" class="btn btn-primary">Print</a> --}}
                        <a href="javascript:printDiv()" class="btn btn-primary">Print/PDF</a>
                    </div>
                </div>
                <div class="row" id="print-area">
                    <div class="col-lg-12">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center" rowspan="2" style="width: 10%;">Account Code</th>
                                        <th class="text-center" rowspan="2" style="width: 30%;">Account Name</th>
                                        @foreach ($months as $month)
                                            <th class="text-center" colspan="2">{{ $month }} </th>
                                        @endforeach
                                        <th rowspan="2">Total</th>
                                    </tr>
                                    <tr>
                                        @foreach ($months as $month)
                                            <td class="text-center">Amount</td>
                                            <td class="text-center">%</td>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $inJuly = $inAugust = $inSeptember = $inOctober = $inNovember = $inDecember = $inJanuary = $inFebruary = $inMarch = $inApril = $inMay = $inJune = $_total = 0;
                                        // $July = $August = $September = $October = $November = $December = $January = $February = $March = $April = $May = $June = 0;
                                    @endphp
                                    @foreach ($ledgers as $i => $loops)
                                        @php
                                            $July = $August = $September = $October = $November = $December = $January = $February = $March = $April = $May = $June = 0;
                                        @endphp
                                        @foreach ($loops->sortBy('id') as $first_loop)
                                            @php
                                                $ledger = $first_loop->first();
                                                $code = $ledger->client_account_code;
                                                // info($code->name. ' <=> ' .$code->code);
                                            @endphp
                                            <tr>
                                                <td>{{ $code->code }}</td>
                                                <td>{{ $code->name }}</td>
                                                @php
                                                    $_total = 0;
                                                @endphp
                                                @foreach ($months as $key => $month)
                                                    @php
                                                        $ledgerMonth = $first_loop->where('month', $key)->first();
                                                    @endphp
                                                    @if ($ledgerMonth)
                                                        @php
                                                            $_total += $ledgerMonth->_balance;
                                                            ${explode(' ', $month)[0]} += $ledgerMonth->_balance;
                                                        @endphp
                                                        <td class="text-right"> {{ abs_number($ledgerMonth->_balance) }}
                                                        </td>
                                                        @if ($code->category_id == 1)
                                                            @php
                                                                ${'in' . explode(' ', $month)[0]} += $ledgerMonth->_balance;
                                                                // info(explode(' ', $month)[0]);
                                                            @endphp
                                                            <td class="text-right">100%</td>
                                                        @else
                                                            <td class="text-right">
                                                                {{ number_format(($ledgerMonth->_balance * 100) / ${'in' . explode(' ', $month)[0]}, 2) }}%
                                                            </td>
                                                        @endif
                                                    @else
                                                        <td class="text-right"> 0.00 </td>
                                                        <td class="text-right">0.00%</td>
                                                    @endif
                                                @endforeach
                                                <td class="text-right">{{ abs_number($_total) }}</td>
                                            </tr>
                                        @endforeach
                                        <tr class="text-right">
                                            @php
                                                $chartName = match($i) {
                                                    1 => 'Income',
                                                    2 => 'Expense',
                                                    5 => 'Asset and property',
                                                    9 => 'Liability and Equity',
                                                    default => 'Unsupported',
                                                };
                                            @endphp
                                            <td colspan="2" style="font-size: 14px; color:blue"><b>Total {{ $chartName }}</b></td>
                                            @foreach ($months as $key => $month)
                                            @if ($i==1)
                                            <input type="hidden" value="{{(${explode(' ', $month)[0]})}}" id="total_income-{{$key}}">
                                            @elseif($i==2)
                                            <input type="hidden" value="{{(${explode(' ', $month)[0]})}}" id="total_expense-{{$key}}">
                                            @endif
                                                <td style="font-size: 14px; color:blue">
                                                    <b>{{ abs_number(${explode(' ', $month)[0]}) }}</b>
                                                </td>
                                                <td></td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td colspan="100%">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <b>Earnings Before GST & Tax</b>
                                        </td>
                                        @foreach ($months as $key => $month)
                                            <td class="text-right">
                                                <b id="total_ebit-{{ $key }}">{{ number_format(0, 2) }}</b>
                                            </td>
                                            <td></td>
                                        @endforeach
                                    </tr>

                                    <tr>
                                        <td colspan="2"><b>GST and tax</b></td>

                                        @foreach ($months as $key => $month)
                                        @php
                                            $payable = $payables->where('month', $key)->first();
                                            $clearing = $clearings->where('month', $key)->first();
                                            if ($payable) {
                                                $payable = $payable->_balance;
                                            } else {
                                                $payable = 0;
                                            }
                                            if ($clearing) {
                                                $clearing = $clearing->_balance;
                                            } else {
                                                $clearing = 0;
                                            }
                                            $total_tax = $payable - $clearing;
                                        @endphp
                                            <td class="text-right" id="total_tax-{{ $key }}">{{ number_format($total_tax,2)}}</td>
                                            <td></td>
                                        @endforeach
                                    </tr>
                                    <tr>
                                        <td colspan="2"><b>Profit after GST and Tax</b></td>

                                        @foreach ($months as $key => $month)
                                            <td class="text-right" id="total_profit-{{ $key }}">a-b</td>
                                            <td></td>
                                        @endforeach
                                    </tr>
                                    <tr>
                                        <td colspan="2"><b>Gross profit margin</b></td>

                                        @foreach ($months as $key => $month)
                                            <td class="text-right" id="total_margin-{{ $key }}">
                                                a*100/total income
                                            </td>
                                            <td></td>
                                        @endforeach
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(function(){
            $('[id^="total_income-"]').each(function() {
                const id = $(this).attr('id');
                const split = id.split('-');
                const type = split[0];
                const number = split[1];
                const income = parseFloat($(this).val() ?? 0);
                const expense = parseFloat($('#total_expense-' + number).val() ?? 0);
                // console.log(income, expense);

                // Calculate EBIT
                const ebit = parseFloat(income) - parseFloat(expense);
                $("#total_ebit-" + number).html(ebit.toFixed(2));

                const tax    = parseFloat($('#total_tax-' + number).text() ?? 0);
                const profit = parseFloat(ebit) - parseFloat(tax);
                $("#total_profit-" + number).html(profit.toFixed(2));

                const margin = parseFloat(ebit) * 100 / parseFloat(income);
                $("#total_margin-" + number).html(margin.toFixed(2) + '%');
            });
        });
        function printDiv() {
            let divToPrint = $("#print-area");
            newWin = window.open("");
            newWin.document.write(
                '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css">'
                );
            newWin.document.write(`<style>
                body {
                    font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
                }
                table,tr,td,th {
                    border-collapse: collapse;
                    font-size: 10px;
                    padding: 3px;
                    border: 1px solid #eee;
                }
                img{
                    width:85px;
                }
                .text-right{
                    text-align: right;
                }
                .text-center{
                    text-align: center;
                }
                .text-left{
                    text-align: left;
                }

                @media print {
                    @page {
                        size: A4 landscape;
                    }

                    .page-break {
                        page-break-before: always;
                    }
                }
                </style>`);
            newWin.document.write(divToPrint.html());
            newWin.document.close();
            newWin.focus();
            newWin.print();
        }
    </script>
@endsection
