<tr>
    <td colspan="14">
        <b class="text-success">{{ $type }}</b>
    </td>
</tr>
@php
    $total_budget = 0;
    $tb = array_fill(0, 13, 0);
@endphp
@foreach ($items as $j => $item)
    @php
        $code = $item->chart;
    @endphp
    <tr>
        {{-- <td>{{$code->code}}</td> --}}
        <td>{{ $code->name }}</td>
        @php
            $total_ = 0;
        @endphp
        @foreach (json_decode($item->months) as $name => $month)
            @php
                $mnt = json_decode($month);
                $new_amount = $mnt->new_amount;
                $total_ += $new_amount;

                $tb[$loop->iteration] += $new_amount;
            @endphp
            <td class="text-right">{{ $new_amount }}</td>
        @endforeach
        @php
            $total_budget += $new_amount;
            // info($tb);
        @endphp
        <td class="total_ text-right">
            <b class="total_">{{ number_format($total_, 2) }}</b>
        </td>
    </tr>
@endforeach
<tr class="text-right">
    <td>
        <b>Total {{ $type }}</b>
    </td>
    @foreach ($months as $month)
        <td>
            {{-- <b class="total_budget">{{number_format($total_budget,2)}}</b> --}}
            <b class="total_budget">{{ number_format($tb[$loop->iteration], 2) }}</b>
            <input type="hidden" value="{{ $tb[$loop->iteration] }}" id="{{ Str::slug($type) }}-{{ $loop->iteration }}">
        </td>
    @endforeach
    <td></td>
</tr>
