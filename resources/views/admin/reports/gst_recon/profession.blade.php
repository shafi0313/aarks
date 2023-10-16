@if ($get == 'profession')
    <label for="select-client">
        <h2>Select Profession</h2>
    </label>
    <select class="form-control" id="profession_id" name="profession_id" tabindex="7"
        onchange="getPeriod($(this).find(':selected').data('client'), $(this).find(':selected').data('profession'))">
        <option>Select Business Activity Report</option>
        @foreach ($professions as $profession)
            <option value="{{ $client->id . '/' . $profession->id }}" data-client="{{ $client->id }}"
                data-profession="{{ $profession->id }}"> {{ $profession->name }} </option>
        @endforeach
    </select>
@endif
@if ($get == 'period')
    <label for="select-financial">
        <h2>Select Financial Year</h2>
    </label>
    <select class="form-control" id="period_id" name="period_id" tabindex="7" onchange="location = this.value">
        <option>Select Financial Year</option>
        @foreach ($periods as $period)
            <option value="{{ route('gst_recon.report', [$client->id, $profession->id, $period->id]) }}">
                {{ $period->year }}
            </option>
        @endforeach
    </select>
@endif
@if ($get == 'codes')
    <label for="select-financial">
        <h2>Select Account Code</h2>
    </label>
    <select class="form-control" id="code" name="code" tabindex="7">
        <option>Select Account Code</option>
        @php
            $c = '';
        @endphp
        @foreach ($codes as $code)
            @if ($c != $code->code)
                <option value="{{ $code->code }}"> {{ $code->name }} - {{ $code->code }} </option>
            @endif
            @php
                $c = $code->code;
            @endphp
        @endforeach
    </select>
@endif
