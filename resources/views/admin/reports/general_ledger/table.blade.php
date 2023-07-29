<table class="table" style="margin: 10px;">
    @foreach ($ledgers as $ledger)
        <tr>
            <td colspan="9" class="bolder" style="margin: 0;padding: 4px">
                {{ $ledger->first()->client_account_code->name }} - {{ $ledger->first()->client_account_code->code }}
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
        {{-- <tr>
            <td colspan="8">Opening Balance</td>
            @php
                $obl_balance = $AL_obl = 0;
                $oblType = '';
            @endphp
            @if ($ledger->count())
                @php
                    $first_ledger = $ledger->first();
                    $AssLai       = $preAssLilas->where('chart_id', $first_ledger->chart_id)->first();
                    $diff_balance = number_format($first_ledger->balance - $first_ledger->net_amount, 2);
                    $balance_type = $diff_balance > 0 ? $first_ledger->balance_type : !$first_ledger->balance_type;
                    
                    $start       = $start_date->format('dm');
                    $fromDate    = $start_date->format('Y-m-d');
                    $obl_balance = $blnc = optional($open_balances->where('chart_id', $first_ledger->chart_id)->first())->openBl;
                    // if($start == '0107'){
                    // if(str_split($first_ledger->chart_id)[0]==1 || str_split($first_ledger->chart_id)[0]==2){
                    // $obl_balance = 0;
                    // }elseif(str_split($first_ledger->chart_id)[0]==5 || str_split($first_ledger->chart_id)[0]==9){
                    // $obl_balance = $AL_obl??0;
                    // }
                    // }else{
                    // if(str_split($first_ledger->chart_id)[0]==1 || str_split($first_ledger->chart_id)[0]==2){
                    // $inExbalance = $inExPreData->where('chart_id', $first_ledger->chart_id)->first();
                    // $obl_balance = $inExbalance?$inExbalance->balance:0;
                    // }elseif(str_split($first_ledger->chart_id)[0]==5 || str_split($first_ledger->chart_id)[0]==9){
                    // $lailaBalance = $assetLailaPreData->where('chart_id', $first_ledger->chart_id)->first();
                    // $lal_balance = $lailaBalance?$lailaBalance->balance:0;
                    // $obl_balance = $lal_balance + $AL_obl ?? 0;
                    // }
                    // }
                    if ($first_ledger->balance_type == 1) {
                        $oblType = $obl_balance > 0 ? 'Dr' : 'Cr';
                    } else {
                        $oblType = $obl_balance < 0 ? 'Dr' : 'Cr';
                    }
                @endphp
            @endif
            <td>{{ abs_number($obl_balance) . ' ' . $oblType }}
            </td>
        </tr> --}}
        @foreach ($ledger->sortBy('date') as $gen_ledger)
            @php
                $debit += $gen_ledger->debit;
                $credit += $gen_ledger->credit;
                $gst += $gen_ledger->gst;
                $net_bl += $gen_ledger->balance;
                
                if ($gen_ledger->credit != 0 || $gen_ledger->debit != 0) {
                    $blnc += $gen_ledger->balance;
                } else {
                    $blnc -= $gen_ledger->balance;
                }
                
                if ($gen_ledger->balance_type == 2) {
                    $blncType = $blnc < 0 ? 'Dr' : 'Cr';
                } elseif ($gen_ledger->balance_type == 1) {
                    $blncType = $blnc < 0 ? 'Cr' : 'Dr';
            } @endphp <tr>
                <td>{{ $gen_ledger->date->format('d/m/Y') }}</td>
                <td>
                    for narration view click <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </td>
                <td class="center">
                    <a href="{{ route($url, [$gen_ledger->transaction_id, $gen_ledger->source]) }}"
                        style="color: green;text-decoration: underline">{{ $gen_ledger->transaction_id }}</a>
                </td>
                <td>{{ $gen_ledger->source }}</td>
                <td>
                    {{ number_format(abs($gen_ledger->debit), 2) }}
                </td>
                <td>
                    {{ number_format(abs($gen_ledger->credit), 2) }}
                </td>
                <td>
                    {{ number_format(abs($gen_ledger->gst), 2) }}
                </td>
                <td>
                    {{ number_format(abs($gen_ledger->balance), 2) }}
                </td>
                <td>
                    {{ number_format(abs($blnc), 2) }} {{ $blncType }}
                </td>
            </tr>
        @endforeach
        <tr>
            <td colspan="4">Total</td>
            <td style="color: red">{{ number_format(abs($debit), 2) }}
            </td>
            <td style="color: red">{{ number_format(abs($credit), 2) }}
            </td>
            <td style="color: red">{{ number_format(abs($gst), 2) }}
            </td>
            <td style="color: red">
                {{ number_format(abs($net_bl), 2) }}</td>
            <td></td>
        </tr>
    @endforeach

    {{-- @if ($retains)
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
            $retainBalance = $retains->sum('balance');
        @endphp
        <tr>
            <td colspan="8">Opening Balance</td>
            <td>{{ abs($retainBalance) . ' ' . ($retainBalance <= 0 ? 'Dr' : 'Cr') }}</td>
        </tr>
    @endif
    </table> --}}



    {{-- 5 & 9 --}}
    {{-- <table class="table" style="margin: 10px;"> --}}

    {{-- <table class="table" style="margin: 10px;"> --}}
    @foreach ($client_account_codes as $client_account_code)
        <tr>
            <td colspan="9" class="bolder" style="margin: 0;padding: 4px">
                {{ $client_account_code->name }} - {{ $client_account_code->code }}</td>
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
        <tr>
            <td colspan="8">Opening Balance</td>
            @php
                $obl_balance = $AL_obl = 0;
                $oblType = '';
            @endphp
            @if ($client_account_code->generalLedger->count())
                @php
                    $first_ledger = $client_account_code->generalLedger->first();
                    $AssLai = $preAssLilas->where('chart_id', $first_ledger->chart_id)->first();
                    $diff_balance = number_format($first_ledger->balance - $first_ledger->net_amount, 2);
                    $balance_type = $diff_balance > 0 ? $first_ledger->balance_type : !$first_ledger->balance_type;
                    
                    $start = $start_date->format('dm');
                    $fromDate = $start_date->format('Y-m-d');
                    $obl_balance = $blnc = optional($open_balances->where('chart_id', $first_ledger->chart_id)->first())->openBl;
                    
                    if ($client_account_code->type == 1) {
                        $oblType = $first_ledger->balance_type == 1 ? ($obl_balance > 0 ? 'Dr' : 'Cr') : ($obl_balance < 0 ? 'Dr' : 'Cr');
                    } elseif ($client_account_code->type == 2) {
                        $oblType = $first_ledger->balance_type == 2 ? ($obl_balance < 0 ? 'Dr' : 'Cr') : ($obl_balance > 0 ? 'Dr' : 'Cr');
                    }
                    // if ($client_account_code->type ==1 && $first_ledger->balance_type == 1) {
                    //     $oblType = $obl_balance > 0 ? 'Dr' : 'Cr';
                    // } else {
                    //     $oblType = $obl_balance < 0 ? 'Dr' : 'Cr';
                    // }
                @endphp
            @endif
            <td>{{ nFA2($obl_balance) . ' ' . $oblType }}
            </td>
        </tr>
        @php
            $blnc = $obl_balance;
            $Sdebit = $Scredit = 0;
        @endphp

        @foreach ($client_account_code->generalLedger as $generalLedger)
            @php
                $debit = 0;
                $credit = 0;
                if ($generalLedger->credit < 0) {
                    $debit = $generalLedger->credit;
                    $Sdebit += $generalLedger->credit;
                } elseif ($generalLedger->debit < 0) {
                    $credit = $generalLedger->debit;
                    $Scredit += $generalLedger->debit;
                } else {
                    $debit = $generalLedger->debit;
                    $credit = $generalLedger->credit;
                    $Sdebit += $generalLedger->debit;
                    $Scredit += $generalLedger->credit;
                }
                if ($generalLedger->credit != 0 || $generalLedger->debit != 0) {
                    $blnc += $generalLedger->balance;
                } else {
                    $blnc -= $generalLedger->balance;
                }
                if ($generalLedger->balance_type == 2) {
                    $blncType = $blnc < 0 ? 'Dr' : 'Cr';
                } elseif ($generalLedger->balance_type == 1) {
                    $blncType = $blnc < 0 ? 'Cr' : 'Dr';
                }
                
            @endphp
            <tr>
                <td>{{ $generalLedger->date->format('d/m/Y') }}</td>
                <td>
                    for narration view click <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </td>
                <td class="center">
                    <a href="{{ route($url, [$generalLedger->transaction_id, $generalLedger->source]) }}"
                        style="color: green;text-decoration: underline">{{ $generalLedger->transaction_id }}</a>
                </td>
                <td>{{ $generalLedger->source }}</td>
                <td>{{ abs($debit) }}</td>
                <td>{{ abs($credit) }}</td>
                <td>{{ abs($generalLedger->gst) }}</td>
                <td>{{ abs($generalLedger->balance) }}</td>
                <td>
                    {{ nFA2($blnc) . ' ' . $blncType }}
                </td>
            </tr>
        @endforeach
        <tr>
            <td colspan="4">Total</td>
            <td style="color: red">{{ abs($Sdebit) }}
            </td>
            <td style="color: red">{{ abs($Scredit) }}
            </td>
            <td style="color: red">
                {{ abs($client_account_code->generalLedger->sum('gst')) }}
            </td>
            <td style="color: red">
                {{ abs($client_account_code->generalLedger->sum('balance')) }}</td>
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
            $retainBalance = $retains->sum('balance');
        @endphp
        <tr>
            <td colspan="8">Opening Balance</td>
            <td>{{ abs($retainBalance) . ' ' . ($retainBalance <= 0 ? 'Dr' : 'Cr') }}</td>
        </tr>
    @endif
</table>
