<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>General Ledgers</title>
        @include('frontend.print-css')
    </head>

    <body>
        <div class="container">
            <div class = "row justify-content-center">
                <div class = "col-lg-12">
                        <h2  class = "text-center bolder">{{ $client->fullname}}</h2>
                        <h5  class = "text-center"><u>Ledger Report From: {{$start_date->format('d/m/Y')}} to :
                                    {{$end_date->format('d/m/Y')}}</u></h5>

                    <div class="row">
                        <div class="col-md-12">
                            <table class="table" style="margin: 10px;">
                                @foreach($ledgers as $ledger)
                                <tr>
                                    <td colspan="9" class="bolder" style="margin: 0;padding: 4px">
                                        {{$ledger->first()->client_account_code->name}}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Date</td>
                                    <td>Particular</td>
                                    <td class="center">Transaction Id</td>
                                    <td>JFL</td>
                                    <td>Dr.amount</td>
                                    <td>Cr.amount</td>
                                    <td>GST</td>
                                    <td>Net amt</td>
                                    <td>Balance</td>
                                </tr>
                                @php
                                $open_bl = $credit = $debit = $gst = $net_bl = $net = $blnc = 0;
                                @endphp
<tr>
    <td colspan="8">Opening Balance</td>
    @php $obl_balance = $AL_obl = 0; $oblType = '' @endphp
    @if ($ledger->count())
    @php
    $fgl          = $ledger->first();
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
    @endif
    <td>{{abs($obl_balance) . ' ' .$oblType}} </td>
</tr>
                                @foreach($ledger->sortBy('date') as $gen_ledger)
                                @php
                                $debit  += $gen_ledger->debit;
                                $credit += $gen_ledger->credit;
                                $gst    += $gen_ledger->gst;
                                $net_bl += $gen_ledger->balance;

                                if($gen_ledger->credit != 0 || $gen_ledger->debit != 0){
                                    $blnc += $gen_ledger->balance;
                                }else{
                                    $blnc -= $gen_ledger->balance;
                                }

                                if($gen_ledger->balance_type == 2){
                                    $blncType = $blnc < 0 ? 'Dr' : 'Cr' ;
                                } elseif($gen_ledger->balance_type == 1){
                                    $blncType = $blnc < 0 ? 'Cr' : 'Dr' ;
                                }
                                @endphp
                                <tr>
                                        <td>{{$gen_ledger->date->format('d/m/Y')}}</td>
                                        <td>
                                            for narration view click <i class="fa fa-hand-o-right"
                                                aria-hidden="true"></i>
                                        </td>
                                        <td class="center">
                                            <a href="{{route('general_ledger.transaction',[ $gen_ledger->transaction_id,$gen_ledger->source])}}"
                                                style="color: green;text-decoration: underline">{{$gen_ledger->transaction_id}}</a>
                                        </td>
                                        <td>{{$gen_ledger->source}}</td>
                                        <td>
                                            {{number_format(abs($gen_ledger->debit), 2)}}
                                        </td>
                                        <td>
                                            {{number_format(abs($gen_ledger->credit), 2)}}
                                        </td>
                                        <td>
                                            {{number_format(abs($gen_ledger->gst), 2)}}
                                        </td>
                                        <td>
                                            {{number_format(abs($gen_ledger->balance), 2)}}
                                        </td>
                                        <td>
                                            {{number_format(abs($blnc), 2)}} {{$blncType}}
                                        </td>
                                        </tr>
                                        @endforeach
                                        <tr>
                                            <td colspan="4">Total</td>
                                            <td style="color: red">{{number_format(abs($debit), 2)}}
                                            </td>
                                            <td style="color: red">{{number_format(abs($credit), 2)}}
                                            </td>
                                            <td style="color: red">{{
                                                number_format(abs($gst), 2) }}
                                            </td>
                                            <td style="color: red">
                                                {{ number_format(abs($net_bl), 2) }}</td>
                                            <td></td>
                                        </tr>
                                        @endforeach

                                        @if ($retains)
                                        <tr>
                                            <td colspan="9" class="bolder" style="margin: 0;padding: 4px">Retain Earning
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Date</td>
                                            <td>Particular</td>
                                            <td class="center">Transaction Id</td>
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
                                            <td colspan="8">Opening Balance</td>
                                            <td>{{abs($retainBalance). ' ' . ($retainBalance <= 0 ? 'Dr' : 'Cr' )}}</td>
                                        </tr>
                                        @endif
                            </table>
                        </div>
                    </div>
                        <!-- PAGE CONTENT ENDS -->
                    </div><!-- /.col -->
                </div><!-- /.row -->
        </div><!-- /.Container -->

    </body>

</html>
