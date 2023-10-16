<table>
    <tr>
        <th>{{$client->fullname}}</th>
    </tr>
    <tr>
        <th>GST Reconciliation</th>
    </tr>
    <tr>
        <th>From Date : {{ '01/07/'.($period->year-1)}} To {{
            '30/06/'.$period->year}}</th>
    </tr>
</table>
<table>
    <thead>
        <tr>
            <th>Item</th>
            <th colspan="3">July - Sep</th>
            <th colspan="3">oct - Dec</th>
            <th colspan="3">Jan - Mar</th>
            <th colspan="3">Apr - June</th>
            <th colspan="3">Total Year</th>
        </tr>
        <tr>
            <th></th>
            <th>GL</th>
            <th>ATO</th>
            <th>Differ</th>
            <th>GL</th>
            <th>ATO</th>
            <th>Differ</th>
            <th>GL</th>
            <th>ATO</th>
            <th>Differ</th>
            <th>GL</th>
            <th>ATO</th>
            <th>Differ</th>
            <th>GL Total</th>
            <th>ATO Total</th>
            <th>Differ</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($reconcilations as $recon)
        <tr>
            <td>
                {{$recon->item}}
            </td>
            <td>
                {{$jul_sep_g1_gl = $recon->jul_sep_gl}}
            </td>
            <td>
                {{$recon->jul_sep_ato}}
            </td>
            <td>
                {{number_format($recon->jul_sep_ato,2)}}
            </td>
            <td>
                {{$oct_dec_g1_gl = $recon->oct_dec_gl}}
            </td>
            <td>
                {{$recon->oct_dec_ato}}
            </td>
            <td>
                {{$recon->oct_dec_ato}}
            </td>
            <td>
                {{$jan_mar_g1_gl = $recon->oct_dec_gl}}
            </td>
            <td>
                {{$recon->jan_mar_ato}}
            </td>
            <td>
                0.00
            </td>
            <td>
                {{$apr_jun_g1_gl = $recon->apr_jun_gl}}
            </td>
            <td>
                {{$recon->apr_jun_ato}}
            </td>
            <td>
                0.00
            </td>
            <td>
                {{$total_g1 = $jul_sep_g1_gl + $oct_dec_g1_gl + $jan_mar_g1_gl + $apr_jun_g1_gl}}
            </td>
            <td>
                0.00
            </td>
            <td>
                0.00
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
<table>
    <thead>
        <tr>
            <th style="width: 150px">Particular</th>
            <th style="width: 100px">P/L amnt. as per bas</th>
            <th style="width: 100px">P/L amt as per complete report</th>
            <th style="width: 100px">P/L as per to ATO</th>
            <th style="width: 100px">Diff</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($taxes as $tax)
        <tr>
            <td>{{$tax->particular}}</td>
            <td>
                {{abs_number($tax->bas)}}
            </td>
            <td>
                {{abs_number($tax->report)}}
            </td>
            <td>
                {{abs_number($tax->ato)}}
            </td>
            <td>
                0.00
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
