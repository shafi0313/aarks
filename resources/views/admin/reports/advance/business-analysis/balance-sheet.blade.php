<div class="table-responsive">
    <table class="table table-bordered">
        <tbody>
            <tr>
                <td colspan="100%">
                    <h3>Assets</h3>
                </td>
            </tr>
            <tr>
                <td colspan="100%" class="text-success">
                    <b>Current Assets</b>
                </td>
            </tr>
            @php
                $total_current = 0;
            @endphp
            @foreach ($current as $crnt)
                @php
                    $code = $crnt->chart;
                @endphp
                <tr>
                    <td>{{ $code->name }}</td>

                    @php
                        $total_c = 0;
                    @endphp
                    @foreach (json_decode($crnt->months) as $name => $month)
                        @php
                            info($crnt->months);
                            $mnt = json_decode($month);
                            $new_amount = $mnt->new_amount;
                            $total_current += $new_amount;
                            $total_c += $new_amount;
                        @endphp
                    @endforeach
                    <td class="text-right">{{ abs_number($total_c) }}</td>
                </tr>
            @endforeach
            <tr>
                <td><b>Total Current Assets</b></td>
                <td class="text-right"><b>{{ abs_number($total_current) }}</b></td>
            </tr>
            <tr>
                <td colspan="100%" class="text-success">
                    <b>Fixed Assets</b>
                </td>
            </tr>
            @php
                $total_fix = 0;
            @endphp
            @foreach ($fixed as $fix)
                @php
                    $code = $fix->chart;
                @endphp
                <tr>
                    <td>{{ $code->name }}</td>
                    @php
                        $total_f = 0;
                    @endphp
                    @foreach (json_decode($crnt->months) as $name => $month)
                        @php
                            $mnt = json_decode($month);
                            $new_amount = $mnt->new_amount;
                            $total_fix += $new_amount;
                            $total_f += $new_amount;
                        @endphp
                    @endforeach
                    <td class="text-right">{{ abs_number($total_f) }}</td>
                </tr>
            @endforeach
            <tr>
                <td><b>Total Fixed Assets</b></td>
                <td class="text-right"><b>{{ abs_number($total_fix) }}</b></td>
            </tr>
            <tr>
                <td>
                    <h4>Total Assets</h4>
                </td>
                <td class="text-right"><b>{{ abs_number($total_current + $total_fix) }}</b></td>
            </tr>
        </tbody>
    </table>
</div>
