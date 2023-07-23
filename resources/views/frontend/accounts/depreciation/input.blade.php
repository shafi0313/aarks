@extends('frontend.layout.master')
@section('title','Input')
@section('content')
<?php $p="cdep"; $mp="acccounts"?>

<!-- PAGE CONTENT BEGINS -->
<div class="row justify-content-center">
    <div class="col-lg-8">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active nav-item">
                <a  class="nav-link active" href="#home" aria-controls="home" role="tab" data-toggle="tab">New
                    Group</a>
            </li>
            <li role="presentation" class="nav-item">
                <a class="nav-link" href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Edit/Delete Group</a>
            </li>
            <li role="presentation" class="nav-item">
                <a class="nav-link" href="#settings" aria-controls="settings" role="tab" data-toggle="tab">Schedule</a>
            </li>
            <li role="presentation" class="nav-item">
                <a class="nav-link" href="#pulling" aria-controls="pulling" role="tab" data-toggle="tab">Pulling</a>
            </li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content" style="min-height: 512px;">
            <div role="tabpanel" class="tab-pane active" id="home" style="min-height: 160px;">
                <form class="form-horizontal" id="add_depreciation" action="{{route('client.dep_group.store')}}">
                    @csrf
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">Group Asset Name</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="asset_group" name="asset_group"
                                placeholder="Group Asset Name" required autocomplete="off">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputPassword3" class="col-sm-4 control-label">Depreciation
                            Account(Dr.A/C)</label>
                        <div class="col-sm-8">
                            <select class="form-control" id="dep_acc" name="dep_acc">
                                <option>-- </option>
                                @foreach ($dep_accs as $dep_acc)
                                <option value="{{$dep_acc->id}} ">{{$dep_acc->name}} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputPassword3" class="col-sm-4 control-label">Accmulataed Depreciation
                            Account(Cr.A/C)</label>
                        <div class="col-sm-8">
                            <select class="form-control" id="accm_dep_acc" name="accm_dep_acc">
                                <option>--</option>
                                @foreach ($accm_dep_accs as $accm_dep_acc)
                                <option value=" {{$accm_dep_acc->id}} ">{{$accm_dep_acc->name}} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group pull-right" style="margin-right:0px">
                        <button type="submit" class="btn btn-primary btn-sm">Save</button>
                        <button type="reset" class="btn btn-primary btn-sm">Cancel</button>
                        <button type="reset" class="btn btn-primary btn-sm">Close</button>
                    </div>
                    <br>
                </form>

                <div class="col-md-12">
                    <table class="table table-bordered table-hover" id="readDepData"></table>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="profile">
                <table class="table table-bordered table-hover" id="editAssetGroup"></table>
            </div>
            <div role="tabpanel" class="tab-pane" id="settings">Schedule</div>
            <div role="tabpanel" class="tab-pane" id="pulling">Pulling</div>
        </div>
    </div>
</div>

<!-- asset group edit  start-->
<div id="update-modal" class="modal fade" tabindex="-1" role="dialog">
    <form id="asset_groupedit" class="updateFrom">
        <input type="hidden" name="id" id="updateId">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Edit Asset Group</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="groupasset_nameedit">Group Asset Name</label>
                        <input type="text" class="form-control" name="groupasset_name" id="groupasset_nameedit" required
                            autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label for="deprecicatinaccountedit">Depreciation Account</label>
                        <select class="form-control" id="deprecicatinaccountedit" name="deprecicatinaccount">
                            <option> -- </option>
                            @foreach ($dep_accs as $dep_acc)
                            <option value="{{$dep_acc->id}}">{{$dep_acc->name}} </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="accmulatedaccountedit">Accmulataed Depreciation Account</label>
                        <select class="form-control" id="accmulatedaccountedit" name="accmulatedaccount">
                            <option> -- </option>
                            @foreach ($accm_dep_accs as $accm_dep_acc)
                            <option value="{{$accm_dep_acc->id}}">{{$accm_dep_acc->name}} </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
</form>
<!-- asset group edit  edit-->

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
<!-- /Delete Modal -->

@section('script')
<script>
$(function(){
    readDepData();
    // Edit data
    $('#editAssetGroup').on('click', (e) =>{
        e.preventDefault();
        let anchor = $(e.target);
        // console.log(anchor.attr('id'));

        let id = anchor.attr('data-id');
        if (anchor.hasClass('btn')) {
            // console.log(id);
            getRecord(anchor.attr('id'), id);
        }

    });
    $('#asset_groupedit').on('submit', (e)=>{
        e.preventDefault();
        $.ajax({
            url: '{{route("client.dep_group.update")}}',
            type: "put",
            data: {
                asset_group : $("#groupasset_nameedit").val(),
                dep_acc     : $("#deprecicatinaccountedit").val(),
                accm_dep_acc: $("#accmulatedaccountedit").val(),
                id          : $("#updateId").val(),
            },
            success: (res)=> {
                res = $.parseJSON(res)
                readDepData();
                $("form").trigger("reset");
                $('#update-modal').modal('hide');
            }
        });
    });
    $('#add_depreciation').on('submit', (e)=>{
        e.preventDefault();
        $.ajax({
            url: '{{route("client.dep_group.store")}}',
            type: "post",
            data: {
                _token       : $('meta[name="csrf-token"]').attr('content'),
                client_id    : '{{$client->id}}',
                profession_id: '{{$profession->id}}',
                asset_group  : $("#asset_group").val(),
                dep_acc      : $("#dep_acc").val(),
                accm_dep_acc : $("#accm_dep_acc").val()
            },
            success:  (res)=> {
                readDepData();
                $("form").trigger("reset");
                $('form').find('button[type="submit"]').removeAttr("disabled");
                toast('success', 'Successfull' ,'Depreciation Added Successfully');
            }
        });
    });
});
function readDepData(){
    let client_id = "{{$client->id}}";
    let profession_id = "{{$profession->id}}";
    $.ajax({
        url:'{{route("client.dep_group.read")}}',
        method:'get',
        data:{
            client_id:client_id,
            profession_id:profession_id
        },
        success: function (data) {
            data = $.parseJSON(data);
            if (data.status == 'success') {
                $('#readDepData').html(data.html);
                $('#editAssetGroup').html(data.html);
            }
        }
    });
}
function getRecord(actionName, id) {
    $.ajax({
        url: '{{route("client.dep_group.edit")}}',
        method: 'get',
        data: { id: id },
        success:(res)=>{
            res = $.parseJSON(res);
            if (res.status == 'success') {

                if (actionName == 'update') {
                    let modal = $('#update-modal');
                    let form = modal.find('.updateFrom');
                    form.find('#groupasset_nameedit').val(res.data['asset_group']);
                    form.find('#deprecicatinaccountedit').val(res.data['dep_acc']);
                    form.find('#accmulatedaccountedit').val(res.data['accm_dep_acc']);
                    form.find('#updateId').val(res.data['id']);
                    modal.modal('show');

                } else if (actionName == 'delete') {

                    let modal = $('#delete-modal');
                    let form = modal.find('.form');
                    form.find('#deleteId').val( res.data['id']);

                    modal.modal('show');
                    // console.log(modal.html());
                }
            }else{
                console.log('ERROR');
            }
        }
    });
}
</script>
<!-- PAGE CONTENT ENDS -->
@stop
@stop
