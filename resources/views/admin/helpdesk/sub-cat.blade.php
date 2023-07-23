@forelse ($subcategories as $i => $sub)
<tr class="sub-desk-{{$sub->parent_id}}">
    <td class="text-right">{{ numberToRoman($i+1)}}</td>
    <td> <span style="padding-left: 20px"></span>{{$sub->name}}</td>
    <td>{{$sub->title}}</td>
    <td class="text-center">
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
            data-target="#info{{$sub->id}}">
            <i class="fa fa-eye"></i>
        </button>

        <!-- Modal -->
        <div class="modal fade" id="info{{$sub->id}}" tabindex="-1" role="dialog"
            aria-labelledby="info{{$sub->id}}Label" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title text-info" id="info{{$sub->id}}Label">
                            {{$sub->name??$sub->title}}</h3>
                    </div>
                    <div class="modal-body">
                        {!!$sub->description!!}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </td>
    <td class="text-center">
        <span data-route="{{route('helpdesk.edit', $sub->id)}}" class="btn btn-info btn-sm"
            onclick="editHelp($(this).data('route'))">
            <i class="fa fa-edit"></i>
        </span>
        <form style="display: inline-block;" action="{{route('helpdesk.destroy', $sub->id)}}" method="post">
            @csrf @method('delete')
            <button onclick="return confirm('Are you sure?')" type="submit" class="btn btn-danger btn-sm">
                <i class="fa fa-trash"></i></button>
        </form>
    </td>
</tr>
@empty
<tr class="sub-desk-{{$id}}">
    <td colspan="100%">
        <h3 class="text-center text-danger">No Data Found</h3>
    </td>
</tr>
@endforelse
