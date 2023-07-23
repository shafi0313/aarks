<form id="addAssetName" class="form-horizontal">
    <input type="hidden" name="client_id" id="client_id" value="{{$depreciation->client_id}}" />
    <input type="hidden" name="profession_id" id="profession_id" value="{{$depreciation->profession_id}}" />
    <div class="form-group">
        <label for="subname" class="control-label">Selected Asset Group
            : <b style="color:green;">{{$depreciation->asset_group}}</b>
        </label>
    </div>
    <div class="form-group">
        <label for="subname" class="control-label">Asset Name</label>
        <input type="text" class="form-control focus" id="subname" name="subname" placeholder="Asset Name" autocomplete="off" required>
    </div>
    <div class="form-group">
        <button type="submit" class="btn btn-primary btn-lg">Save</button>
        <a href="{{route('client.dep_group.index',[$depreciation->client_id,$depreciation->profession_id])}}"
            class="btn btn-warning btn-lg">Back</a>
    </div>
    <br>

</form>

<div class="col-md-12">
    <div><strong class="delegemgs" style="color:red;"></strong></div>
    <table class="table  table-bordered table-hover">
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
