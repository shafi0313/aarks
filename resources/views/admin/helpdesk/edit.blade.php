<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Update Help Desk</h5>
            </div>
            <form action="{{ route('helpdesk.update', $helpdesk->id) }}" method="post" autocomplete="off" enctype="multipart/form-data">
                @csrf @method('put')
                <input type="hidden" name="type" value="1">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Menu Name</label>
                        <input type="text" required class="form-control" id="name"
                            name="name" value="{{$helpdesk->name}}" placeholder="Ex: Sales">
                    </div>
                    @if ($helpdesk->parent_id != 0)
                    <div class="form-group">
                        <div class="form-group">
                            <label for="parent_id">Category/Page</label>
                            <select name="parent_id" required class="form-control" id="parent_id">
                                <option value="0">As Parent</option>
                                @foreach($desks as $dsk)
                                <option value="{{ $dsk->id }}" {{$dsk->id == $helpdesk->parent_id ? 'selected':''}} >{{ $dsk->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Enter Topics</label>
                        <input type="text" required class="form-control" id="title" name="title"
                            value="{{$helpdesk->title}}" placeholder="Enter Topics">
                    </div>

                    <div class="form-group">
                        <label>Details</label>
                        <textarea id="editnote" placeholder="Enter details" class="form-control" id="description"
                            name="description" placeholder="Enter details">{!!$helpdesk->description!!}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="thumbnail">Thumbnail</label>
                        <input type="file" name="thumbnail" id="thumbnail" class="form-control" accept="image/*">
                    </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#editnote').summernote();
    });
</script>
