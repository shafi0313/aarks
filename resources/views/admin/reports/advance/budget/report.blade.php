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
                <li>Prepare Budget/Budget Entry</li>
                <li class="active">Enter</li>
            </ul>
        </div>
        <style>
            .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
                padding: 1px 5px;
                vertical-align: middle;
            }
            .form-control {
                height: 28px;
            }
        </style>
        <div class="page-content" style="margin-top: 50px;">
            <form class="was-validated">
                <div align="center" class="row">
                    <div class="col-lg-12">
                        <!-- PAGE CONTENT BEGINS -->
                        <div class="text-center">
                            <h2 style="padding: 0;margin:0"><b>{{ $client->full_name}}</b></h2>
                            <h2 style="padding: 0;margin:0"><b>ABN {{$client->abn_number}}</b></h2>
                            <h4><b>Budget Report as at: {{$date->format('d/m/Y')}}</b></h4>
                        </div>
                        <style>
                            .text-danger {
                                color: red;
                            }
                        </style>
                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>

                        @endif
                        <table class="table table-bordered" style="width: 80%">
                            <thead>
                                <tr>
                                    <td class="text-center" rowspan="2" style="width: 8%;">Account Code</td>
                                    <td class="text-center" rowspan="2" style="width: 26%;">Account Name</td>
                                    <td class="text-center" colspan="2" style="width: 20%;">
                                        Last Year ({{$current->format('d/m/Y')}})
                                    </td>
                                    <td class="text-center" colspan="2"  style="width: 20%;">
                                        Budget Year ({{$date->format('d/m/Y')}})
                                    </td>
                                    <td class="text-center" rowspan="2"  style="width: 13%;">Actual</td>
                                    <td class="text-center" rowspan="2"  style="width: 13%;">Variance</td>
                                  </tr>
                                <tr>
                                    <td class="text-center" style="width: 11%;">Amount</td>
                                    <td class="text-center" style="width: 9%">Percent</td>
                                    <td class="text-center" style="width: 9%">Proposed %</td>
                                    <td class="text-center" style="width: 11%;">Amount</td>
                                  </tr>
                            </thead>
<tbody>
@php
    $other_balance = $balance = $salse_balance = $sum_sales_budget_amount = $sum_without_sales_budget_amount = $sum_expense_budget_amount = $salse_actual = $other_actual = $sum_sales_variance = $sum_without_sales_variance = 0;
@endphp

    @foreach($currentBudgets as $i => $budgets)
        @php
        $ledger_type = $ledgers[$i];
        @endphp
        @foreach($budgets as $j => $budget)
        @php
            $code = $budget->chart;
            $balance = ($budget->last_year_amount);
            $salse_balance += $balance;
            if ($i != 1) {
                $other_balance += abs($balance);
            }
            $ledger = $ledger_type->where('chart_id', $budget->chart_id)->first();
            if (!$ledger) {
                continue;
            }
            $actual = $ledger->actual_amount??0;
            if($i == 1 && $ledger->balance_type == 1){
                $actual = - $ledger->actual_amount??0;
            }
            $salse_actual += $actual;
            if ($i != 1) {
                $other_actual += abs($actual);
            }
        @endphp
        @if ($balance != 0)
        @if ($code->category_id == 1)
        <tr class="with_sales">
            <td style="text-align: center">{{$code->code}}</td>
            <td>{{$code->name}}</td>
            <td style="text-align: right">
                {{-- {{$balance}} --}}
                {{number_format(abs($balance),2)}}
            </td>
            <td>
                <div class="input-group" >
                    <input value="100" class="form-control w-25 text-right"  type="number" disabled  aria-describedby="old_percent_{{$i}}_{{$j}}">
                    <span class="input-group-addon" id="old_percent_{{$i}}_{{$j}}">&percnt;</span>
                </div>
            </td>
            <td>
                <div class="input-group" >
                    <input type="number" disabled class="with_sales_percent form-control w-25 text-right" value="{{$budget->percent??0}}" aria-describedby="percent_{{$i}}_{{$j}}" step="any">
                    <span class="input-group-addon" id="percent_{{$i}}_{{$j}}">&percnt;</span>
                </div>
            </td>
            <td style="text-align: right">
                @php
                    $sales_budget_amount = ($budget->last_year_amount * $budget->percent)/100;
                    $sum_sales_budget_amount += $sales_budget_amount;

                    $sales_variance = abs($sales_budget_amount) - abs($actual);
                    if($ledger->balance_type == 1){
                        $sum_sales_variance -= abs($sales_variance);
                    } else {
                        $sum_sales_variance += abs($sales_variance);
                    }
                @endphp
                <span class="budget_amount">{{number_format(abs($sales_budget_amount),2)}}</span>
            </td>
            <td style="text-align: right">
                <span class="actual_amount">{{number_format(abs($actual),2)}}</span>
            </td>
            <td style="text-align: right">
                <span class="variance_amount">{{number_format(abs($sales_variance),2)}}</span>
            </td>
        </tr>
        @else
        <tr class="without_sales">
            <td style="text-align: center">{{$code->code}}</td>
            <td>{{$code->name}}</td>
            <td style="text-align: right">
                {{-- {{$balance}} --}}
                {{number_format(abs($balance),2)}}
            </td>
            <td>
                <div class="input-group">
                    <input class="old_percent form-control w-25 text-right" type="number" disabled value="{{number_format($budget->old_percent??0,4)}}"  aria-describedby="old_percent_{{$i}}_{{$j}}">
                    <span class="input-group-addon" id="old_percent_{{$i}}_{{$j}}">&percnt;</span>
                </div>
            </td>
            <td>
                <div class="input-group">
                    <input type="number" disabled value="{{$budget->percent??0}}" class="percent form-control w-25 text-right" aria-describedby="percent_{{$i}}_{{$j}}" step="any">
                    <span class="input-group-addon" id="percent_{{$i}}_{{$j}}">&percnt;</span>
                </div>
            </td>
            <td style="text-align: right">
                @php
                    $without_sales_budget_amount = ($sum_sales_budget_amount * $budget->percent)/100;
                    $sum_without_sales_budget_amount += $without_sales_budget_amount;
                    if($i == 2){
                        $sum_expense_budget_amount += $without_sales_budget_amount;
                    }
                @endphp
                <span class="budget_amount">{{number_format(abs($without_sales_budget_amount),2)}}</span>
            </td>
            <td style="text-align: right">
                <span class="actual_amount">{{number_format(abs($actual),2)}}</span>
            </td>
            <td style="text-align: right">
                @php
                    $without_sales_variance = abs($without_sales_budget_amount) - abs($actual);
                    $sum_without_sales_variance += abs($without_sales_variance);
                @endphp
                <span class="variance_amount">{{number_format(abs($without_sales_variance),2)}}</span>
            </td>
        </tr>
        @endif
        @endif
    @endforeach
        {{-- <tr>
            <td><b class="text-danger">{{number_format($other_balance,2)}}</b> </td>
        </tr> --}}
        @if($i==1)
        <tr class="text-right with_sales_total">
            <td colspan="2">
                <b>Net Sale</b>
            </td>
            <td>
                <b class="last_year_total">{{number_format($salse_balance,2)}}</b>
            </td>
            <td>0.00</td>
            <td>0.00</td>
            <td>
                <b class="total_budget">{{number_format(abs($sum_sales_budget_amount),2)}}</b>
            </td>
            <td>
                <b class="total_budget">{{number_format(abs($salse_actual),2)}}</b>
            </td>
            <td>
                <b class="total_budget">{{number_format(abs($sum_sales_variance),2)}}</b>
            </td>
        </tr>
        @endif
    @endforeach
    <tr class="text-right without_sales_total">
        <td colspan="2">
            <b>Total</b>
        </td>
        <td>
            <b class="last_year_total">{{number_format($other_balance,2)}}</b>
        </td>
        <td colspan="2"></td>
        <td>
            <b class="total_budget">{{number_format($sum_without_sales_budget_amount,2)}}</b>
        </td>
        <td>
            <b class="total_budget">{{number_format($other_actual,2)}}</b>
        </td>
        <td>
            <b class="total_budget">{{number_format($sum_without_sales_variance,2)}}</b>
        </td>
    </tr>
    <tr class="text-center">
        <td colspan="2" class="text-right">Profit/Loss as at
            {{$current->format('d/m/Y')}}</td>
        @if ($CRetains)
        <td colspan="2">
            {{$CRetains}}
        </td>
        @else
        <td colspan="2">{{number_format(0,2)}}</td>
        @endif
        <td colspan="2">
            @php($pl = abs($sum_sales_budget_amount) - abs($sum_expense_budget_amount))
            <span class="budget_pl">
                {{number_format($pl,2)}}
            </span>
        </td>
        @if ($CRetains)
        <td colspan="2">
            {{$CRetains}}
        </td>
        @else
        <td colspan="2">{{number_format(0,2)}}</td>
        @endif
    </tr>
