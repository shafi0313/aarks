@extends('frontend.layout.master')
@section('title','Client Depreciation')
@section('content')
<?php $p="cdep"; $mp="acccounts";?>
<!-- Page Content Start -->
<section class="page-content">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <nav>
                            <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
                                <a class="nav-item nav-link active" id="nav-new-group-tab" data-toggle="tab"
                                    href="#nav-new-group" role="tab" aria-controls="nav-new-group" aria-selected="true">
                                    New Group
                                </a>
                                <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile"
                                    role="tab" aria-controls="nav-profile" aria-selected="false">
                                    Edit/Delete Group
                                </a>
                                <a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-contact"
                                    role="tab" aria-controls="nav-contact" aria-selected="false">
                                    Schedule
                                </a>
                                <a class="nav-item nav-link" id="nav-about-tab" data-toggle="tab" href="#nav-about"
                                    role="tab" aria-controls="nav-about" aria-selected="false">
                                    Pulling
                                </a>
                            </div>
                        </nav>
                    </div>
                    <div class="card-body">
                        <div class="tab-content py-3 px-3 px-sm-0" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="nav-new-group" role="tabpanel"
                                aria-labelledby="nav-new-group-tab">
                                <form class="form-horizontal" id="add_depreciation"
                                    action="">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Group Asset
                                            Name</label>
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
                                        <label for="inputPassword3" class="col-sm-4 control-label">Accmulataed
                                            Depreciation Account(Cr.A/C)</label>
                                        <div class="col-sm-8">
                                            <select class="form-control" id="accm_dep_acc" name="accm_dep_acc">
                                                <option>--</option>
                                                @foreach ($accm_dep_accs as $accm_dep_acc)
                                                <option value=" {{$accm_dep_acc->id}} ">{{$accm_dep_acc->name}}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group" style="margin-right:0px">
                                        <button type="submit" class="btn btn-lg btn-primary btn-sm">Save</button>
                                        <button type="reset" class="btn btn-lg btn-primary btn-sm">Cancel</button>
                                        <button type="reset" class="btn btn-lg btn-primary btn-sm">Close</button>
                                    </div>
                                    <br>
                                </form>
                                <table class="table table-bordered table-hover" id="readDepData"></table>
                            </div>
                            <div class="tab-pane fade" id="nav-profile" role="tabpanel"
                                aria-labelledby="nav-profile-tab">
                                <table class="table table-bordered table-hover" id="editAssetGroup"></table>
                            </div>
                            <div class="tab-pane fade" id="nav-contact" role="tabpanel"
                                aria-labelledby="nav-contact-tab">
                                Et et consectetur ipsum labore excepteur est proident excepteur ad velit occaecat qui
                                minim
                            </div>
                            <div class="tab-pane fade" id="nav-about" role="tabpanel" aria-labelledby="nav-about-tab">
                                Et et consectetur ipsum labore excepteur est proident excepteur ad velit occaecat qui
                                minim
                                occaecat veniam. Fugiat veniam incididunt anim aliqua enim pariatur veniam sunt est aute
                                sit
                                dolor anim. Velit non irure adipisicing aliqua ullamco irure incididunt irure non esse
                                consectetur nostrud minim non minim occaecat. Amet duis do nisi duis veniam non est
                                eiusmod
                                tempor incididunt tempor dolor ipsum in qui sit. Exercitation mollit sit culpa nisi
                                culpa non
                                adipisicing reprehenderit do dolore. Duis reprehenderit occaecat anim ullamco ad duis
                                occaecat
                                ex.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@include('frontend.accounts.depreciation.modal', ['type' => 'asset-group-update'])
</section>
@endsection
@section('script')
<script>
    $(function() {
        readDepData();
        // Edit data
        $('#editAssetGroup').on('click', (e) =>{
            e.preventDefault();
            let anchor = $(e.target);
            let id = anchor.attr('data-id');
            if (anchor.hasClass('btn')) {
                getRecord(anchor.attr('id'), id);
            }
        });
        $('#add_depreciation').on('submit', (e)=>{
            e.preventDefault();
            $.ajax({
                url: '{{route("client.dep_group.store")}}',
                type: "post",
                data: {
                    client_id    : '{{$client->id}}',
                    profession_id: '{{$profession->id}}',
                    _token       : '{{csrf_token()}}',
                    asset_group  : $("#asset_group").val(),
                    dep_acc      : $("#dep_acc").val(),
                    accm_dep_acc : $("#accm_dep_acc").val()
                },
                success:  (res)=> {
                    readDepData();
                    $("form").trigger("reset");
                    $("#add_depreciation").find('button[type="submit"]').removeAttr('disabled');
                }
            });
        });

        $('#asset_groupedit').on('submit', (e)=>{
            e.preventDefault();
            $.ajax({
                url: '{{route("client.dep_group.update")}}',
                type: "post",
                data: {
                    _token      : '{{csrf_token()}}',
                    id          : $("#updateId").val(),
                    asset_group : $("#asset_group_editname").val(),
                    dep_acc     : $("#dep_edit_id").val(),
                    accm_dep_acc: $("#accum_dep_edit_id").val(),
                },
                success: (res)=> {
                    readDepData();
                    $("form").trigger("reset");
                    $('#update-modal').modal('hide');
                    $("#asset_groupedit").find('button[type="submit"]').removeAttr('disabled')
                }
            });
        });
    });
    function getRecord(actionName, id) {
        $.ajax({
            url: '{{route("client.dep_group.edit")}}',
            method: 'get',
            data: { id: id },
            success:(res)=>{
                if (res.status == 'success') {
                    if (actionName == 'update') {
                        let modal = $('#update-modal');
                        let form = modal.find('.updateFrom');
                        form.find('#asset_group_editname').val(res.data.asset_group);
                        form.find('#dep_edit_id').val(res.data.dep_acc);
                        form.find('#accum_dep_edit_id').val(res.data.accm_dep_acc);
                        form.find('#updateId').val(res.data.id);
                        modal.modal('show');
                    } else if (actionName == 'delete') {
                        let modal = $('#delete-modal');
                        let form = modal.find('.form');
                        form.find('#deleteId').val( res.data.id);
                        modal.modal('show');
                    }
                }else{
                    console.log('ERROR');
                }
            }
        });
    }
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
</script>
@endsection
