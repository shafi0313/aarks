@if ($type == 'asset-name-delete')
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
                    <div>
                        <strong class="datamgs2" style="color:red;">
                            @if ($errors->any())
                            {{$errors->first()}}
                            @endif
                        </strong>
                    </div>
                    <h1 class="text-dark">Enter Password</h1>
                    <input type="password" class="form-control" name="password" id="deprPass" />
                    @csrf
                    @method('delete')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger btnOff">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@if ($type =='asset-name-update')
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
@endif
@if ($type == 'asset-group-update')
<!-- asset group edit  start-->
<div id="update-modal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Update Asset Group</h4>
            </div>
            <form id="asset_groupedit" class="updateFrom">
                <input type="hidden" name="id" id="updateId">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Group Asset Name</label>
                        <input type="text" class="form-control" name="groupasset_name" id="asset_group_editname"
                            required autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Depreciation Account</label>
                        <select class="form-control" id="dep_edit_id" name="deprecicatinaccount">
                            <option>-SELECT ONE-</option>
                            @foreach ($dep_accs as $dep_acc)
                            <option value="{{$dep_acc->id}}">{{$dep_acc->name}} </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="exampleInputPassword1">Accmulataed Depreciation Account</label>
                        <select class="form-control" id="accum_dep_edit_id" name="accmulatedaccount">
                            <option>-SELECT ONE-</option>
                            @foreach ($accm_dep_accs as $accm_dep_acc)
                            <option value="{{$accm_dep_acc->id}}">{{$accm_dep_acc->name}}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@if ($type == 'asset-group-delete')
<!-- Delete Modal -->
<form id="myModal222" action="" method="POST">
    @csrf
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog" style="width: 400px">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="bootbox-close-button close" data-dismiss="modal"
                        aria-hidden="true">×</button>
                    <h4 class="modal-title">Delete</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="passdord">Password</label>
                        <input type="passdord" class="form-control" id="passdord">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"
                        aria-hidden="true">Cancel</button>
                    <button type="Submit" class="btn btn-success btn-sm ">Submit</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endif
@if ($type == 'ajax-modal')
<div class="ajax_modal"></div>
@endif
