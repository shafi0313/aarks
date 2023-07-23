<div class="table-responsive">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th class="text-center" rowspan="2" colspan="4">Account Code</th>
                {{-- <th class="text-center" rowspan="2">Account Name</th> --}}
                <th class="text-center" colspan="2">Last Year</th>
                @foreach ($months as $month)
                <th class="text-center" colspan="2">{{$month}} </th>
                <input type="hidden" name="entries[month][]" value="{{$month}}">
                @endforeach
                <th rowspan="2">Total</th>
            </tr>
            <tr>
                <td class="text-center">Amount</td>
                <td class="text-center">Percent</td>
                @foreach ($months as $month)
                <td class="text-center">Percent</td>
                <td class="text-center">Amount</td>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @php
                $l    = 0;
                $type = 'sales';
            @endphp
            @foreach ($accountCategories as $accountCategory)
            @php
                $mainCode = $accountCategory->where('category_id', '=', $accountCategory->id);
            @endphp
            <tr>
                <td colspan="100%" style="color:red">{{$accountCategory->name}}</td>
                {{-- <td></td> --}}
            </tr>
            @foreach ($accountCategory->subCategoryWithoutAdditional->sortBy('code') as $subCategory)
            @php
                $subCode = $subCategory->where('sub_category_id', '=', $subCategory->id);
            @endphp
            <tr>
                <td></td>
                <td colspan="100%" style="color:green">{{$subCategory->name}}</td>
                {{-- <td></td> --}}
            </tr>
            @endforeach
            @foreach ($subCategory->additionalCategory->sortBy('code') as $additionalCategory)
            @php
                $additionalCode = $subCategory->where('additional_category_id', '=', $additionalCategory->id);
            @endphp
            <tr>
                <td></td>
                <td></td>
                <td colspan="100%" style="color:violet">{{$additionalCategory->name}}</td>
                {{-- <td></td> --}}
            </tr>
            @foreach ($codes as $code)
            @if ($code->additional_category_id == $additionalCategory->id)

            @php
                $l++
            @endphp
            {{-- <tr>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="100%" style="color:#1B6AAA">
                    {{$code->name}}
                </td>
            </tr> --}}

        <input type="hidden" name="entries[account_code_id][]" value="{{$code->id}}">
        <input type="hidden" name="entries[chart_id][]" value="{{$code->code}}">

        <tr class="{{substr($code->code, -6, 1) == 1 ? 'with_sales':'without_sales'}}">
                <td></td>
                <td></td>
                <td></td>
            <td style="text-align: center" id="A-{{$l}}">{{$code->code}} </</td>
            <td id="B-{{$l}}">{{$code->name}}</td>
            <td style="text-align: right"  id="C-{{$l}}">
                <input type="text" id="last_year_amount-{{$l}}" name="entries[last_year_amount][]" class="last_year_with_sales last_year_amount text-right" value="0">
            </td>
            <td id="D-{{$l}}">
                <input value="0" id="old_percent-{{$l}}" class="form-control text-right last_year_percent" type="text" name="entries[old_percent][]">
            </td>
            @php
                $a = 'D';
            @endphp
            @foreach ($months as $month)
            <td id="{{++$a}}-{{$l}}">
                        {{-- {{$a}}{{$l}} --}}
                <input type="text" value="0" name="entries[percent][]" class="with_sales_percent form-control text-right month_percent-{{$l}}">
            </td>
            <td style="text-align: right" id="{{++$a}}-{{$l}}" class="{{$type}}">
                {{-- {{$a}}{{$l}} --}}
                <span class="budget_amount">{{number_format(0,2)}}</span>
                <input type="hidden" value="0" name="entries[budget_amount][]" class="budget_amount _amount-{{$l}}">
            </td>
            @endforeach
            <td class="total_" id="total-{{$l}}">
                <b class="total_">{{number_format(0,2)}}</b>
                <input type="hidden" value="0" name="entries[total_][]" class="total_">
            </td>
        </tr>
            @endif
            @endforeach
            @endforeach
            @endforeach
        </tbody>
    </table>
</div>