</tbody>
                        </table>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(function(){
        $('.with_sales').each(function(){
            var $this = $(this);
            var $input = $this.find('input');
            var $last_year_amount = $this.find('.last_year_with_sales').val();
            $input.on('keyup', function(){
                var $this = $(this);
                if($this.hasClass('with_sales_percent')){
                    var $percent = $this.val();
                    var $amount = ($percent * $last_year_amount) / 100;
                    $this.closest('tr').find('td').eq(5).find('.budget_amount').text(Math.abs($amount).toFixed(2)).val($amount.toFixed(2));
                    var $total = 0;
                    $.each($('.with_sales').find('input.budget_amount '), function(i, v){
                        $total += parseFloat($(v).val());
                    });
                    $('.total_budget').text(Math.abs($total).toFixed(2)).val($total.toFixed(2));
                }
                $(".without_sales").find("input").prop('disabled', false);
                getBudgetPl();
            });
        });
        $('.without_sales').each(function(){
            var $this = $(this);
            var $input = $this.find('input.percent');
            var $last_year_amount = $this.find('.last_year_amount').val();

            $input.on('keyup', function(){
                var $this = $(this);
                if(!$this.hasClass('with_sales_percent')){
                    var $percent = $this.val();
                    var $total_budget = parseFloat($('.total_budget').val());
                    var $amount = ($percent * $total_budget) / 100;
                    $this.closest('tr').find('td').eq(5).find('.budget_amount').text(Math.abs($amount).toFixed(2)).val($amount.toFixed(2));
                    var $total = 0;
                    $.each($('.without_sales').find('input.budget_amount '), function(i, v){
                        $total += parseFloat($(v).val());
                    });
                    $('.without_sales_total').find('.total_budget').text(Math.abs($total).toFixed(2)).val($total.toFixed(2));
                    getBudgetPl();
                }
            });
        });
    });
    function getBudgetPl(){
        let exp = $(".budget_amount_2");
        let inc = parseFloat($(".total_budget").val());
        let total_exp = 0;
        $.each(exp, function(i, v){
            console.log($(v).val());
            total_exp += parseFloat($(v).val());

        });
        let profit_loss =  inc - total_exp;
        $(".budget_pl").text(profit_loss.toFixed(2));
    }
    $(".datepicker").datepicker({
        dateFormat: "dd/mm/yy",
        changeMonth: true,
        changeYear: true
    });

</script>
@endsection
