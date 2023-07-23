<div class="reportH">
    <div class="row pt-4">
    <div class="col-lg-12 text-center" style="padding:15px 0">
        <h3 class="company_name">{{$client->fullname}}</h3>
        <h5 class="report_name">Depreciation Report</h5>
        <h5 style="border-bottom:1px solid black;display:inline-block; padding-bottom:2px;margin:0">
            Depreciation Schedule for the year ended 30 June, {{ $data['year'] }}
        </h5>
    </div>
        <div class="col-md-12">
            <div class="" align="center">
                <table class="table table-hover table-bordered table-sm" style="width:100%">
                    <thead>
                        <tr>
                            <th style="text-align:center" rowspan="2">Asset Name</th>
                            <th style="text-align:center" colspan="3">Cost</th>
                            <th style="text-align:center" colspan="3">ADDITION</th>
                            <th style="text-align:center" colspan="2">DISPOSAL</th>
                            <th style="text-align:center" rowspan="2">M</th>
                            <th style="text-align:center" colspan="3">DEPRECIATION</th>

                            <th style="text-align:center" colspan="2">PROFIT</th>
                            <th style="text-align:center" colspan="2">LOSS</th>
                        </tr>


                        <tr>
                            <td style="text-align:center">P.Date</td>
                            <td style="text-align:center">Total</td>
                            <td style="text-align:center">OWDV</td>

                            <td style="text-align:center">Date</td>
                            <td style="text-align:center">Rate</td>
                            <td style="text-align:center">Value</td>

                            <td style="text-align:center">Date</td>
                            <td style="text-align:center">Consid</td>
                            <td style="text-align:center">Rate</td>
                            <td style="text-align:center">Deprec</td>
                            <td style="text-align:center">CWDV</td>
                            {{-- <td style="text-align:center">Upto +</td> --}}
                            <td style="text-align:center">Above</td>
                            <td style="text-align:center">Total</td>
                            <td style="text-align:center">Priv</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data['depreciations'] as $item)
                            <tr>
                                <td colspan="17">
                                    <strong class="dep_gropu text-success">{{ $item->asset_group }}</strong> <br>
                                </td>
                            </tr>
                            @php
                                $depAssetNames = $item->asset_names->where('year', $data['year']);
                            @endphp
                            {{-- Current Year Report --}}
                            @foreach ($depAssetNames as $asset_name)
                                @if ($asset_name->owdv_value_date != null)
                                    @php
                                        $purch_date1 = $asset_name->purchase_date->format('d/m/Y');
                                        $purch_date2 = $asset_name->owdv_value_date->format('Y-m-d');

                                        if (in_array($asset_name->purchase_date->format('m'), range(1, 6))) {
                                            $year = $asset_name->purchase_date->format('Y');
                                        } else {
                                            $year = $asset_name->purchase_date->format('Y') + 1;
                                        }
                                    @endphp
                                    {{-- @if ($purch_date2 <= $year . '-06-30') --}}
                                    <tr>
                                        <td style="text-align:right">
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            {{ $asset_name->asset_name }}
                                        </td>
                                        <td style="text-align:center">{{ $purch_date1 }}</td>
                                        <td style="text-align:center">{{ number_format($asset_name->total_value, 2) }}
                                        </td>
                                        <td style="text-align:center">{{ number_format($asset_name->owdv_value, 2) }}
                                        </td>
                                        @if ($asset_name->year == $year)
                                            <td style="text-align:center">{{ $purch_date1 }}</td>
                                            <td style="text-align:center">
                                                {{ number_format($asset_name->purchase_rate, 2) }}
                                            </td>
                                            <td style="text-align:center">
                                                {{ number_format($asset_name->purchase_value, 2) }}</td>
                                        @else
                                            <td style="text-align:center">0</td>
                                            <td style="text-align:center">0</td>
                                            <td style="text-align:center">0</td>
                                        @endif
                                        <td style="text-align:center">
                                            {{ optional($asset_name->disposal_date)->format('d/m/Y') }}</td>
                                        <td style="text-align:center">
                                            {{ number_format($asset_name->disposal_value, 2) }}
                                        </td>
                                        <td style="text-align:center">{{ $asset_name->dep_method }}</td>
                                        <td style="text-align:center">{{ number_format($asset_name->dep_rate, 2) }}
                                        </td>
                                        <td style="text-align:center">{{ number_format($asset_name->dep_amt, 2) }}
                                        </td>
                                        <td style="text-align:center">{{ number_format($asset_name->cwdv_value, 2) }}
                                        </td>
                                        <td style="text-align:center">
                                            {{ number_format($asset_name->profit ?? 0, 2) }}</td>
                                        <td style="text-align:center">{{ number_format($asset_name->loss ?? 0, 2) }}
                                        </td>
                                        <td style="text-align:center">{{ number_format($asset_name->loss ?? 0, 2) }}
                                        </td>
                                        {{-- <td style="text-align:center">{{$asset_name->id}}</td> --}}
                                    </tr>
                                    {{-- @endif --}}
                                @endif
                            @endforeach
                        @endforeach
                    </tbody>
                    <tbody>
                </table>
            </div>
        </div>
    </div>
</div>
