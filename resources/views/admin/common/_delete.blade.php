<div class="modal fade" id="common-delete-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Password</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" id="_delete_form">
                    @csrf @method('delete')
                    <input type="hidden" name="_method" value="DELETE">
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Password:</label>
                        <input type="password" name="password" class="form-control">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


@push('custom_scripts')
    <script>
        $(document).ready(function () {
            $('._delete')
                // .addClass('fa fa-trash-o bigger-130 red')
                .css({
                    "cursor" : "pointer"
                }).on('click',function () {
                $('#_delete_form').attr('action', $(this).data('route'));
                $('#common-delete-modal').modal('show');
            });
        });
    </script>
@endpush
