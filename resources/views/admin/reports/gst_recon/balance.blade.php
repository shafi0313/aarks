@extends('admin.layout.master')
@section('title', 'GST Reconciliation for TR')
@section('style')
    @include('admin.reports.gst_recon.css')
@endsection
@section('content')

    <div class="main-content">
        <div class="main-content-inner">
            <div class="breadcrumbs ace-save-state" id="breadcrumbs">
                <ul class="breadcrumb">
                    <li>
                        <i class="ace-icon fa fa-home home-icon"></i>
                        <a href="{{ route('admin.dashboard') }}">Home</a>
                    </li>
                    <li>Report</li>
                    <li>GST Reconciliation for TR</li>
                    <li>{{ $client->fullname }}</li>
                    <li class="active">{{ $profession->name }}</li>
                </ul><!-- /.breadcrumb -->
            </div>

            <div class="page-content">
                <div class="row">
                    <div class="col-lg-12">
                        <!-- PAGE CONTENT BEGINS -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        <h3 class="panel-title"><i class="glyphicon glyphicon-align-justify"></i>
                                            {{ $client->fullname }}
                                        </h3>
                                    </div>
                                    <div align="center">
                                        <h1>{{ $client->fullname }}</h1>
                                        <h2>GST Reconciliation</h2>
                                        <h4><u> From Date : {{ '01/07/' . ($period->year - 1) }} To
                                                {{ '30/06/' . $period->year }}</u></h4>
                                    </div>
                                    @php
                                        $year = $period->year;
                                        $july = $year - 1 . '-07-01';
                                        $sep = $year - 1 . '-09-30';
                                        $oct = $year - 1 . '-10-01';
                                        $dec = $year - 1 . '-12-31';
                                        $jan = $year . '-01-01';
                                        $mar = $year . '-03-31';
                                        $apr = $year . '-04-01';
                                        $june = $year . '-06-30';
                                        // G1
                                        $recon_g1 = optional($recons->where('item', 'g1')->first());
                                        $re_g1_jul_sep_ato = abs($recon_g1->jul_sep_ato);
                                        $re_g1_oct_dec_ato = abs($recon_g1->oct_dec_ato);
                                        $re_g1_jan_mar_ato = abs($recon_g1->jan_mar_ato);
                                        $re_g1_apr_jun_ato = abs($recon_g1->apr_jun_ato);
                                        
                                        $re_g1_jul_sep_diff = abs($recon_g1->jul_sep_gl) - abs($recon_g1->jul_sep_ato);
                                        $re_g1_oct_dec_diff = abs($recon_g1->oct_dec_gl) - abs($recon_g1->oct_dec_ato);
                                        $re_g1_jan_mar_diff = abs($recon_g1->jan_mar_gl) - abs($recon_g1->jan_mar_ato);
                                        $re_g1_apr_jun_diff = abs($recon_g1->apr_jun_gl) - abs($recon_g1->apr_jun_ato);
                                        
                                        $re_g1_total = abs($re_g1_jul_sep_ato + $re_g1_oct_dec_ato + $re_g1_jan_mar_ato + $re_g1_apr_jun_ato);
                                        $re_g1_total_diff = $re_g1_jul_sep_diff + $re_g1_oct_dec_diff + $re_g1_jan_mar_diff + $re_g1_apr_jun_diff;
                                        // G1
                                        
                                        // G3
                                        $recon_g3 = optional($recons->where('item', 'g3')->first());
                                        $re_g3_jul_sep_ato = abs($recon_g3->jul_sep_ato);
                                        $re_g3_oct_dec_ato = abs($recon_g3->oct_dec_ato);
                                        $re_g3_jan_mar_ato = abs($recon_g3->jan_mar_ato);
                                        $re_g3_apr_jun_ato = abs($recon_g3->apr_jun_ato);
                                        
                                        $re_g3_jul_sep_diff = abs($recon_g3->jul_sep_gl) - abs($recon_g3->jul_sep_ato);
                                        $re_g3_oct_dec_diff = abs($recon_g3->oct_dec_gl) - abs($recon_g3->oct_dec_ato);
                                        $re_g3_jan_mar_diff = abs($recon_g3->jan_mar_gl) - abs($recon_g3->jan_mar_ato);
                                        $re_g3_apr_jun_diff = abs($recon_g3->apr_jun_gl) - abs($recon_g3->apr_jun_ato);
                                        
                                        $re_g3_total = abs($re_g3_jul_sep_ato + $re_g3_oct_dec_ato + $re_g3_jan_mar_ato + $re_g3_apr_jun_ato);
                                        $re_g3_total_diff = $re_g3_jul_sep_diff + $re_g3_oct_dec_diff + $re_g3_jan_mar_diff + $re_g3_apr_jun_diff;
                                        // g3
                                        
                                        // 1a
                                        $recon_1a = optional($recons->where('item', '1a')->first());
                                        $re_1a_jul_sep_ato = abs($recon_1a->jul_sep_ato);
                                        $re_1a_oct_dec_ato = abs($recon_1a->oct_dec_ato);
                                        $re_1a_jan_mar_ato = abs($recon_1a->jan_mar_ato);
                                        $re_1a_apr_jun_ato = abs($recon_1a->apr_jun_ato);
                                        
                                        $re_1a_jul_sep_diff = abs($recon_1a->jul_sep_gl) - abs($recon_1a->jul_sep_ato);
                                        $re_1a_oct_dec_diff = abs($recon_1a->oct_dec_gl) - abs($recon_1a->oct_dec_ato);
                                        $re_1a_jan_mar_diff = abs($recon_1a->jan_mar_gl) - abs($recon_1a->jan_mar_ato);
                                        $re_1a_apr_jun_diff = abs($recon_1a->apr_jun_gl) - abs($recon_1a->apr_jun_ato);
                                        
                                        $re_1a_total = abs($re_1a_jul_sep_ato + $re_1a_oct_dec_ato + $re_1a_jan_mar_ato + $re_1a_apr_jun_ato);
                                        $re_1a_total_diff = $re_1a_jul_sep_diff + $re_1a_oct_dec_diff + $re_1a_jan_mar_diff + $re_1a_apr_jun_diff;
                                        // 1a
                                        
                                        // g11
                                        $recon_g11 = optional($recons->where('item', 'g11')->first());
                                        $re_g11_jul_sep_ato = abs($recon_g11->jul_sep_ato);
                                        $re_g11_oct_dec_ato = abs($recon_g11->oct_dec_ato);
                                        $re_g11_jan_mar_ato = abs($recon_g11->jan_mar_ato);
                                        $re_g11_apr_jun_ato = abs($recon_g11->apr_jun_ato);
                                        
                                        $re_g11_jul_sep_diff = abs($recon_g11->jul_sep_gl) - abs($recon_g11->jul_sep_ato);
                                        $re_g11_oct_dec_diff = abs($recon_g11->oct_dec_gl) - abs($recon_g11->oct_dec_ato);
                                        $re_g11_jan_mar_diff = abs($recon_g11->jan_mar_gl) - abs($recon_g11->jan_mar_ato);
                                        $re_g11_apr_jun_diff = abs($recon_g11->apr_jun_gl) - abs($recon_g11->apr_jun_ato);
                                        
                                        $re_g11_total = abs($re_g11_jul_sep_ato + $re_g11_oct_dec_ato + $re_g11_jan_mar_ato + $re_g11_apr_jun_ato);
                                        $re_g11_total_diff = $re_g11_jul_sep_diff + $re_g11_oct_dec_diff + $re_g11_jan_mar_diff + $re_g11_apr_jun_diff;
                                        // g11
                                        
                                        // 1b
                                        $recon_1b = optional($recons->where('item', '1b')->first());
                                        $re_1b_jul_sep_ato = abs($recon_1b->jul_sep_ato);
                                        $re_1b_oct_dec_ato = abs($recon_1b->oct_dec_ato);
                                        $re_1b_jan_mar_ato = abs($recon_1b->jan_mar_ato);
                                        $re_1b_apr_jun_ato = abs($recon_1b->apr_jun_ato);
                                        
                                        $re_1b_jul_sep_diff = abs($recon_1b->jul_sep_gl) - abs($recon_1b->jul_sep_ato);
                                        $re_1b_oct_dec_diff = abs($recon_1b->oct_dec_gl) - abs($recon_1b->oct_dec_ato);
                                        $re_1b_jan_mar_diff = abs($recon_1b->jan_mar_gl) - abs($recon_1b->jan_mar_ato);
                                        $re_1b_apr_jun_diff = abs($recon_1b->apr_jun_gl) - abs($recon_1b->apr_jun_ato);
                                        
                                        $re_1b_total = abs($re_1b_jul_sep_ato + $re_1b_oct_dec_ato + $re_1b_jan_mar_ato + $re_1b_apr_jun_ato);
                                        $re_1b_total_diff = $re_1b_jul_sep_diff + $re_1b_oct_dec_diff + $re_1b_jan_mar_diff + $re_1b_apr_jun_diff;
                                        // 1b
                                        
                                        // w1
                                        $recon_w1 = optional($recons->where('item', 'w1')->first());
                                        $re_w1_jul_sep_ato = abs($recon_w1->jul_sep_ato);
                                        $re_w1_oct_dec_ato = abs($recon_w1->oct_dec_ato);
                                        $re_w1_jan_mar_ato = abs($recon_w1->jan_mar_ato);
                                        $re_w1_apr_jun_ato = abs($recon_w1->apr_jun_ato);
                                        
                                        $re_w1_jul_sep_diff = abs($recon_w1->jul_sep_gl) - abs($recon_w1->jul_sep_ato);
                                        $re_w1_oct_dec_diff = abs($recon_w1->oct_dec_gl) - abs($recon_w1->oct_dec_ato);
                                        $re_w1_jan_mar_diff = abs($recon_w1->jan_mar_gl) - abs($recon_w1->jan_mar_ato);
                                        $re_w1_apr_jun_diff = abs($recon_w1->apr_jun_gl) - abs($recon_w1->apr_jun_ato);
                                        
                                        $re_w1_total = abs($re_w1_jul_sep_ato + $re_w1_oct_dec_ato + $re_w1_jan_mar_ato + $re_w1_apr_jun_ato);
                                        $re_w1_total_diff = $re_w1_jul_sep_diff + $re_w1_oct_dec_diff + $re_w1_jan_mar_diff + $re_w1_apr_jun_diff;
                                        // w1
                                        
                                        // w2
                                        $recon_w2 = optional($recons->where('item', 'w2')->first());
                                        $re_w2_jul_sep_ato = abs($recon_w2->jul_sep_ato);
                                        $re_w2_oct_dec_ato = abs($recon_w2->oct_dec_ato);
                                        $re_w2_jan_mar_ato = abs($recon_w2->jan_mar_ato);
                                        $re_w2_apr_jun_ato = abs($recon_w2->apr_jun_ato);
                                        
                                        $re_w2_jul_sep_diff = abs($recon_w2->jul_sep_gl) - abs($recon_w2->jul_sep_ato);
                                        $re_w2_oct_dec_diff = abs($recon_w2->oct_dec_gl) - abs($recon_w2->oct_dec_ato);
                                        $re_w2_jan_mar_diff = abs($recon_w2->jan_mar_gl) - abs($recon_w2->jan_mar_ato);
                                        $re_w2_apr_jun_diff = abs($recon_w2->apr_jun_gl) - abs($recon_w2->apr_jun_ato);
                                        
                                        $re_w2_total = abs($re_w2_jul_sep_ato + $re_w2_oct_dec_ato + $re_w2_jan_mar_ato + $re_w2_apr_jun_ato);
                                        $re_w2_total_diff = $re_w2_jul_sep_diff + $re_w2_oct_dec_diff + $re_w2_jan_mar_diff + $re_w2_apr_jun_diff;
                                        // w2
                                        
                                        // g10
                                        $recon_g10 = optional($recons->where('item', 'g10')->first());
                                        $re_g10_jul_sep_ato = abs($recon_g10->jul_sep_ato);
                                        $re_g10_oct_dec_ato = abs($recon_g10->oct_dec_ato);
                                        $re_g10_jan_mar_ato = abs($recon_g10->jan_mar_ato);
                                        $re_g10_apr_jun_ato = abs($recon_g10->apr_jun_ato);
                                        
                                        $re_g10_jul_sep_diff = abs($recon_g10->jul_sep_gl) - abs($recon_g10->jul_sep_ato);
                                        $re_g10_oct_dec_diff = abs($recon_g10->oct_dec_gl) - abs($recon_g10->oct_dec_ato);
                                        $re_g10_jan_mar_diff = abs($recon_g10->jan_mar_gl) - abs($recon_g10->jan_mar_ato);
                                        $re_g10_apr_jun_diff = abs($recon_g10->apr_jun_gl) - abs($recon_g10->apr_jun_ato);
                                        
                                        $re_g10_total = abs($re_g10_jul_sep_ato + $re_g10_oct_dec_ato + $re_g10_jan_mar_ato + $re_g10_apr_jun_ato);
                                        $re_g10_total_diff = $re_g10_jul_sep_diff + $re_g10_oct_dec_diff + $re_g10_jan_mar_diff + $re_g10_apr_jun_diff;
                                        // g10
                                        
                                        // 9
                                        $recon_9 = optional($recons->where('item', '9')->first());
                                        $re_9_jul_sep_ato = abs($recon_9->jul_sep_ato);
                                        $re_9_oct_dec_ato = abs($recon_9->oct_dec_ato);
                                        $re_9_jan_mar_ato = abs($recon_9->jan_mar_ato);
                                        $re_9_apr_jun_ato = abs($recon_9->apr_jun_ato);
                                        
                                        $re_9_jul_sep_diff = abs($recon_9->jul_sep_gl) - abs($recon_9->jul_sep_ato);
                                        $re_9_oct_dec_diff = abs($recon_9->oct_dec_gl) - abs($recon_9->oct_dec_ato);
                                        $re_9_jan_mar_diff = abs($recon_9->jan_mar_gl) - abs($recon_9->jan_mar_ato);
                                        $re_9_apr_jun_diff = abs($recon_9->apr_jun_gl) - abs($recon_9->apr_jun_ato);
                                        
                                        $re_9_total = abs($re_9_jul_sep_ato + $re_9_oct_dec_ato + $re_9_jan_mar_ato + $re_9_apr_jun_ato);
                                        $re_9_total_diff = $re_9_jul_sep_diff + $re_9_oct_dec_diff + $re_9_jan_mar_diff + $re_9_apr_jun_diff;
                                        // 9
                                    @endphp
                                    <form
                                        action="{{ route('gst_recon.store', [$client->id, $profession->id, $period->id]) }}"
                                        method="post" enctype="multipart/form-data">
                                        @csrf
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
                                                    {{-- G1 --}}
                                                    <tr>
                                                        <td class="tg-e1kv">G1</td>
                                                        <td class="tg-wtqs">
                                                            {{ $jul_sep_g1_gl = abs($income->whereBetween('trn_date', [$july, $sep])->sum('gross_amount')) }}
                                                            <input type="hidden" name="jul_sep_gl[]" id="jul_sep_g1_gl"
                                                                value="{{ $jul_sep_g1_gl }}">
                                                        </td>
                                                        <td class="tg-kahv">
                                                            <input type="number" step="any" name="jul_sep_ato[]"
                                                                data-total="g1" data-id="jul_sep_g1" id="jul_sep_g1_ato"
                                                                class="form-control g1_ato"
                                                                value="{{ old('jul_sep_ato') ?? $re_g1_jul_sep_ato }}"
                                                                placeholder="0.00">
                                                        </td>
                                                        <td class="tg-j8ny">
                                                            <span class="jul_sep_diff"
                                                                id="jul_sep_g1_diff">{{ nF2($re_g1_jul_sep_diff) }}</span>
                                                            <input type="hidden" value="{{ $re_g1_jul_sep_diff }}"
                                                                name="jul_sep_diff[]" id="jul_sep_g1_diff_inp">
                                                        </td>

                                                        <td class="tg-4o99">
                                                            {{ $oct_dec_g1_gl = abs($income->whereBetween('trn_date', [$oct, $dec])->sum('gross_amount')) }}
                                                            <input type="hidden" name="oct_dec_gl[]" id="oct_dec_g1_gl"
                                                                value="{{ $oct_dec_g1_gl }}">
                                                        </td>
                                                        <td class="tg-lshw">
                                                            <input type="number" step="any" name="oct_dec_ato[]"
                                                                data-total="g1" data-id="oct_dec_g1" id="oct_dec_g1_ato"
                                                                class="form-control g1_ato"
                                                                value="{{ old('oct_dec_ato') ?? $re_g1_oct_dec_ato }}"
                                                                placeholder="0.00">
                                                        </td>
                                                        <td class="tg-hqnx">
                                                            <span class="oct_dec_diff"
                                                                id="oct_dec_g1_diff">{{ nF2($re_g1_oct_dec_diff) }}</span>
                                                            <input type="hidden" name="oct_dec_diff[]"
                                                                id="oct_dec_g1_diff_inp" value="{{ $re_g1_oct_dec_diff }}">
                                                        </td>

                                                        <td class="tg-o5ew">
                                                            {{ $jan_mar_g1_gl = abs($income->whereBetween('trn_date', [$jan, $mar])->sum('gross_amount')) }}
                                                            <input type="hidden" name="jan_mar_gl[]" id="jan_mar_g1_gl"
                                                                value="{{ $jan_mar_g1_gl }}">
                                                        </td>
                                                        <td class="tg-7169">
                                                            <input type="number" step="any" name="jan_mar_ato[]"
                                                                data-total="g1" data-id="jan_mar_g1" id="jan_mar_g1_ato"
                                                                class="form-control g1_ato"
                                                                value="{{ old('jan_mar_ato') ?? $re_g1_jan_mar_ato }}"
                                                                placeholder="0.00">
                                                        </td>
                                                        <td class="tg-thn2">
                                                            <span class="jan_mar_diff"
                                                                id="jan_mar_g1_diff">{{ nF2($re_g1_jan_mar_diff) }}</span>
                                                            <input type="hidden" name="jan_mar_diff[]"
                                                                id="jan_mar_g1_diff_inp"
                                                                value="{{ $re_g1_jan_mar_diff }}">
                                                        </td>

                                                        <td class="tg-9459">
                                                            {{ $apr_jun_g1_gl = abs($income->whereBetween('trn_date', [$apr, $june])->sum('gross_amount')) }}
                                                            <input type="hidden" name="apr_jun_gl[]" id="apr_jun_g1_gl"
                                                                value="{{ $apr_jun_g1_gl }}">
                                                        </td>
                                                        <td class="tg-q8dw">
                                                            <input type="number" step="any" name="apr_jun_ato[]"
                                                                data-total="g1" data-id="apr_jun_g1" id="apr_jun_g1_ato"
                                                                class="form-control g1_ato"
                                                                value="{{ old('apr_jun_ato') ?? $re_g1_apr_jun_ato }}"
                                                                placeholder="0.00">
                                                        </td>
                                                        <td class="tg-darx">
                                                            <span class="apr_jun_diff"
                                                                id="apr_jun_g1_diff">{{ nF2($re_g1_apr_jun_diff) }}</span>
                                                            <input type="hidden" name="apr_jun_diff[]"
                                                                id="apr_jun_g1_diff_inp"
                                                                value="{{ $re_g1_apr_jun_diff }}">
                                                        </td>

                                                        <td class="tg-be25">
                                                            {{ $total_g1 = abs($income->sum('gross_amount')) }}
                                                            <input type="hidden" id="g1_total"
                                                                value="{{ $total_g1 }}">
                                                        </td>
                                                        <td class="tg-5q6i">
                                                            <span class="g1">{{ nF2($re_g1_total) }}</span>
                                                        </td>
                                                        <td class="tg-xszb">
                                                            <span class="g1_diff">{{ nF2($re_g1_total_diff) }}</span>
                                                        </td>
                                                    </tr>

                                                    {{-- G3 --}}
                                                    <tr>
                                                        <td class="tg-e1kv">G3</td>
                                                        <td class="tg-wtqs">
                                                            {{ $jul_sep_g3_gl = abs($incomeNonGst->whereBetween('trn_date', [$july, $sep])->sum('net_amount')) }}
                                                            <input type="hidden" name="jul_sep_gl[]" id="jul_sep_g3_gl"
                                                                value="{{ $jul_sep_g3_gl }}">
                                                        </td>
                                                        <td class="tg-kahv">
                                                            <input type="number" step="any" name="jul_sep_ato[]"
                                                                data-total="g3" data-id="jul_sep_g3" id="jul_sep_g3_ato"
                                                                class="form-control g3_ato"
                                                                value="{{ old('jul_sep_ato') ?? $re_g3_jul_sep_ato }}"
                                                                placeholder="0.00">
                                                        </td>
                                                        <td class="tg-j8ny">
                                                            <span class="jul_sep_diff"
                                                                id="jul_sep_g3_diff">{{ nF2($re_g3_jul_sep_diff) }}</span>
                                                            <input type="hidden" name="jul_sep_diff[]"
                                                                id="jul_sep_g3_diff_inp" value={{ $re_g3_jul_sep_diff }}>
                                                        </td>
                                                        <td class="tg-4o99">
                                                            {{ $oct_dec_g3_gl = abs($incomeNonGst->whereBetween('trn_date', [$oct, $dec])->sum('net_amount')) }}
                                                            <input type="hidden" name="oct_dec_gl[]" id="oct_dec_g3_gl"
                                                                value="{{ $oct_dec_g3_gl }}">
                                                        </td>
                                                        <td class="tg-lshw">
                                                            <input type="number" step="any" name="oct_dec_ato[]"
                                                                data-total="g3" data-id="oct_dec_g3" id="oct_dec_g3_ato"
                                                                class="form-control g3_ato"
                                                                value="{{ old('oct_dec_ato') ?? $re_g3_oct_dec_ato }}"
                                                                placeholder="0.00">
                                                        </td>
                                                        <td class="tg-hqnx">
                                                            <span class="oct_dec_diff"
                                                                id="oct_dec_g3_diff">{{ nF2($re_g3_oct_dec_diff) }}</span>
                                                            <input type="hidden" name="oct_dec_diff[]"
                                                                id="oct_dec_g3_diff_inp" value={{ $re_g3_oct_dec_diff }}>
                                                        </td>
                                                        <td class="tg-o5ew">
                                                            {{ $jan_mar_g3_gl = abs($incomeNonGst->whereBetween('trn_date', [$jan, $mar])->sum('net_amount')) }}
                                                            <input type="hidden" name="jan_mar_gl[]" id="jan_mar_g3_gl"
                                                                value="{{ $jan_mar_g3_gl }}">
                                                        </td>
                                                        <td class="tg-7169">
                                                            <input type="number" step="any" name="jan_mar_ato[]"
                                                                data-total="g3" data-id="jan_mar_g3" id="jan_mar_g3_ato"
                                                                class="form-control g3_ato"
                                                                value="{{ old('jan_mar_ato') ?? $re_g3_jan_mar_ato }}"
                                                                placeholder="0.00">
                                                        </td>
                                                        <td class="tg-thn2">
                                                            <span class="jan_mar_diff"
                                                                id="jan_mar_g3_diff">{{ nF2($re_g3_jan_mar_diff) }}</span>
                                                            <input type="hidden" name="jan_mar_diff[]"
                                                                id="jan_mar_g3_diff_inp" value={{ $re_g3_jan_mar_diff }}>
                                                        </td>
                                                        <td class="tg-9459">
                                                            {{ $apr_jun_g3_gl = abs($incomeNonGst->whereBetween('trn_date', [$apr, $june])->sum('net_amount')) }}
                                                            <input type="hidden" name="apr_jun_gl[]" id="apr_jun_g3_gl"
                                                                value="{{ $apr_jun_g3_gl }}">
                                                        </td>
                                                        <td class="tg-q8dw">
                                                            <input type="number" step="any" name="apr_jun_ato[]"
                                                                data-total="g3" data-id="apr_jun_g3" id="apr_jun_g3_ato"
                                                                class="form-control g3_ato"
                                                                value="{{ old('apr_jun_ato') ?? $re_g3_apr_jun_ato }}"
                                                                placeholder="0.00">
                                                        </td>
                                                        <td class="tg-darx">
                                                            <span class="apr_jun_diff"
                                                                id="apr_jun_g3_diff">{{ nF2($re_g3_apr_jun_diff) }}</span>
                                                            <input type="hidden" name="apr_jun_diff[]"
                                                                id="apr_jun_g3_diff_inp" value={{ $re_g3_apr_jun_diff }}>
                                                        </td>
                                                        <td class="tg-be25">
                                                            {{ $total_g3 = abs($incomeNonGst->sum('net_amount')) }}
                                                            <input type="hidden" id="g3_total"
                                                                value="{{ $total_g3 }}">
                                                        </td>
                                                        <td class="tg-5q6i">
                                                            <span class="g3">{{ nF2($re_g3_total) }}</span>
                                                        </td>
                                                        <td class="tg-xszb">
                                                            <span class="g3_diff">{{ nF2($re_g3_total_diff) }}</span>
                                                        </td>
                                                    </tr>
                                                    {{-- 1A --}}
                                                    <tr>
                                                        <td class="tg-e1kv">1A</td>
                                                        <td class="tg-wtqs">
                                                            {{ $jul_sep_1a_gl = abs($income->whereBetween('trn_date', [$july, $sep])->sum('gst_cash_amount')) }}
                                                            <input type="hidden" name="jul_sep_gl[]" id="jul_sep_1a_gl"
                                                                value="{{ $jul_sep_1a_gl }}">

                                                        </td>
                                                        <td class="tg-kahv">
                                                            <input type="number" step="any" name="jul_sep_ato[]"
                                                                data-total="1a" data-id="jul_sep_1a" id="jul_sep_1a_ato"
                                                                class="form-control 1a_ato"
                                                                value="{{ old('jul_sep_ato') ?? $re_1a_jul_sep_ato }}"
                                                                placeholder="0.00">
                                                        </td>
                                                        <td class="tg-j8ny">
                                                            <span class="jul_sep_diff"
                                                                id="jul_sep_1a_diff">{{ nF2($re_1a_jul_sep_diff) }}</span>
                                                            <input type="hidden" name="jul_sep_diff[]"
                                                                id="jul_sep_1a_diff_inp"
                                                                value="{{ $re_1a_jul_sep_diff }}">
                                                        </td>
                                                        <td class="tg-4o99">
                                                            {{ $oct_dec_1a_gl = abs($income->whereBetween('trn_date', [$oct, $dec])->sum('gst_cash_amount')) }}
                                                            <input type="hidden" name="oct_dec_gl[]" id="oct_dec_1a_gl"
                                                                value="{{ $oct_dec_1a_gl }}">
                                                        </td>
                                                        <td class="tg-lshw">
                                                            <input type="number" step="any" name="oct_dec_ato[]"
                                                                data-total="1a" data-id="oct_dec_1a" id="oct_dec_1a_ato"
                                                                class="form-control 1a_ato"
                                                                value="{{ old('oct_dec_ato') ?? $re_1a_oct_dec_ato }}"
                                                                placeholder="0.00">
                                                        </td>
                                                        <td class="tg-hqnx">
                                                            <span class="oct_dec_diff"
                                                                id="oct_dec_1a_diff">{{ nF2($re_1a_oct_dec_diff) }}</span>
                                                            <input type="hidden" name="oct_dec_diff[]"
                                                                id="oct_dec_1a_diff_inp"
                                                                value="{{ $re_1a_oct_dec_diff }}">
                                                        </td>
                                                        <td class="tg-o5ew">
                                                            {{ $jan_mar_1a_gl = abs($income->whereBetween('trn_date', [$jan, $mar])->sum('gst_cash_amount')) }}
                                                            <input type="hidden" name="jan_mar_gl[]" id="jan_mar_1a_gl"
                                                                value="{{ $jan_mar_1a_gl }}">
                                                        </td>
                                                        <td class="tg-7169">
                                                            <input type="number" step="any" name="jan_mar_ato[]"
                                                                data-total="1a" data-id="jan_mar_1a" id="jan_mar_1a_ato"
                                                                class="form-control 1a_ato"
                                                                value="{{ old('jan_mar_ato') ?? $re_1a_jan_mar_ato }}"
                                                                placeholder="0.00">
                                                        </td>
                                                        <td class="tg-thn2">
                                                            <span class="jan_mar_diff"
                                                                id="jan_mar_1a_diff">{{ nF2($re_1a_jan_mar_diff) }}</span>
                                                            <input type="hidden" name="jan_mar_diff[]"
                                                                id="jan_mar_1a_diff_inp"
                                                                value="{{ $re_1a_jan_mar_diff }}">
                                                        </td>
                                                        <td class="tg-9459">
                                                            {{ $apr_jun_1a_gl = abs($income->whereBetween('trn_date', [$apr, $june])->sum('gst_cash_amount')) }}
                                                            <input type="hidden" name="apr_jun_gl[]" id="apr_jun_1a_gl"
                                                                value="{{ $apr_jun_1a_gl }}">
                                                        </td>
                                                        <td class="tg-q8dw">
                                                            <input type="number" step="any" name="apr_jun_ato[]"
                                                                data-total="1a" data-id="apr_jun_1a" id="apr_jun_1a_ato"
                                                                class="form-control 1a_ato"
                                                                value="{{ old('apr_jun_ato') ?? $re_1a_apr_jun_ato }}"
                                                                placeholder="0.00">
                                                        </td>
                                                        <td class="tg-darx">
                                                            <span class="apr_jun_diff"
                                                                id="apr_jun_1a_diff">{{ nF2($re_1a_apr_jun_diff) }}</span>
                                                            <input type="hidden" name="apr_jun_diff[]"
                                                                id="apr_jun_1a_diff_inp"
                                                                value="{{ $re_1a_apr_jun_diff }}">
                                                        </td>
                                                        <td class="tg-be25">
                                                            {{ $total_1a = abs($income->sum('gst_cash_amount')) }}
                                                            <input type="hidden" id="1a_total"
                                                                value="{{ $total_1a }}">
                                                        </td>
                                                        <td class="tg-5q6i">
                                                            <span class="1a">{{ nF2($re_1a_total) }}</span>
                                                        </td>
                                                        <td class="tg-xszb">
                                                            <span class="1a_diff">{{ nF2($re_1a_total_diff) }}</span>
                                                        </td>
                                                    </tr>

                                                    {{-- G11 --}}
                                                    <tr>
                                                        <td class="tg-e1kv">G11</td>
                                                        <td class="tg-wtqs">
                                                            {{ $jul_sep_g11_gl = abs($expense_code->whereBetween('trn_date', [$july, $sep])->sum('gross_amount')) }}
                                                            <input type="hidden" name="jul_sep_gl[]" id="jul_sep_g11_gl"
                                                                value="{{ $jul_sep_g11_gl }}">
                                                        </td>
                                                        <td class="tg-kahv">
                                                            <input type="number" step="any" name="jul_sep_ato[]"
                                                                data-total="g11" data-id="jul_sep_g11"
                                                                id="jul_sep_g11_ato" class="form-control g11_ato"
                                                                value="{{ old('jul_sep_ato') ?? $re_g11_jul_sep_ato }}"
                                                                placeholder="0.00">
                                                        </td>
                                                        <td class="tg-j8ny">
                                                            <span class="jul_sep_diff"
                                                                id="jul_sep_g11_diff">{{ nF2($re_g11_jul_sep_diff) }}</span>
                                                            <input type="hidden" name="jul_sep_diff[]"
                                                                id="jul_sep_g11_diff_inp"
                                                                value="{{ $re_g11_jul_sep_diff }}">
                                                        </td>
                                                        <td class="tg-4o99">
                                                            {{ $oct_dec_g11_gl = abs($expense_code->whereBetween('trn_date', [$oct, $dec])->sum('gross_amount')) }}
                                                            <input type="hidden" name="oct_dec_gl[]" id="oct_dec_g11_gl"
                                                                value="{{ $oct_dec_g11_gl }}">
                                                        </td>
                                                        <td class="tg-lshw">
                                                            <input type="number" step="any" name="oct_dec_ato[]"
                                                                data-total="g11" data-id="oct_dec_g11"
                                                                id="oct_dec_g11_ato" class="form-control g11_ato"
                                                                value="{{ old('oct_dec_ato') ?? $re_g11_oct_dec_ato }}"
                                                                placeholder="0.00">
                                                        </td>
                                                        <td class="tg-hqnx">
                                                            <span class="oct_dec_diff"
                                                                id="oct_dec_g11_diff">{{ nF2($re_g11_oct_dec_diff) }}</span>
                                                            <input type="hidden" name="oct_dec_diff[]"
                                                                id="oct_dec_g11_diff_inp"
                                                                value="{{ $re_g11_oct_dec_diff }}">
                                                        </td>
                                                        <td class="tg-o5ew">
                                                            {{ $jan_mar_g11_gl = abs($expense_code->whereBetween('trn_date', [$jan, $mar])->sum('gross_amount')) }}
                                                            <input type="hidden" name="jan_mar_gl[]" id="jan_mar_g11_gl"
                                                                value="{{ $jan_mar_g11_gl }}">
                                                        </td>
                                                        <td class="tg-7169">
                                                            <input type="number" step="any" name="jan_mar_ato[]"
                                                                data-total="g11" data-id="jan_mar_g11"
                                                                id="jan_mar_g11_ato" class="form-control g11_ato"
                                                                value="{{ old('jan_mar_ato') ?? $re_g11_jan_mar_ato }}"
                                                                placeholder="0.00">
                                                        </td>
                                                        <td class="tg-thn2">
                                                            <span class="jan_mar_diff"
                                                                id="jan_mar_g11_diff">{{ nF2($re_g11_jan_mar_diff) }}</span>
                                                            <input type="hidden" name="jan_mar_diff[]"
                                                                id="jan_mar_g11_diff_inp"
                                                                value="{{ $re_g11_jan_mar_diff }}">
                                                        </td>
                                                        <td class="tg-9459">
                                                            {{ $apr_jun_g11_gl = abs($expense_code->whereBetween('trn_date', [$apr, $june])->sum('gross_amount')) }}
                                                            <input type="hidden" name="apr_jun_gl[]" id="apr_jun_g11_gl"
                                                                value="{{ $apr_jun_g11_gl }}">
                                                        </td>
                                                        <td class="tg-q8dw">
                                                            <input type="number" step="any" name="apr_jun_ato[]"
                                                                data-total="g11" data-id="apr_jun_g11"
                                                                id="apr_jun_g11_ato" class="form-control g11_ato"
                                                                value="{{ old('apr_jun_ato') ?? $re_g11_apr_jun_ato }}"
                                                                placeholder="0.00">
                                                        </td>
                                                        <td class="tg-darx">
                                                            <span class="apr_jun_diff"
                                                                id="apr_jun_g11_diff">{{ nF2($re_g11_apr_jun_diff) }}</span>
                                                            <input type="hidden" name="apr_jun_diff[]"
                                                                id="apr_jun_g11_diff_inp"
                                                                value="{{ $re_g11_apr_jun_diff }}">
                                                        </td>
                                                        <td class="tg-be25">
                                                            {{ $total_g11 = abs($expense_code->sum('gross_amount')) }}
                                                            <input type="hidden" id="g11_total"
                                                                value="{{ $total_g11 }}">
                                                        </td>
                                                        <td class="tg-5q6i">
                                                            <span class="g11">{{ nF2($re_g11_total) }}</span>
                                                        </td>
                                                        <td class="tg-xszb">
                                                            <span class="g11_diff">{{ nF2($re_g11_total_diff) }}</span>
                                                        </td>
                                                    </tr>

                                                    {{-- 1B --}}
                                                    <tr>
                                                        <td class="tg-e1kv">1B</td>
                                                        <td class="tg-wtqs">
                                                            {{ $jul_sep_1b_gl = abs($expense->whereBetween('trn_date', [$july, $sep])->sum('gst_cash_amount')) }}
                                                            <input type="hidden" name="jul_sep_gl[]" id="jul_sep_1b_gl"
                                                                value="{{ $jul_sep_1b_gl }}">
                                                        </td>
                                                        <td class="tg-kahv">
                                                            <input type="number" step="any" name="jul_sep_ato[]"
                                                                data-total="1b" data-id="jul_sep_1b" id="jul_sep_1b_ato"
                                                                class="form-control 1b_ato"
                                                                value="{{ old('jul_sep_ato') ?? $re_1b_jul_sep_ato }}"
                                                                placeholder="0.00">
                                                        </td>
                                                        <td class="tg-j8ny">
                                                            <span class="jul_sep_diff"
                                                                id="jul_sep_1b_diff">{{ nF2($re_1b_jul_sep_diff) }}</span>
                                                            <input type="hidden" name="jul_sep_diff[]"
                                                                id="jul_sep_1b_diff_inp"
                                                                value="{{ $re_1b_jul_sep_diff }}">
                                                        </td>
                                                        <td class="tg-4o99">
                                                            {{ $oct_dec_1b_gl = abs($expense->whereBetween('trn_date', [$oct, $dec])->sum('gst_cash_amount')) }}
                                                            <input type="hidden" name="oct_dec_gl[]" id="oct_dec_1b_gl"
                                                                value="{{ $oct_dec_1b_gl }}">
                                                        </td>
                                                        <td class="tg-lshw">
                                                            <input type="number" step="any" name="oct_dec_ato[]"
                                                                data-total="1b" data-id="oct_dec_1b" id="oct_dec_1b_ato"
                                                                class="form-control 1b_ato"
                                                                value="{{ old('oct_dec_ato') ?? $re_1b_oct_dec_ato }}"
                                                                placeholder="0.00">
                                                        </td>
                                                        <td class="tg-hqnx">
                                                            <span class="oct_dec_diff"
                                                                id="oct_dec_1b_diff">{{ nF2($re_1b_oct_dec_diff) }}</span>
                                                            <input type="hidden" name="oct_dec_diff[]"
                                                                id="oct_dec_1b_diff_inp"
                                                                value="{{ $re_1b_oct_dec_diff }}">
                                                        </td>
                                                        <td class="tg-o5ew">
                                                            {{ $jan_mar_1b_gl = abs($expense->whereBetween('trn_date', [$jan, $mar])->sum('gst_cash_amount')) }}
                                                            <input type="hidden" name="jan_mar_gl[]" id="jan_mar_1b_gl"
                                                                value="{{ $jan_mar_1b_gl }}">
                                                        </td>
                                                        <td class="tg-7169">
                                                            <input type="number" step="any" name="jan_mar_ato[]"
                                                                data-total="1b" data-id="jan_mar_1b" id="jan_mar_1b_ato"
                                                                class="form-control 1b_ato"
                                                                value="{{ old('jan_mar_ato') ?? $re_1b_jan_mar_ato }}"
                                                                placeholder="0.00">
                                                        </td>
                                                        <td class="tg-thn2">
                                                            <span class="jan_mar_diff"
                                                                id="jan_mar_1b_diff">{{ nF2($re_1b_jan_mar_diff) }}</span>
                                                            <input type="hidden" name="jan_mar_diff[]"
                                                                id="jan_mar_1b_diff_inp"
                                                                value="{{ $re_1b_jan_mar_diff }}">
                                                        </td>
                                                        <td class="tg-9459">
                                                            {{ $apr_jun_1b_gl = abs($expense->whereBetween('trn_date', [$apr, $june])->sum('gst_cash_amount')) }}
                                                            <input type="hidden" name="apr_jun_gl[]" id="apr_jun_1b_gl"
                                                                value="{{ $apr_jun_1b_gl }}">
                                                        </td>
                                                        <td class="tg-q8dw">
                                                            <input type="number" step="any" name="apr_jun_ato[]"
                                                                data-total="1b" data-id="apr_jun_1b" id="apr_jun_1b_ato"
                                                                class="form-control 1b_ato"
                                                                value="{{ old('apr_jun_ato') ?? $re_1b_apr_jun_ato }}"
                                                                placeholder="0.00">
                                                        </td>
                                                        <td class="tg-darx">
                                                            <span class="apr_jun_diff"
                                                                id="apr_jun_1b_diff">{{ nF2($re_1b_apr_jun_diff) }}</span>
                                                            <input type="hidden" name="apr_jun_diff[]"
                                                                id="apr_jun_1b_diff_inp"
                                                                value="{{ $re_1b_apr_jun_diff }}">
                                                        </td>
                                                        <td class="tg-be25">
                                                            {{ $total_1b = abs($expense->sum('gst_cash_amount')) }}
                                                            <input type="hidden" id="1b_total"
                                                                value="{{ $total_1b }}">
                                                        </td>
                                                        <td class="tg-5q6i">
                                                            <span class="1b">{{ nF2($re_1b_total) }}</span>
                                                        </td>
                                                        <td class="tg-xszb">
                                                            <span class="1b_diff">{{ nF2($re_1b_total_diff) }}</span>
                                                        </td>
                                                    </tr>

                                                    {{-- W1 --}}
                                                    <tr>
                                                        <td class="tg-e1kv">W1</td>
                                                        <td class="tg-wtqs">
                                                            {{ $jul_sep_w1_gl = abs($w1->whereBetween('trn_date', [$july, $sep])->sum('gross_amount')) }}
                                                            <input type="hidden" name="jul_sep_gl[]" id="jul_sep_w1_gl"
                                                                value="{{ $jul_sep_w1_gl }}">
                                                        </td>
                                                        <td class="tg-kahv">
                                                            <input type="number" step="any" name="jul_sep_ato[]"
                                                                data-total="w1" data-id="jul_sep_w1" id="jul_sep_w1_ato"
                                                                class="form-control w1_ato"
                                                                value="{{ old('jul_sep_ato') ?? $re_w1_jul_sep_ato }}"
                                                                placeholder="0.00">
                                                        </td>
                                                        <td class="tg-j8ny">
                                                            <span class="jul_sep_diff"
                                                                id="jul_sep_w1_diff">{{ nF2($re_w1_jul_sep_diff) }}</span>
                                                            <input type="hidden" name="jul_sep_diff[]"
                                                                id="jul_sep_w1_diff_inp">
                                                        </td>
                                                        <td class="tg-4o99">
                                                            {{ $oct_dec_w1_gl = abs($w1->whereBetween('trn_date', [$oct, $dec])->sum('gross_amount')) }}
                                                            <input type="hidden" name="oct_dec_gl[]" id="oct_dec_w1_gl"
                                                                value="{{ $oct_dec_w1_gl }}">
                                                        </td>
                                                        <td class="tg-lshw">
                                                            <input type="number" step="any" name="oct_dec_ato[]"
                                                                data-total="w1" data-id="oct_dec_w1" id="oct_dec_w1_ato"
                                                                class="form-control w1_ato"
                                                                value="{{ old('oct_dec_ato') ?? $re_w1_oct_dec_ato }}"
                                                                placeholder="0.00">
                                                        </td>
                                                        <td class="tg-hqnx">
                                                            <span class="oct_dec_diff"
                                                                id="oct_dec_w1_diff">{{ nF2($re_w1_oct_dec_diff) }}</span>
                                                            <input type="hidden" name="oct_dec_diff[]"
                                                                id="oct_dec_w1_diff_inp">
                                                        </td>
                                                        <td class="tg-o5ew">
                                                            {{ $jan_mar_w1_gl = abs($w1->whereBetween('trn_date', [$jan, $mar])->sum('gross_amount')) }}
                                                            <input type="hidden" name="jan_mar_gl[]" id="jan_mar_w1_gl"
                                                                value="{{ $jan_mar_w1_gl }}">
                                                        </td>
                                                        <td class="tg-7169">
                                                            <input type="number" step="any" name="jan_mar_ato[]"
                                                                data-total="w1" data-id="jan_mar_w1" id="jan_mar_w1_ato"
                                                                class="form-control w1_ato"
                                                                value="{{ old('jan_mar_ato') ?? $re_w1_jan_mar_ato }}"
                                                                placeholder="0.00">
                                                        </td>
                                                        <td class="tg-thn2">
                                                            <span class="jan_mar_diff"
                                                                id="jan_mar_w1_diff">{{ nF2($re_w1_jan_mar_diff) }}</span>
                                                            <input type="hidden" name="jan_mar_diff[]"
                                                                id="jan_mar_w1_diff_inp">
                                                        </td>
                                                        <td class="tg-9459">
                                                            {{ $apr_jun_w1_gl = abs($w1->whereBetween('trn_date', [$apr, $june])->sum('gross_amount')) }}
                                                            <input type="hidden" name="apr_jun_gl[]" id="apr_jun_w1_gl"
                                                                value="{{ $apr_jun_w1_gl }}">
                                                        </td>
                                                        <td class="tg-q8dw">
                                                            <input type="number" step="any" name="apr_jun_ato[]"
                                                                data-total="w1" data-id="apr_jun_w1" id="apr_jun_w1_ato"
                                                                class="form-control w1_ato"
                                                                value="{{ old('apr_jun_ato') ?? $re_w1_apr_jun_ato }}"
                                                                placeholder="0.00">
                                                        </td>
                                                        <td class="tg-darx">
                                                            <span class="apr_jun_diff"
                                                                id="apr_jun_w1_diff">{{ nF2($re_w1_apr_jun_diff) }}</span>
                                                            <input type="hidden" name="apr_jun_diff[]"
                                                                id="apr_jun_w1_diff_inp">
                                                        </td>
                                                        <td class="tg-be25">
                                                            {{ $total_w1 = abs($w1->sum('gross_amount')) }}
                                                            <input type="hidden" id="w1_total"
                                                                value="{{ $total_w1 }}">
                                                        </td>
                                                        <td class="tg-5q6i">
                                                            <span class="w1">{{ nF2($re_w1_total) }}</span>
                                                        </td>
                                                        <td class="tg-xszb">
                                                            <span class="w1_diff">{{ nF2($re_w1_total_diff) }}</span>
                                                        </td>
                                                    </tr>

                                                    {{-- W2 --}}
                                                    <tr>
                                                        <td class="tg-e1kv">W2</td>
                                                        <td class="tg-wtqs">
                                                            {{ $jul_sep_w2_gl = abs($w2->whereBetween('trn_date', [$july, $sep])->sum('gross_amount')) }}
                                                            <input type="hidden" name="jul_sep_gl[]" id="jul_sep_w2_gl"
                                                                value="{{ $jul_sep_w2_gl }}">
                                                        </td>
                                                        <td class="tg-kahv">
                                                            <input type="number" step="any" name="jul_sep_ato[]"
                                                                data-total="w2" data-id="jul_sep_w2" id="jul_sep_w2_ato"
                                                                class="form-control w2_ato"
                                                                value="{{ old('jul_sep_ato') ?? $re_w2_jul_sep_ato }}"
                                                                placeholder="0.00">
                                                        </td>
                                                        <td class="tg-j8ny">
                                                            <span class="jul_sep_diff"
                                                                id="jul_sep_w2_diff">{{ nF2($re_w2_jul_sep_diff) }}</span>
                                                            <input type="hidden" name="jul_sep_diff[]"
                                                                id="jul_sep_w2_diff_inp"
                                                                value="{{ $re_w2_jul_sep_diff }}">
                                                        </td>
                                                        <td class="tg-4o99">
                                                            {{ $oct_dec_w2_gl = abs($w2->whereBetween('trn_date', [$oct, $dec])->sum('gross_amount')) }}
                                                            <input type="hidden" name="oct_dec_gl[]" id="oct_dec_w2_gl"
                                                                value="{{ $oct_dec_w2_gl }}">
                                                        </td>
                                                        <td class="tg-lshw">
                                                            <input type="number" step="any" name="oct_dec_ato[]"
                                                                data-total="w2" data-id="oct_dec_w2" id="oct_dec_w2_ato"
                                                                class="form-control w2_ato"
                                                                value="{{ old('oct_dec_ato') ?? $re_w2_oct_dec_ato }}"
                                                                placeholder="0.00">
                                                        </td>
                                                        <td class="tg-hqnx">
                                                            <span class="oct_dec_diff"
                                                                id="oct_dec_w2_diff">{{ nF2($re_w2_oct_dec_diff) }}</span>
                                                            <input type="hidden" name="oct_dec_diff[]"
                                                                id="oct_dec_w2_diff_inp"
                                                                value="{{ $re_w2_oct_dec_diff }}">
                                                        </td>
                                                        <td class="tg-o5ew">
                                                            {{ $jan_mar_w2_gl = abs($w2->whereBetween('trn_date', [$jan, $mar])->sum('gross_amount')) }}
                                                            <input type="hidden" name="jan_mar_gl[]" id="jan_mar_w2_gl"
                                                                value="{{ $jan_mar_w2_gl }}">
                                                        </td>
                                                        <td class="tg-7169">
                                                            <input type="number" step="any" name="jan_mar_ato[]"
                                                                data-total="w2" data-id="jan_mar_w2" id="jan_mar_w2_ato"
                                                                class="form-control w2_ato"
                                                                value="{{ old('jan_mar_ato') ?? $re_w2_jan_mar_ato }}"
                                                                placeholder="0.00">
                                                        </td>
                                                        <td class="tg-thn2">
                                                            <span class="jan_mar_diff"
                                                                id="jan_mar_w2_diff">{{ nF2($re_w2_jan_mar_diff) }}</span>
                                                            <input type="hidden" name="jan_mar_diff[]"
                                                                id="jan_mar_w2_diff_inp"
                                                                value="{{ $re_w2_jan_mar_diff }}">
                                                        </td>
                                                        <td class="tg-9459">
                                                            {{ $apr_jun_w2_gl = abs($w2->whereBetween('trn_date', [$apr, $june])->sum('gross_amount')) }}
                                                            <input type="hidden" name="apr_jun_gl[]" id="apr_jun_w2_gl"
                                                                value="{{ $apr_jun_w2_gl }}">
                                                        </td>
                                                        <td class="tg-q8dw">
                                                            <input type="number" step="any" name="apr_jun_ato[]"
                                                                data-total="w2" data-id="apr_jun_w2" id="apr_jun_w2_ato"
                                                                class="form-control w2_ato"
                                                                value="{{ old('apr_jun_ato') ?? $re_w2_apr_jun_ato }}"
                                                                placeholder="0.00">
                                                        </td>
                                                        <td class="tg-darx">
                                                            <span class="apr_jun_diff"
                                                                id="apr_jun_w2_diff">{{ nF2($re_w2_apr_jun_diff) }}</span>
                                                            <input type="hidden" name="apr_jun_diff[]"
                                                                id="apr_jun_w2_diff_inp"
                                                                value="{{ $re_w2_apr_jun_diff }}">
                                                        </td>
                                                        <td class="tg-be25">
                                                            {{ $total_w2 = abs($w2->sum('gross_amount')) }}
                                                            <input type="hidden" id="w2_total"
                                                                value="{{ $total_w2 }}">
                                                        </td>
                                                        <td class="tg-5q6i">
                                                            <span class="w2">{{ nF2($re_w2_total) }}</span>
                                                        </td>
                                                        <td class="tg-xszb">
                                                            <span class="w2_diff">{{ nF2($re_w2_total_diff) }}</span>
                                                        </td>
                                                    </tr>

                                                    {{-- G10 --}}
                                                    <tr>
                                                        <td class="tg-e1kv">G10</td>
                                                        <td class="tg-wtqs">
                                                            {{ $jul_sep_g10_gl = abs($asset->whereBetween('trn_date', [$july, $sep])->sum('gross_amount')) }}
                                                            <input type="hidden" name="jul_sep_gl[]" id="jul_sep_g10_gl"
                                                                value="{{ $jul_sep_g10_gl }}">
                                                        </td>
                                                        <td class="tg-kahv">
                                                            <input type="number" step="any" name="jul_sep_ato[]"
                                                                data-total="g10" data-id="jul_sep_g10"
                                                                id="jul_sep_g10_ato" class="form-control g10_ato"
                                                                value="{{ old('jul_sep_ato') ?? $re_g10_jul_sep_ato }}"
                                                                placeholder="0.00">
                                                        </td>
                                                        <td class="tg-j8ny">
                                                            <span class="jul_sep_diff"
                                                                id="jul_sep_g10_diff">{{ nF2($re_g10_jul_sep_diff) }}</span>
                                                            <input type="hidden" name="jul_sep_diff[]"
                                                                id="jul_sep_g10_diff_inp"
                                                                value="{{ $re_g10_jul_sep_diff }}">
                                                        </td>
                                                        <td class="tg-4o99">
                                                            {{ $oct_dec_g10_gl = abs($asset->whereBetween('trn_date', [$oct, $dec])->sum('gross_amount')) }}
                                                            <input type="hidden" name="oct_dec_gl[]" id="oct_dec_g10_gl"
                                                                value="{{ $oct_dec_g10_gl }}">
                                                        </td>
                                                        <td class="tg-lshw">
                                                            <input type="number" step="any" name="oct_dec_ato[]"
                                                                data-total="g10" data-id="oct_dec_g10"
                                                                id="oct_dec_g10_ato" class="form-control g10_ato"
                                                                value="{{ old('oct_dec_ato') ?? $re_g10_oct_dec_ato }}"
                                                                placeholder="0.00">
                                                        </td>
                                                        <td class="tg-hqnx">
                                                            <span class="oct_dec_diff"
                                                                id="oct_dec_g10_diff">{{ nF2($re_g10_oct_dec_diff) }}</span>
                                                            <input type="hidden" name="oct_dec_diff[]"
                                                                id="oct_dec_g10_diff_inp"
                                                                value="{{ $re_g10_oct_dec_diff }}">
                                                        </td>
                                                        <td class="tg-o5ew">
                                                            {{ $jan_mar_g10_gl = abs($asset->whereBetween('trn_date', [$jan, $mar])->sum('gross_amount')) }}
                                                            <input type="hidden" name="jan_mar_gl[]" id="jan_mar_g10_gl"
                                                                value="{{ $jan_mar_g10_gl }}">
                                                        </td>
                                                        <td class="tg-7169">
                                                            <input type="number" step="any" name="jan_mar_ato[]"
                                                                data-total="g10" data-id="jan_mar_g10"
                                                                id="jan_mar_g10_ato" class="form-control g10_ato"
                                                                value="{{ old('jan_mar_ato') ?? $re_g10_jan_mar_ato }}"
                                                                placeholder="0.00">
                                                        </td>
                                                        <td class="tg-thn2">
                                                            <span class="jan_mar_diff"
                                                                id="jan_mar_g10_diff">{{ nF2($re_g10_jan_mar_diff) }}</span>
                                                            <input type="hidden" name="jan_mar_diff[]"
                                                                id="jan_mar_g10_diff_inp"
                                                                value="{{ $re_g10_jan_mar_diff }}">
                                                        </td>
                                                        <td class="tg-9459">
                                                            {{ $apr_jun_g10_gl = abs($asset->whereBetween('trn_date', [$apr, $june])->sum('gross_amount')) }}
                                                            <input type="hidden" name="apr_jun_gl[]" id="apr_jun_g10_gl"
                                                                value="{{ $apr_jun_g10_gl }}">
                                                        </td>
                                                        <td class="tg-q8dw">
                                                            <input type="number" step="any" name="apr_jun_ato[]"
                                                                data-total="g10" data-id="apr_jun_g10"
                                                                id="apr_jun_g10_ato" class="form-control g10_ato"
                                                                value="{{ old('apr_jun_ato') ?? $re_g10_apr_jun_ato }}"
                                                                placeholder="0.00">
                                                        </td>
                                                        <td class="tg-darx">
                                                            <span class="apr_jun_diff"
                                                                id="apr_jun_g10_diff">{{ nF2($re_g10_apr_jun_diff) }}</span>
                                                            <input type="hidden" name="apr_jun_diff[]"
                                                                id="apr_jun_g10_diff_inp"
                                                                value="{{ $re_g10_apr_jun_diff }}">
                                                        </td>
                                                        <td class="tg-be25">
                                                            {{ $total_g10 = abs($asset->sum('gross_amount')) }}
                                                            <input type="hidden" id="g10_total"
                                                                value="{{ $total_g10 }}">
                                                        </td>
                                                        <td class="tg-5q6i">
                                                            <span class="g10">{{ nF2($re_g10_total) }}</span>
                                                        </td>
                                                        <td class="tg-xszb">
                                                            <span class="g10_diff">{{ nF2($re_g10_total_diff) }}</span>
                                                        </td>
                                                    </tr>

                                                    {{-- 9 = 1A-1B --}}
                                                    <tr>
                                                        <td class="tg-e1kv">9</td>
                                                        <td class="tg-wtqs">
                                                            {{ $jul_sep_9_gl = abs($jul_sep_1a_gl - $jul_sep_1b_gl) }}
                                                            <input type="hidden" name="jul_sep_gl[]" id="jul_sep_9_gl"
                                                                value="{{ $jul_sep_9_gl }}">
                                                        </td>
                                                        <td class="tg-kahv">
                                                            <input type="number" step="any" name="jul_sep_ato[]"
                                                                data-total="9" data-id="jul_sep_9" id="jul_sep_9_ato"
                                                                class="form-control 9_ato"
                                                                value="{{ old('jul_sep_ato') ?? $re_9_jul_sep_ato }}"
                                                                placeholder="0.00">
                                                        </td>
                                                        <td class="tg-j8ny">
                                                            <span class="jul_sep_diff"
                                                                id="jul_sep_9_diff">{{ nF2($re_9_jul_sep_diff) }}</span>
                                                            <input type="hidden" name="jul_sep_diff[]"
                                                                id="jul_sep_9_diff_inp"
                                                                value="{{ $re_9_jul_sep_diff }}">
                                                        </td>
                                                        <td class="tg-4o99">
                                                            {{ $oct_dec_9_gl = abs($oct_dec_1a_gl - $oct_dec_1b_gl) }}
                                                            <input type="hidden" name="oct_dec_gl[]" id="oct_dec_9_gl"
                                                                value="{{ nF2($oct_dec_9_gl) }}">
                                                        </td>
                                                        <td class="tg-lshw">
                                                            <input type="number" step="any" name="oct_dec_ato[]"
                                                                data-total="9" data-id="oct_dec_9" id="oct_dec_9_ato"
                                                                class="form-control 9_ato"
                                                                value="{{ old('oct_dec_ato') ?? $re_9_oct_dec_ato }}"
                                                                placeholder="0.00">
                                                        </td>
                                                        <td class="tg-hqnx">
                                                            <span class="oct_dec_diff"
                                                                id="oct_dec_9_diff">{{ nF2($re_9_oct_dec_diff) }}</span>
                                                            <input type="hidden" name="oct_dec_diff[]"
                                                                id="oct_dec_9_diff_inp"
                                                                value="{{ $re_9_oct_dec_diff }}">
                                                        </td>
                                                        <td class="tg-o5ew">
                                                            {{ $jan_mar_9_gl = abs($jan_mar_1a_gl - $jan_mar_1b_gl) }}
                                                            <input type="hidden" name="jan_mar_gl[]" id="jan_mar_9_gl"
                                                                value="{{ $jan_mar_9_gl }}">
                                                        </td>
                                                        <td class="tg-7169">
                                                            <input type="number" step="any" name="jan_mar_ato[]"
                                                                data-total="9" data-id="jan_mar_9" id="jan_mar_9_ato"
                                                                class="form-control 9_ato"
                                                                value="{{ old('jan_mar_ato') ?? $re_9_jan_mar_ato }}"
                                                                placeholder="0.00">
                                                        </td>
                                                        <td class="tg-thn2">
                                                            <span class="jan_mar_diff"
                                                                id="jan_mar_9_diff">{{ nF2($re_9_jan_mar_diff) }}</span>
                                                            <input type="hidden" name="jan_mar_diff[]"
                                                                id="jan_mar_9_diff_inp"
                                                                value="{{ $re_9_jan_mar_diff }}">
                                                        </td>
                                                        <td class="tg-9459">
                                                            {{ $apr_jun_9_gl = abs($apr_jun_1a_gl - $apr_jun_1b_gl) }}
                                                            <input type="hidden" name="apr_jun_gl[]" id="apr_jun_9_gl"
                                                                value="{{ $apr_jun_9_gl }}">
                                                        </td>
                                                        <td class="tg-q8dw">
                                                            <input type="number" step="any" name="apr_jun_ato[]"
                                                                data-total="9" data-id="apr_jun_9" id="apr_jun_9_ato"
                                                                class="form-control 9_ato"
                                                                value="{{ old('apr_jun_ato') ?? $re_9_apr_jun_ato }}"
                                                                placeholder="0.00">
                                                        </td>
                                                        <td class="tg-darx">
                                                            <span class="apr_jun_diff"
                                                                id="apr_jun_9_diff">{{ nF2($re_9_apr_jun_diff) }}</span>
                                                            <input type="hidden" name="apr_jun_diff[]"
                                                                id="apr_jun_9_diff_inp"
                                                                value="{{ $re_9_apr_jun_diff }}">
                                                        </td>
                                                        <td class="tg-be25">
                                                            {{ $total_9 = abs($total_1a - $total_1b) }}
                                                            <input type="hidden" id="9_total"
                                                                value="{{ $total_9 }}">
                                                        </td>
                                                        <td class="tg-5q6i">
                                                            <span class="9">{{ nF2($re_9_total) }}</span>
                                                        </td>
                                                        <td class="tg-xszb">
                                                            <span class="9_diff">{{ nF2($re_9_total_diff) }}</span>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <input type="hidden" name="is_posted"
                                            value="{{ optional($recons->first())->is_posted ?? 0 }}">
                                        <div class="tax_return mt-5 col-md-5">
                                            @php
                                                $sales_bf = optional($recons_taxes->where('particular', 'Sales before GST')->first());
                                                $sales_bf_bas = abs($sales_bf->bas);
                                                $sales_bf_report = abs($sales_bf->report);
                                                $sales_bf_ato = abs($sales_bf->ato);
                                                $sales_bf_diff = max([$sales_bf_bas, $sales_bf_report, $sales_bf_ato]) - min([$sales_bf_bas, $sales_bf_report, $sales_bf_ato]);
                                                
                                                $sales = optional($recons_taxes->where('particular', 'GST amount in sales')->first());
                                                $sales_bas = abs($sales->bas);
                                                $sales_report = abs($sales->report);
                                                $sales_ato = abs($sales->ato);
                                                $sales_diff = max([$sales_bas, $sales_report, $sales_ato]) - min([$sales_bas, $sales_report, $sales_ato]);
                                                
                                                $sales_af = optional($recons_taxes->where('particular', 'Sales After GST')->first());
                                                $sales_af_bas = abs($sales_af->bas);
                                                $sales_af_report = abs($sales_af->report);
                                                $sales_af_ato = abs($sales_af->ato);
                                                $sales_af_diff = max([$sales_af_bas, $sales_af_report, $sales_af_ato]) - min([$sales_af_bas, $sales_af_report, $sales_af_ato]);
                                                
                                                $exp_bf = optional($recons_taxes->where('particular', 'Expesnes before GST')->first());
                                                $exp_bf_bas = abs($exp_bf->bas);
                                                $exp_bf_report = abs($exp_bf->report);
                                                $exp_bf_ato = abs($exp_bf->ato);
                                                $exp_bf_diff = max([$exp_bf_bas, $exp_bf_report, $exp_bf_ato]) - min([$exp_bf_bas, $exp_bf_report, $exp_bf_ato]);
                                                
                                                $exp = optional($recons_taxes->where('particular', 'GST ON EXPESNSE')->first());
                                                $exp_bas = abs($exp->bas);
                                                $exp_report = abs($exp->report);
                                                $exp_ato = abs($exp->ato);
                                                $exp_diff = max([$exp_bas, $exp_report, $exp_ato]) - min([$exp_bas, $exp_report, $exp_ato]);
                                                
                                                $exp_af = optional($recons_taxes->where('particular', 'Expense after GST')->first());
                                                $exp_af_bas = abs($exp_af->bas);
                                                $exp_af_report = abs($exp_af->report);
                                                $exp_af_ato = abs($exp_af->ato);
                                                $exp_af_diff = max([$exp_af_bas, $exp_af_report, $exp_af_ato]) - min([$exp_af_bas, $exp_af_report, $exp_af_ato]);
                                                
                                                $wages = optional($recons_taxes->where('particular', 'Total wages')->first());
                                                $wages_bas = abs($wages->bas);
                                                $wages_report = abs($wages->report);
                                                $wages_ato = abs($wages->ato);
                                                $wages_diff = max([$wages_bas, $wages_report, $wages_ato]) - min([$wages_bas, $wages_report, $wages_ato]);
                                                
                                                $payg = optional($recons_taxes->where('particular', 'PAYG')->first());
                                                $payg_bas = abs($payg->bas);
                                                $payg_report = abs($payg->report);
                                                $payg_ato = abs($payg->ato);
                                                $payg_diff = max([$payg_bas, $payg_report, $payg_ato]) - min([$payg_bas, $payg_report, $payg_ato]);
                                                
                                            @endphp
                                            <h1 class="text-center">Income Tax Return Reconciliation</h1>
                                            <table class="tax_table">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 230px">Particular</th>
                                                        <th style="width: 110px" class="text-center">P/L amnt. as per bas
                                                        </th>
                                                        <th style="width: 110px" class="text-center">P/L amt as per
                                                            complete
                                                            report</th>
                                                        <th style="width: 110px" class="text-center">P/L as per to ATO
                                                        </th>
                                                        <th style="width: 110px" class="text-center">Diff</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>Sales before GST</td>
                                                        <td>
                                                            <span class="tax_gl_g1">{{ nFA2($total_g1) }}</span>
                                                            <input type="hidden" name="bas[]"
                                                                value="{{ $total_g1 }}">
                                                        </td>
                                                        <td>
                                                            <span class="tax_pl_g1">{{ nFA2($totalCredit) }}</span>
                                                            <input type="hidden" name="report[]"
                                                                value="{{ $totalCredit }}">
                                                        </td>
                                                        <td>
                                                            <span
                                                                class="g1 tax_g1 tax_ato_g1">{{ nF2($sales_bf_ato) }}</span>
                                                            <input type="hidden" name="ato[]"
                                                                value="{{ $sales_bf_ato }}" class="g1">
                                                        </td>
                                                        <td>
                                                            <span class="tax_diff_g1">{{ $sales_bf_diff }}</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>GST amount on Sales</td>
                                                        <td>
                                                            <span class="tax_gl_1a">{{ nFA2($total_1a) }}</span>
                                                            <input type="hidden" name="bas[]"
                                                                value="{{ $total_1a }}">
                                                        </td>
                                                        <td>
                                                            <span class="tax_pl_1a">0.00</span>
                                                            <input type="hidden" name="report[]" value="0.00">
                                                        </td>
                                                        <td>
                                                            <span
                                                                class="1a tax_1a tax_ato_1a">{{ nF2($sales_ato) }}</span>
                                                            <input type="hidden" name="ato[]"
                                                                value="{{ $sales_ato }}" class="1a">
                                                        </td>
                                                        <td>
                                                            <span class="tax_diff_1a">{{ nF2($sales_diff) }}</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Sales after GST</td>
                                                        <td>
                                                            <span
                                                                class="tax_gl_1a_g1">{{ nF2(abs($total_g1) - abs($total_1a)) }}</span>
                                                            <input type="hidden" name="bas[]"
                                                                value="{{ abs($total_g1) - abs($total_1a) }}">
                                                        </td>
                                                        <td>
                                                            <span class="tax_pl_1a_g1">{{ nFA2($totalIncome) }}</span>
                                                            <input type="hidden" name="report[]"
                                                                value="{{ $totalIncome }}">
                                                        </td>
                                                        <td>
                                                            <span
                                                                class="1a_g1 tax_1a_g1 tax_ato_1a_g1">{{ nF2($sales_af_ato) }}</span>
                                                            <input type="hidden" name="ato[]"
                                                                value="{{ $sales_af_ato }}" class="1a_g1">
                                                        </td>
                                                        <td>
                                                            <span class="tax_diff_1a_g1">{{ nF2($sales_af_diff) }}</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Expense before GST</td>
                                                        <td>
                                                            <span class="tax_gl_g11">{{ nFA2($total_g11) }}</span>
                                                            <input type="hidden" name="bas[]"
                                                                value="{{ $total_g11 }}">
                                                        </td>
                                                        <td>
                                                            <span class="tax_pl_g11">{{ nFA2($totalDebit) }}</span>
                                                            <input type="hidden" name="report[]"
                                                                value="{{ $totalDebit }}">
                                                        </td>
                                                        <td>
                                                            <span
                                                                class="g11 tax_g11 tax_ato_g11">{{ nF2($exp_bf_ato) }}</span>
                                                            <input type="hidden" name="ato[]"
                                                                value="{{ $exp_bf_ato }}" class="g11">
                                                        </td>
                                                        <td>
                                                            <span class="tax_diff_g11">{{ nF2($exp_bf_diff) }}</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td> GST on expense</td>
                                                        <td>
                                                            <span class="tax_gl_1b">{{ nFA2($total_1b) }}</span>
                                                            <input type="hidden" name="bas[]"
                                                                value="{{ $total_1b }}">
                                                        </td>
                                                        <td>
                                                            <span class="tax_pl_1b">0.00</span>
                                                            <input type="hidden" name="report[]" value="0.00">
                                                        </td>
                                                        <td>
                                                            <span class="1b tax_1b tax_ato_1b">{{ nF2($exp_ato) }}</span>
                                                            <input type="hidden" name="ato[]"
                                                                value="{{ $exp_ato }}" class="1b">
                                                        </td>
                                                        <td>
                                                            <span class="tax_diff_1b">{{ nF2($exp_diff) }}</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Expense after GST+Wages</td>
                                                        <td>
                                                            <span
                                                                class="tax_gl_g11_1b">{{ nF2(abs($total_g11) - abs($total_1b) + abs($total_w1)) }}</span>
                                                            <input type="hidden" name="bas[]"
                                                                value="{{ abs($total_g11) - abs($total_1b) + abs($total_w1) }}">
                                                        </td>
                                                        <td>
                                                            <span class="tax_pl_g11_1b">{{ nFA2($totalExpense) }}</span>
                                                            <input type="hidden" name="report[]"
                                                                value="{{ $totalExpense }}">
                                                        </td>
                                                        <td>
                                                            <span
                                                                class="g11_1b tax_g11_1b tax_ato_g11_1b">{{ nF2($exp_af_ato) }}</span>
                                                            <input type="hidden" name="ato[]"
                                                                value="{{ $exp_af_ato }}" class="g11_1b">
                                                        </td>
                                                        <td>
                                                            <span class="tax_diff_g11_1b">{{ nF2($exp_af_diff) }}</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Total wages</td>
                                                        <td>
                                                            <span class="tax_gl_w1">{{ nFA2($total_w1) }}</span>
                                                            <input type="hidden" name="bas[]"
                                                                value="{{ $total_w1 }}">
                                                        </td>
                                                        <td>
                                                            <span class="tax_pl_w1">0.00</span>
                                                            <input type="hidden" name="report[]" value="0.00">
                                                        </td>
                                                        <td>
                                                            <span
                                                                class="w1 tax_w1 tax_ato_w1">{{ nF2($wages_ato) }}</span>
                                                            <input type="hidden" name="ato[]"
                                                                value="{{ $wages_ato }}" class="w1">
                                                        </td>
                                                        <td>
                                                            <span class="tax_diff_w1">{{ nF2($wages_diff) }}</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>PAYG</td>
                                                        <td>
                                                            <span class="tax_gl_w2">{{ nFA2($total_w2) }}</span>
                                                            <input type="hidden" name="bas[]"
                                                                value="{{ $total_w2 }}">
                                                        </td>
                                                        <td>
                                                            <span class="tax_pl_w2">0.00</span>
                                                            <input type="hidden" name="report[]" value="0.00">
                                                        </td>
                                                        <td>
                                                            <span
                                                                class="w2 tax_w2 tax_ato_w2">{{ nF2($payg_ato) }}</span>
                                                            <input type="hidden" name="ato[]"
                                                                value="{{ $payg_ato }}" class="w2">
                                                        </td>
                                                        <td>
                                                            <span class="tax_diff_w2">{{ nF2($payg_diff) }}</span>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="col-md-6" style="padding-top: 220px">
                                            {{-- <input type="submit" name="type" value="" class="btn" > --}}
                                            <!-- Button trigger modal -->
                                            <button type="button" class="btn"
                                                style="background: rgb(42, 0, 158) !important; border-color: rgb(42, 0, 158) !important;"data-toggle="modal"
                                                data-target="#reconNote">Notes
                                            </button>
                                            <input type="submit" name="type" value="Print/PDF" class="btn"
                                                style="background: rgb(212, 0, 255) !important; border-color: rgb(212, 0, 255) !important;">
                                            <input type="submit" name="type" value="Export to excel"
                                                class="btn"
                                                style="background: blue !important; border-color: blue !important">
                                            @if (optional($recons->first())->is_posted == 0)
                                                @canany('admin.gst-reconciliation-for-tr.save')
                                                    <input type="submit" name="type" value="Save" class="btn"
                                                        style="background: green !important; border-color: green !important">
                                                @endcanany
                                                @canany('admin.gst-reconciliation-for-tr.post')
                                                    <input type="submit" name="type" value="Post"
                                                        class="btn btn-warning">
                                                @endcanany
                                            @endif
                                            <!-- Button trigger modal -->
                                            <button type="button" class="btn btn-danger" data-toggle="modal"
                                                data-target="#permisiion">
                                                Edit With Permission
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.page-content -->
        </div>

        <!-- Modal -->
        <div class="modal fade" id="permisiion" tabindex="-1" role="dialog" aria-labelledby="permisiionLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="permisiionLabel">Enter Admin Password</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('gst_recon.permission', [$client->id, $profession->id, $period->id]) }}"
                        method="post">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="password">Admin Password</label>
                                <input type="password" name="password" id="password" class="form-control">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Add Note -->
        <div class="modal fade" id="reconNote" tabindex="-1" role="dialog" aria-labelledby="reconNoteLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="reconNoteLabel">Add Reconciliation Note</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('gst_recon.save_note', [$client->id, $profession->id]) }}" method="post"
                        id="saveNote">
                        @csrf
                        <input type="hidden" name="model" value="reconcilation">
                        <input type="hidden" name="year" value="{{ $year }}">
                        <div class="modal-body">
                            <textarea name="content" id="note" cols="30" rows="10" class="form-control summernote"
                                placeholder="Add your notes">{{ optional($note)->content }}</textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" id="saveNoteBtn" class="btn btn-primary">Save Note</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div><!-- /.main-content -->
    @include('admin.reports.gst_recon.js')
@endsection
