@extends('admin.layout.master')
@section('title','Accumulated P/L GST Exclusive')
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
                <li class="active">Accumulated P/L GST Exclusive</li>
                <li class="active">Balance</li>
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

                    <div class="row" id="printarea">
                        <div class="col-xs-12">
                            <div align="center">
                                <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center"
                                    style="text-align:center; border-bottom:2px solid #999999;">
                                    <tr>
                                        <td width="30%" height="132">
                                            <img src="{{logo($client)}}"
                                                width="120" align="right" />
                                            </td>
                                        <td width="40%"><span style="font-size:22px; font-weight:800;"> Focus Taxation
                                                and Accounting Pty
                                                Ltd.</span> <br /><span style="font-size:12px;">(Certified Practising
                                                Accountant &amp;
                                                Registered. Tax Agents.) </span><br />
                                            ABN : 68 136 655 608, Add : 8 Rochford Way, Girrawheen WA 6064<br />
                                            Phone: 08 9343 9107, Mobile 0433 282 508, 0433 376 277<br /><span
                                                style="font-size:12px;">
                                                E-mail:the.team@focustaxation.com.au, visit : www.focustaxation.com.au
                                            </span></td>
                                        <td width="30%"><img src="{{logo()}}"
                                                width="120" height="80" align="left" /></td>
                                    </tr>
                                </table>
                                <div align="center" style="padding-top:20px;">
                                    <strong style="font-weight:800; font-size:18px;">{{$client->full_name}}
                                    </strong>
                                    <br />
                                    <strong>ABN : {{$client->abn_number}}</strong>

                                    <br />
                                    <strong style="font-weight:800; font-size:18px;"><b>Profit and Loss
                                            Statement</b></strong><br />
                                    <strong><b><u>For the Period From {{$form_date}} - {{$to_date}}</u></b></strong>

                                    <div align="center" style="padding-top:0px;">
                                        <table width="60%" class="table-hover table-striped tablecustome">
                                            <tr>
                                                <th width="70%" align="left"><u>Description</u></th>
                                                <th width="30%" style="text-align:right;"><u>AUD($)</u></th>
                                            </tr>
                                            @foreach ($incomeCodes as $incomeCode)
                                            @if ($incomeCode->id != '')
                                            <tr>
                                                <th>{{$incomeCode->client_account_code->name}}</th>
                                                <th style="text-align: right;">{{number_format($incomeCode->inBalance,2)}}</th>
                                            </tr>
                                            @endif
                                            @endforeach
                                            <tr>
                                                <th width="82%">Total Turnover/Income</th>
                                                <th width="18%" align="right"
                                                    style="border-bottom:1px solid #000000; border-top:1px solid #000000; text-align:right;">
                                                    $ {{number_format(abs($totalIncome->totalIncome),2)}} </th>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                            </tr>
                                            @foreach ($expensCodes as $expensCode)
                                            @if ($expensCode->id != '')
                                            <tr>
                                                <th>{{$expensCode->client_account_code->name}}</th>
                                                <th style="text-align: right;">{{number_format($expensCode->exBalance,2)}}</th>
                                            </tr>
                                            @endif
                                            @endforeach

                                            <tr>
                                                <th>Total Expenses</th>
                                                <th
                                                    style="border-bottom:1px solid #000000; border-top:1px solid #000000; text-align:right;">
                                                    ${{number_format(abs($totalExpense->totalExpense),2)}}</th>
                                            </tr>
                                            <tr>
                                                <th> <span style="color:red;">Net Profit/Loss</span> </th>
                                                <th style="text-align:right;"> <span style="color:red;">$
                                                        {{number_format(abs($totalIncome->totalIncome) - abs($totalExpense->totalExpense),2)}}

                                                    </span> </th>
                                            </tr>

                                            <tr>
                                                <th></th>
                                                <th
                                                    style="border-bottom:1px solid #000000; border-top:1px solid #000000; padding-top:2px; text-align:right;">
                                                </th>
                                            </tr>

                                            <tr>
                                                <td colspan="2" style="color:red;">I declared that the Information
                                                    provided in this form is
                                                    in accordance with the information provided by our client and to
                                                    that extent is complete
                                                    and correct.</td>
                                            </tr>

                                            <tr>
                                                <td colspan="2"> <br><br>...................................................
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>Date :</td>
                                                <td></td>
                                            </tr>

                                            <tr>
                                                <td colspan="2">Focus Taxation and Accounting Pty Ltd</td>
                                            </tr>

                                        </table>
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
                                <button onclick="printDiv('printarea')" title="Print Report" class="btn btn-primary">Print</button>
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
                                <button type="submit" title="Send Mail" class="btn btn-success pull-right">Send Mail</button>
                            </form>
                        </div>

                    </div>

                    {{-- </div> --}}
                    {{-- </div> --}}
                    {{-- </div> --}}
                    {{-- </div> --}}





                    <!-- PAGE CONTENT ENDS -->
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.page-content -->
    </div>
</div><!-- /.main-content -->



<!-- inline scripts related to this page -->
<style>
    @media print {

        body *,
        #main * {
            display: none;
        }

        #main,
        #main #printarea,
        #main #printarea * {
            display: block;
        }
    }
</style>

<script>
    function printDiv(divId){
    var divToPrint = document.getElementById(divId);
    newWin= window.open();
    newWin.document.write(divToPrint.innerHTML);
    newWin.document.write('<style>table,tr,td,th {border-collapse: collapse;}.table-code tr, td, th {text-align: left;padding: 5px;}</style>');
    newWin.document.close();
    newWin.focus();
    newWin.print();
    }
</script>

@endsection
