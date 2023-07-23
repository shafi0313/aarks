@php
$code = \App\Models\ClientAccountCode::whereClientId($client->id)->where('code', 999999)->first();
$l = $l+1;
@endphp
<tr class="tax">
    <input type="hidden" name="entries[{{$code->code}}][account_code_id]" value="{{$code->id}}">
    <input type="hidden" name="entries[{{$code->code}}][chart_id]" value="{{$code->code}}">
    <td style="text-align: center" id="A-{{$l}}"></td>
    <td id="B-{{$l}}">Income tax and other tax: </td>

    <td style="text-align: right" id="C-{{$l}}">
        <input type="text" name="entries[{{$code->code}}][last_amount]"  id="last_year_amount-{{$l}}"
            class="last_year_tax text-right" value="{{isset($last_year_amount)?$last_year_amount:0}}">
    </td>
    <td id="D-{{$l}}">
        <input value="0" id="old_tax_percent-{{$l}}" class="form-control text-right last_year_percent" type="text"
            name="entries[{{$code->code}}][last_percent]">
    </td>
    @php
    $a = 'D';
    @endphp
    @foreach ($months as $month)
    <td id="{{++$a}}-{{$l}}">
        <input type="text" value="0" name="entries[{{$code->code}}][{{$month}}][new_percent]"
            class="form-control text-right  month_percent month_percent-{{$l}}">
    </td>
    <td style="text-align: right" id="{{++$a}}-{{$l}}" class="tax">
        <span class="tax_amount">{{number_format(0,2)}}</span>
        <input type="hidden" value="0" name="entries[{{$code->code}}][{{$month}}][new_amount]"
            class="tax_amount _tax-{{$l}}">
    </td>
    @endforeach
    <td class="total_tax" id="total-{{$l}}">
        <b class="total_tax">{{number_format(0,2)}}</b>
        <input type="hidden" value="0" name="total_tax[]" class="total_tax">
    </td>
</tr>
