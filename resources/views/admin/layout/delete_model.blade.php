<div id="default-delete-modal" class="modal fade" tabindex="-1" role="dialog">
    <form action="" method="POST" id="default-delete-form">
        <input type="hidden" name="_method" value="DELETE">
        @csrf
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">Delete</h4>
                </div>
                <div class="modal-body">
                    <h3>Are you sure want to delete this?</h3>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger modal_delete_link">Delete</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </form>
</div><!-- /.modal -->
