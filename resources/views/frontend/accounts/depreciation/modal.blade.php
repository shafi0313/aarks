<!--Delete Asset Name-->
<div class="modal fade" id="delete-modal" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form enctype="multipart/form-data" method="post" action="" class="assetDeleteForm">
                <div class="modal-body">
                    <div><strong class="datamgs2" style="color:red;"></strong></div>
                    <h1 class="modal-title">Enter Password</h1>
                    <input type="hidden" class="form-control" name="asset_name_id" id="depID" />
                    <input type="password" class="form-control" name="password" id="deprPass" />
                    @csrf
                    @method('delete')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btnOff">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--Update Asset Name Modal -->
<div class="modal fade" id="update-modal" tabindex="-1" role="dialog" aria-labelledby="updateLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form enctype="multipart/form-data" class="form">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-center" id="updateLabel">Update Asset Name</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input type="hidden" name="id" id="updateId">
                        <div class="status text-center text-uppercase"></div>
                    </div>
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" class="form-control" id="up_asset_name" aria-describedby="up_asset_name"
                            name="up_asset_name">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="ajax_modal"></div>
