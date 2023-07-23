<table class="table-hover table-striped tablecustome">
    <tr>
        <th width="70%" align="left"><u>Description</u></th>
        <th width="30%" style="text-align:right;"><u>AUD($)</u></th>
    </tr>
    @foreach ($incomeCodes as $incomeCode)
    @if ($incomeCode->id != '')
    <tr>
        <th>{{$incomeCode->client_account_code->name}}</th>
        <th style="text-align: right;">
            {{number_format($incomeCode->inBalance,2)}}</th>
    </tr>
    @endif
    @endforeach
    <tr>
        <th width="82%">Total Turnover/Income</th>
        <th width="18%" align="right"
            style="border-bottom:1px solid #000000; border-top:1px solid #000000; text-align:right;">
            $ {{number_format(abs($totalIncome),2)}} </th>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    @foreach ($expensCodes as $expensCode)
    @if ($expensCode->id != '')
    <tr>
        <th>{{$expensCode->client_account_code->name}}</th>
        <th style="text-align: right;">
            {{number_format($expensCode->exBalance,2)}}</th>
    </tr>
    @endif
    @endforeach

    <tr>
        <th>Total Expenses</th>
        <th style="border-bottom:1px solid #000000; border-top:1px solid #000000; text-align:right;">
            ${{number_format(abs($totalExpense),2)}}</th>
    </tr>
    <tr>
        <th> <span style="color:red;">Net Profit/Loss</span> </th>
        <th style="text-align:right;"> <span style="color:red;">$
                {{number_format(abs($totalIncome) - abs($totalExpense),2)}}

            </span> </th>
    </tr>

    <tr>
        <th></th>
        <th style="border-bottom:1px solid #000000; border-top:1px solid #000000; padding-top:2px; text-align:right;">
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
        <td colspan="2">
            <br><br>...................................................
        </td>
    </tr>

    <tr>
        <td>Date : {{ date('d/m/Y') }}</td>
        <td></td>
    </tr>

    <tr>
        <td colspan="2" class="text-center">
            <b>Powered by <a href="https://aarks.com.au">AARKS</a> <a href="https://aarks.net.au">(ADVANCED ACCOUNTING &
                    RECORD
                    KEEPING SOFTWARE)</a></b>
        </td>
    </tr>
</table>
