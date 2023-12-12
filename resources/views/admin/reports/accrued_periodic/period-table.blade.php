<style>
    .col-md-4 {
        padding-right: 0
    }
</style>
<div class="row">
    @foreach ($periods as $period)
        <div class="col-md-3">
            <strong class="pull-right" style="color:#00CC66; padding-right:10px;">
                <u>
                    {{ bdDate($period->start_date) }}
                    to
                    {{ bdDate($period->end_date) }}
                </u>
            </strong>
            <table class="table table-bordered table-hover table-striped">
                <tr>
                    {{-- <th style="color: blue">Code</th> --}}
                    <th>Account Name</th>
                    <th>Gross</th>
                    <th>GST</th>
                </tr>
                @if (isset($accrueds[$period->id]))
                    @php
                        $arr_accrueds = $accrueds[$period->id]
                            ->where('trn_date', '>=', $period->start_date->format('Y-m-d'))
                            ->where('trn_date', '<=', $period->end_date->format('Y-m-d'))
                            ->groupBy('chart_code');
                        $code = '';
                    @endphp
                    @foreach ($arr_accrueds as $accrued)
                        @php
                            $accrued_first = $accrued->first();
                        @endphp
                        @if ($period->id == $accrued_first->period_id)
                            <tr>
                                {{-- <td style="color: blue; font-size: 10px">{{ $accrued_first->accountCodes->code }}</td> --}}
                                <td>{{ $accrued_first->accountCodes->name }}</td>
                                <td style="padding-right:0px; padding-left:0px; text-align:right;">
                                    {{ number_format($accrued->sum('gross_amount'), 2) }}
                                </td>
                                <td style="padding-right:0px; padding-left:0px; text-align:right;">
                                    {{ number_format($accrued->sum('gst_cash_amount'), 2) }}
                                </td>
                            </tr>
                        @endif
                    @endforeach
                @endif
            </table>
        </div>
    @endforeach
</div>
