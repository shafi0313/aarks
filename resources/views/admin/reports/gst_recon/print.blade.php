<!doctype html>
<html lang="en">

    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
        <title>{{$client->fullname}} - GST Reconcilation</title>
        @include('admin.reports.gst_recon.css')
        @include('frontend.print-css')
    </head>

    <body>
            <div align="center">
                <h1>{{$client->fullname}}</h1>
                <h2>GST Reconcilation</h2>
                <h4><u> From Date : {{ '01/07/'.($period->year-1)}} To {{
                        '30/06/'.$period->year}}</u></h4>
            </div>
            <div class="tg-wrap">
                <table class="tg">
                    <thead>
                        <tr>
                            <th class="tg-nw0z">Item</th>
                            <th class="tg-wq9b" colspan="3">July - Sep</th>
                            <th class="tg-efm8" colspan="3">oct - Dec</th>
                            <th class="tg-x59h" colspan="3">Jan - Mar</th>
                            <th class="tg-7j7k" colspan="3">Apr - June</th>
                            <th class="tg-7h3q" colspan="3">Total Year</th>
                        </tr>
                        <tr>
                            <th class="tg-e1kv"></th>
                            <th class="tg-wtqs">GL</th>
                            <th class="tg-kahv">ATO</th>
                            <th class="tg-j8ny">Differ</th>
                            <th class="tg-4o99">GL</th>
                            <th class="tg-lshw">ATO</th>
                            <th class="tg-hqnx"><span style="font-style:normal">Differ</span>
                            </th>
                            <th class="tg-o5ew">GL</th>
                            <th class="tg-7169">ATO</th>
                            <th class="tg-thn2"><span style="font-style:normal">Differ</span>
                            </th>
                            <th class="tg-9459">GL</th>
                            <th class="tg-q8dw">ATO</th>
                            <th class="tg-darx"><span style="font-style:normal">Differ</span>
                            </th>
                            <th class="tg-be25">GL Total</th>
                            <th class="tg-5q6i">ATO Total</th>
                            <th class="tg-xszb"><span style="font-style:normal">Differ</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reconcilations as $recon)
                        {{-- {{$recon->item}} --}}
                        <tr>
                            <td class="tg-e1kv">{{$recon->item}}</td>
                            <td class="tg-wtqs">
                                {{$jul_sep_g1_gl = $recon->jul_sep_gl}}
                            </td>
                            <td class="tg-kahv">{{$recon->jul_sep_ato}}
                            </td>
                            <td class="tg-j8ny">
                                <span class="jul_sep_diff"
                                    id="jul_sep_g1_diff">{{number_format($recon->jul_sep_ato,2)}}</span>
                            </td>
                            <td class="tg-4o99">
                                {{$oct_dec_g1_gl = $recon->oct_dec_gl}}
                            </td>
                            <td class="tg-lshw">{{$recon->oct_dec_ato}}
                            </td>
                            <td class="tg-hqnx">
                                <span class="oct_dec_diff" id="oct_dec_g1_diff">{{$recon->oct_dec_ato}}</span>
                            </td>
                            <td class="tg-o5ew">
                                {{$jan_mar_g1_gl = $recon->oct_dec_gl}}
                            </td>
                            <td class="tg-7169">{{$recon->jan_mar_ato}}
                            </td>
                            <td class="tg-thn2">
                                <span class="jan_mar_diff" id="jan_mar_g1_diff">0.00</span>
                            </td>
                            <td class="tg-9459">
                                {{$apr_jun_g1_gl = $recon->apr_jun_gl}}
                            </td>
                            <td class="tg-q8dw">{{$recon->apr_jun_ato}}
                            </td>
                            <td class="tg-darx">
                                <span class="apr_jun_diff" id="apr_jun_g1_diff">0.00</span>
                            </td>
                            <td class="tg-be25">
                                {{$total_g1 = $jul_sep_g1_gl + $oct_dec_g1_gl + $jan_mar_g1_gl +
                                $apr_jun_g1_gl}}
                            </td>
                            <td class="tg-5q6i">
                                <span class="g1">0.00</span>
                            </td>
                            <td class="tg-xszb">
                                <span class="g1_diff">0.00</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="page-break"></div>
            <div class="tax_return mt-5 col-md-4">
                <h1 class="text-center">Income Tax return Reconcilation</h1>
                <table class="tax_table">
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
                                <span class="tax_gl_g1">{{abs_number($tax->bas)}}</span>
                            </td>
                            <td>
                                <span class="tax_pl_g1">{{abs_number($tax->report)}}</span>
                            </td>
                            <td>
                                <span class="g1 tax_g1 tax_ato_g1">{{abs_number($tax->ato)}}</span>
                            </td>
                            <td>
                                <span class="tax_diff_g1">0.00</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        <script src="https://code.jquery.com/jquery-3.4.1.min.js">
        </script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js">
        </script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js">
        </script>
        @include('admin.reports.gst_recon.js')
    </body>

</html>
