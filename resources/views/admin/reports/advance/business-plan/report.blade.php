@extends('admin.layout.master')
@section('title', 'Business Plan Report')
@section('content')

<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="{{ route('admin.dashboard') }}">Home</a>
                </li>
                <li class="active">Business Plan Report</li>
                <li class="active">Enter</li>
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
                    {{-- <a href="{{request()->fullUrl().'&print=true'}}" class="btn btn-primary">Print</a> --}}
                    <a href="javascript:printDiv()" class="btn btn-primary">Print</a>
                </div>
            </div>
            <div align="center" class="row" id="print-area">
                <div class="col-lg-12 my-4">
                    <!-- PAGE CONTENT BEGINS -->
                    <div class="text-center">
                        <h2 style="padding: 0;margin:0"><b>{{ $client->full_name }}</b></h2>
                        <h2 style="padding: 0;margin:0"><b>ABN {{ $client->abn_number }}</b></h2>
                    </div>
                    <br>
                    {{-- Profit and Lost --}}
                    @include('admin.reports.advance.business-plan.profit-and-loss')
                    {{-- Profit and Lost --}}

                </div>
                <div class="page-break"></div>
                <div class="col-lg-12 my-4">
                    <hr>
                    <!-- PAGE CONTENT BEGINS -->
                    <div class="text-center">
                        <h2 style="padding: 0;margin:0"><b>{{ $client->full_name }}</b></h2>
                        <h2 style="padding: 0;margin:0"><b>ABN {{ $client->abn_number }}</b></h2>
                        <h4><b>As At: </b> 30 June {{request()->year+1}} </h4>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-lg-6 col-lg-offset-3">
                            <div class="text-left">
                                <h5><b>Things to consider:</b></h5>
                                <ul>
                                    <li>Enter cash payments/receipts only, not invoice amounts</li>
                                    <li>Are the sales figures you've entered in the forecast realistic?</li>
                                    <li>Have you taken seasonality into account?</li>
                                    <li>Do they make sense when compared to your budget and forecast Profit & Loss and
                                        Balance Sheet?</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <br>
                    {{-- cash flow --}}
                    @include('admin.reports.advance.business-plan.cashflow')
                    {{-- cash flow --}}

                </div>
                <div class="page-break"></div>
                <div class="col-lg-12 my-4">
                    <hr>
                    <div class="text-center">
                        <h2 style="padding: 0;margin:0"><b>{{ $client->full_name }}</b></h2>
                        <h2 style="padding: 0;margin:0"><b>ABN {{ $client->abn_number }}</b></h2>
                        <h4><b>As At: </b> 30 June {{request()->year+1}} </h4>
                    </div>
                    <br>
                    {{-- balance sheet --}}
                    @include('admin.reports.advance.business-plan.balance-sheet')
                    {{-- balance sheet --}}
                </div>
            </div>
        </div>
    </div>
</div>
<script>
$(function() {
    $(".datepicker").datepicker({
        dateFormat: "dd/mm/yy",
        changeMonth: true,
        changeYear: true
    });
    $('[id^="income-"]').each(function() {
        const id = $(this).attr('id');
        const split = id.split('-');
        const type = split[0];
        const number = split[1];
        const value = parseFloat($(this).val() ?? 0);
        const goods = $("#cost-of-goods-sold-" + number).val() ?? 0;
        const gross_profit = parseFloat(value) - parseFloat(goods);
        $(".total-gross-profit-" + number).html(gross_profit.toFixed(2));

        // Calculate total Expenses
        const selling = $("#selling-overhead-" + number).val() ?? 0;
        const administrative = $("#administrative-overhead-" + number).val() ?? 0;
        const general = $("#general-overhead-" + number).val() ?? 0;
        const financial = $("#financial-overhead-" + number).val() ?? 0;
        const total_exp = parseFloat(selling) + parseFloat(administrative) + parseFloat(general) +
            parseFloat(financial);
        $(".total-exp-" + number).html(total_exp.toFixed(2));

        // Calculate EBIT
        const ebit = parseFloat(gross_profit) - parseFloat(total_exp);
        $(".total-ebit-" + number).html(ebit.toFixed(2));

        // Profit after Tax
        const tax = $(".total-tax-" + number).text() ?? 0;
        const profit_after_tax = parseFloat(ebit) - parseFloat(tax);
        $(".total-profit-after-" + number).html(profit_after_tax.toFixed(2));

        // Gross profit  margin
        const gross_profit_margin = parseFloat(gross_profit) / parseFloat(value) * 100;
        $(".profit-margin-" + number).html(gross_profit_margin.toFixed(2));

        // Cash Flow Report
        $(".total-receipt-" + number).text(parseFloat(value).toFixed(2));

        $(".cost-of-goods-sold-" + number).text(value.toFixed(2));
        $(".selling-overhead-" + number).text($("#selling-overhead-" + number).val());
        $(".administrative-overhead-" + number).text($("#administrative-overhead-" + number).val());
        $(".general-overhead-" + number).text($("#general-overhead-" + number).val());
        $(".financial-overhead-" + number).text($("#financial-overhead-" + number).val());

        $(".total-lessp-" + number).text(totalLess(number));

        const net_cash = parseFloat($(".total-receipt-" + number).text()) - parseFloat($(".total-lessp-" + number).text());
        $(".net-cash-" + number).text(net_cash.toFixed(2));

        // Net Cash + Closing = opening
        // const opening = parseFloat($(".opening-bl-" + number).text() ?? 0);
        // const closing = parseFloat($(".closing-bl-" + number).text() ?? 0);
        // const net_closing = parseFloat(net_cash) + parseFloat(opening);
        // $(".closing-bl-" + number).text(net_closing.toFixed(2));
        // if (number != 1) {
        //     $(".opening-bl-" + number).text(net_closing.toFixed(2));
        // }
    });

    // Bank Closing
    $('.net-cash').each(function(i,v) {
        const cash = parseFloat($(this).text());
        const opening = $($(".opening").get(i));
        const closing = $($(".closing").get(i));
        const net_closing = parseFloat(cash) + parseFloat(opening.text());
        closing.text(net_closing.toFixed(2));
        $($(".opening").get(i+1)).text(net_closing.toFixed(2));
    });

});
function totalLess(num) {
    var total = 0;
    $('.less-'+ num).each(function() {
        total += parseInt($(this).text());
    });
    return total.toFixed(2);
}
function printDiv() {
    let divToPrint = $("#print-area");
    newWin = window.open("");

    newWin.document.write('<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css">');
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
