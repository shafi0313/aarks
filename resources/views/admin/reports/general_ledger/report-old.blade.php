@extends('admin.layout.master')
@section('title','General Ledger')
@section('content')

<div class = "main-content">
    <div class = "main-content-inner">
        <div class = "breadcrumbs ace-save-state" id = "breadcrumbs">
            <ul  class = "breadcrumb">
                <li>
                    <i class = "ace-icon fa fa-home home-icon"></i>
                    <a href  = "{{ route('admin.dashboard') }}">Home</a>
                </li>

                <li>
                    <a href = "#">Report</a>
                </li>
                <li>
                    <a href = "#">General Ledger</a>
                </li>
                <li>
                    <a href="#">{{ ($client->company)? $client->company : $client->first_name.' '.$client->last_name}}</a>
                </li>
            </ul><!-- /.breadcrumb -->
        </div>

        <div class = "page-content">

            <!-- Settings -->
            {{--             <h1>Hello</h1>@include('admin.layout.settings')--}}
            <!-- /Settings -->

            <div class = "row">
            <div class = "col-lg-12">
                    <!-- PAGE CONTENT BEGINS -->
                    <div class = "row">
                    <h2  class = "text-center bolder">{{ ($client->company)? $client->company : $client->first_name.' '.$client->last_name}}</h2>
                    <h5  class = "text-center"><u>Ledger Report From: {{$start_date->format('d/m/Y')}} to :
                                {{$end_date->format('d/m/Y')}}</u></h5>
                    </div>

                    <div class="row">
                        <div class="col-lg-2 pull-right">
                        <form action="{{route('ledger.show')}}" method="GET" autocomplete="off">
                            <input type="hidden" name="client_id" value="{{$client->id}}">
                            {{-- <input type="hidden" name="profession_id" value="{{$profession->id}}"> --}}
                            <input name="start_date" hidden value="{{$start_date->format('d/m/Y')}}">
                            <input name="end_date" hidden value="{{$end_date->format('d/m/Y')}}">
                            <input name="from_account" hidden value="{{$from_account}}">
                            <input name="to_account" hidden value="{{$to_account}}">
                            <input type="submit" name="submit" value="Print" class="btn btn-success">
                            <input type="submit" name="submit" value="Email" class="btn btn-info">
                        </form>
                    </div>
                    </div>

                    <div class = "row">
                        <div>
                            <table class = "table" style = "margin: 10px;">
                                @foreach($client_account_codes as $client_account_code)
                                <tr>
                                    <td colspan = "9" class = "bolder" style = "margin: 0;padding: 4px">
                                        {{$client_account_code->name}}</td>
                                </tr>
                                <tr>
                                    <td>Date</td>
                                    <td>Particular</td>
                                    <td class = "center">Transaction Id</td>
                                    <td>JFL</td>
                                    <td>Dr.amount</td>
                                    <td>Cr.amount</td>
                                    <td>GST</td>
                                    <td>Net amt</td>
                                    <td>Balance</td>
                                </tr>
<tr>
    <td  colspan  = "8">Opening Balance</td>
    @php $obl_balance = $AL_obl = 0; $oblType = ''@endphp
    @if ($client_account_code->generalLedger->count())
    @php
    $fgl          = $client_account_code->generalLedger->first();
    $AssLai       = $preAssLilas->where('chart_id',$fgl->chart_id)->first();
    $diff_balance = number_format($fgl->balance - $fgl->net_amount, 2);
    $balance_type = ($diff_balance > 0 ? $fgl->balance_type : !$fgl->balance_type);

    $start    = $start_date->format('dm');
    $fromDate = $start_date->format('Y-m-d'); 
    if($start == '0107'){
        if(str_split($fgl->chart_id)[0]==1 || str_split($fgl->chart_id)[0]==2){
            $obl_balance = 0;
        }elseif(str_split($fgl->chart_id)[0]==5 || str_split($fgl->chart_id)[0]==9){
            $obl_balance = $AL_obl??0;
        }
    }else{
        if(str_split($fgl->chart_id)[0]==1 || str_split($fgl->chart_id)[0]==2){
            $inExbalance = $inExPreData->where('chart_id', $fgl->chart_id)->first();
            $obl_balance = $inExbalance?$inExbalance->balance:0;
        }elseif(str_split($fgl->chart_id)[0]==5 || str_split($fgl->chart_id)[0]==9){
            $lailaBalance = $assetLailaPreData->where('chart_id', $fgl->chart_id)->first();
            $lal_balance = $lailaBalance?$lailaBalance->balance:0;
            $obl_balance = $lal_balance + $AL_obl ?? 0;
        }
    }
    if($fgl->balance_type == 1){
        $oblType = $obl_balance > 0 ? 'Dr' : 'Cr';
    }else{
        $oblType = $obl_balance < 0 ? 'Dr' : 'Cr';
    }
    @endphp
    <td>{{abs($obl_balance) . ' ' .$oblType}} </td>
    @else
    @php
    if($preAssLilas->count()){
        foreach($preAssLilas as $preAssLila){
            if($client_account_code->id == $preAssLila->client_account_code_id){
                $Obl = $preAssLila->OpenBl;
               echo "<td>".abs($Obl) . ($Obl <= 0 ? ' Cr' : ' Dr' )."</td>";
            }
        }
    }
    @endphp
    @endif
