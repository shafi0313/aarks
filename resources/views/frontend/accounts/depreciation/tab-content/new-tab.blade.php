<form class="form-horizontal" id="addAssetName">
    <input type="hidden" name="client_id" id="client_id" value="{{$dep->client_id}}" />
    <input type="hidden" name="profession_id" id="profession_id" value="{{$dep->profession_id}}" />
    <div class="form-group">
        <label for="subname" class="col-sm-3 control-label">Selected Asset Group
            :</label>
        <div class="col-sm-9" style="padding-top: 5px; font-size: 15px;">
            <b style="color:green;">{{$dep->asset_group}}</b>
        </div>
    </div>
    <div class="form-group">
        <label for="subname" class="col-sm-3 control-label">Asset Name</label>
        <div class="col-sm-9">
            <input type="text" class="form-control focus" id="subname" name="subname" placeholder="Asset Name"
                autocomplete="off" required>
        </div>
    </div>
    <div class="form-group pull-right">
        <a href="{{route('client.dep_group.index',[$dep->client_id,$dep->profession_id])}}"
            class="btn btn-success btn-sm">Home</a>
        <button type="submit" class="btn btn-primary btn-sm" id="assetNameSave">Save</button>
        <button type="reset" class="btn btn-primary btn-sm">Cancel</button>
        <button type="reset" class="btn btn-primary btn-sm">Close</button>
    </div>
    <br>

</form>

<div class="col-md-12">
    <div><strong class="delegemgs" style="color:red;"></strong></div>
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>Asset Group Name : Asset Group</th>
                <th>Purchase Date</th>
                <th>Disposal Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="readAssetName">
        </tbody>
    </table>
</div>
