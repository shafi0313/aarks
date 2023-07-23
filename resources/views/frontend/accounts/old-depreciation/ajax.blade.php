@if ($type == 'asset_name')
@foreach ($inputs as $inp)
@php($input = $inp->first())
<tr id="input_row_{{$input->id}}">
    <td>{{$input->asset_name }}</td>
    <td>{{optional($input->purchase_date)->format('d/m/Y') }}</td>
    <td>{{optional($input->disposal_date)->format('d/m/Y') }}</td>
    <td style="text-align:center">
        <button data-id="{{$input->id}}" class="_edit red btn btn-info btn-sm"
            data-route="{{route('depreciation.name.update', $input->id)}}">
            <i class="fa fa-pencil-square-o fa-lg" aria-hidden="true"></i>
        </button>
        @if ($input->rollover_year == null && $input->disposal_date == null)
        <button  title="Depriciation Delete" class="red btn btn-danger btn-sm ml-3 _delete" data-route="{{route('client.dep_name.destroy', $input->id)}}">
            <i class="fa fa-trash" aria-hidden="true"></i>
        </button>
        @endif
    </td>
</tr>
@endforeach
@endif

@if ($type == 'active_asset_name')
<select class="form-control" id="dep_asset_name" name="dep_asset_name" required>
    <option>--Plase Select Asset Name--</option>
    @foreach ($active_assets as $active)
    <option value="{{$active->id}}">{{$active->asset_name}}</option>
    @endforeach
</select>
@endif

@if ($type == 'old_asset_name')
<select class="form-control" id="dep_asset_name" name="dep_asset_name" required>
    <option>--Plase Select Asset Name--</option>
    @foreach ($old_assets as $old)
    <option value="{{$old->id}}">{{$old->asset_name}}</option>
    @endforeach
</select>
@endif

@if ($type == 'error')
<tr>
    <td colspan="4">
        <h1 class="text-center">Table Empty.....</h1>
    </td>
</tr>
@endif