</tr>
                                @php
                                $blnc   = $obl_balance;
                                $Sdebit = $Scredit = 0;
                                @endphp

                                @foreach($client_account_code->generalLedger as $generalLedger)
                                @php
                                $debit  = 0;
                                $credit = 0;
                                if($generalLedger->credit < 0){
                                    $debit   = $generalLedger->credit;
                                    $Sdebit += $generalLedger->credit;
                                }elseif($generalLedger->debit < 0){
                                    $credit   = $generalLedger->debit;
                                    $Scredit += $generalLedger->debit;
                                }else{
                                    $debit    = $generalLedger->debit;
                                    $credit   = $generalLedger->credit;
                                    $Sdebit  += $generalLedger->debit;
                                    $Scredit += $generalLedger->credit;
                                }
                                if($generalLedger->credit != 0 || $generalLedger->debit != 0){
                                    $blnc += $generalLedger->balance;
                                }else{
                                    $blnc -= $generalLedger->balance;
                                }
                                if($generalLedger->balance_type == 2){
                                    $blncType = $blnc < 0 ? 'Dr' : 'Cr';
                                }elseif($generalLedger->balance_type == 1){
                                    $blncType = $blnc < 0 ? 'Cr' : 'Dr';
                                }

                                @endphp
                                <tr>
                                    <td>{{$generalLedger->date->format('d/m/Y')}}</td>
                                    <td>
                                        for narration view click <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                                    </td>
                                    <td class = "center">
                                        <a href="{{route('general_ledger.transaction',[ $generalLedger->transaction_id,$generalLedger->source])}}"
                                        style = "color: green;text-decoration: underline">{{$generalLedger->transaction_id}}</a>
                                    </td>
                                    <td>{{$generalLedger->source}}</td>
                                    <td>{{abs($debit)}}</td>
                                    <td>{{abs($credit)}}</td>
                                    <td>{{abs($generalLedger->gst)}}</td>
                                    <td>{{abs($generalLedger->balance)}}</td>
                                    <td>
                                        {{abs($blnc) . ' ' . ($blncType)}}
                                    </td>
                                </tr>
                                @endforeach
                                <tr>
                                    <td colspan ="4">Total</td>
                                    <td style="color: red">{{abs($Sdebit)}}
                                    </td>
                                    <td style="color: red">{{abs($Scredit)}}
                                    </td>
                                    <td style="color: red">{{ abs($client_account_code->generalLedger->sum('gst')) }}
                                    </td>
                                    <td style = "color: red">
                                        {{ abs($client_account_code->generalLedger->sum('balance')) }}</td>
                                    <td></td>
                                </tr>
                                @endforeach
                                @if ($retains)
                                <tr>
                                    <td colspan = "9" class = "bolder" style = "margin: 0;padding: 4px">Retain Earning</td>
                                </tr>
                                <tr>
                                    <td>Date</td>
                                    <td>Particular</td>
                                    <td class = "center">Transaction Id</td>
                                    <td>JFL</td>
                                    <td>Dr.amount</td>
                                    <td>Cr.amount</td>
                                    <td>GST</td>
                                    <td>Net amt</td>
                                    <td>Balance</td>
                                </tr>
                                @php
                                $retainBalance =$retains->sum('balance');
                                @endphp
                                <tr>
                                    <td colspan = "8">Opening Balance</td>
                                    <td >{{abs($retainBalance). ' ' . ($retainBalance <= 0 ? 'Dr' : 'Cr' )}}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                    <!-- PAGE CONTENT ENDS -->
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.page-content -->
    </div>
</div><!-- /.main-content -->
@endsection
