<table width="100%" border="1" cellspacing="5" cellpadding="5">
    <thead>
        <tr>
            <th style="text-align:center;" width="20%">A/c Code</th>
            <th style="text-align:center;" width="20%">Narration and
                Note</th>
            <th style="text-align:center;" width="6%">Txcode</th>
            <th style="text-align:center;" width="11%">Payment</th>
            <th style="text-align:center;" width="11%">Recevied</th>
            <th style="text-align:center;" width="11%">GST</th>
            <th style="text-align:center;" width="11%">RL</th>
            <th style="text-align:center;" width="2%">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($cashbooks as $cashbook)
        <tr>
            <td align="center">
                {{$cashbook->accountCode->name}}
            </td>
            <td align="center">{{$cashbook->narration}} </td>
            <td align="center">{{$cashbook->gst_code}}</td>
            <td align="center">
                {{$cashbook->ac_type == 1?number_format($cashbook->amount_debit,2):'0.00'}}
            </td>
            <td align="center">
                {{$cashbook->ac_type == 2?number_format($cashbook->amount_credit,2):'0.00'}}
            </td>

            <td align="center">
                @if($client->gst_method == 1)
                {{$cashbook->ac_type ==
                1?number_format($cashbook->gst_cash_debit,2):number_format($cashbook->gst_cash_credit,2)}}
                @else
                {{$cashbook->ac_type ==
                2?number_format($cashbook->gst_accrued_credit,2):number_format($cashbook->gst_accrued_debit,2)}}
                @endif
            </td>
            <td align="center">0.00</td>
            <td align="center">
                @include('admin.trashed.action', ['item' => $cashbook])
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
