@extends('frontend.layout.master')
@section('title','Account Chart')
@section('content')
<?php $p="ac"; $mp="acccounts";?>
<style>
    .panel-primary>.panel-heading {
        color: #fff;
        background-color: #337ab7;
        border-color: #337ab7;
    }

    .panel-primary {
        border: 2px solid #337ab7;
    }

    .panel-title {
        margin: 0px;
        font-size: 16px;
    }

    .panel-body {
        padding: 15px;
        border: 1px solid #337ab7;
    }

    .panel {
        width: 100%;
        margin-bottom: 20px;
        background-color: #fff;
        border: 1px solid transparent;
        border-radius: 4px;
        -webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, .05);
        box-shadow: 0 1px 1px rgba(0, 0, 0, .05);
    }

    .table>tbody>tr>td,
    .table>tbody>tr>th,
    .table>tfoot>tr>td,
    .table>tfoot>tr>th,
    .table>thead>tr>td,
    .table>thead>tr>th {
        padding: 6px;
        line-height: 1.42857143;
        vertical-align: top;
        border: 1px solid #ddd;
        font-size: 14px;
    }

    .panel-success {
        border: 2px solid #d6e9c6;
    }

    .panel-success>.panel-heading {
        color: #3c763d;
        background-color: #dff0d8;
        border-color: #d6e9c6;
    }

    .panel-heading {
        padding: 10px 15px;
        border-bottom: 1px solid transparent;
        border-top-right-radius: 3px;
        border-top-left-radius: 3px;
    }

    .panel-footer {
        padding: 10px 15px;
        border-bottom-right-radius: 3px;
        border-bottom-left-radius: 3px;
    }
</style>
<div class="main-content">
    <div class="container">
        <div class="page-content">
            <div class="row justify-content-center">
                <div class="col-md-3">
                    <h3>Select Professions</h3>
                </div>
                <div class="col-md-4">
                    <form action="#" name="topform">
                        <div class="form-group">
                            <select class="form-control" onchange="location = this.value">
                                <option>Select Professions</option>
                                @foreach($client->professions as $prfns)
                                <option value="{{route('clientCodeProfession',[$client->id,$prfns->id])}}" @if($prfns->id == $profession->id) selected @endif>
                                    {{$prfns->name}}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-md-10">
                                <h3 class="panel-title"><i class="fa fa-bars"
                                        aria-hidden="true"></i> Chart Account</h3>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <table class="table table-bordered table-hover table-striped">
                            <tbody>
                                <tr>
                                    <th>Heading Name</th>
                                    <th>Debit/Credit</th>
                                    <th>Tax Code</th>
                                    <th>Account Code</th>
                                    <th>Note</th>
                                    <th>Action</th>
                                </tr>

                                @foreach($accountCodeCategories as $accountCodeCategory)
                                <tr>
                                    <td colspan="3" style="color: red">{{$accountCodeCategory->name}}</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                @foreach($accountCodeCategory->subCategoryWithoutAdditional as $subCategory)
                                <tr>
                                    <td colspan="3" style="color: green"> &nbsp;&nbsp;&nbsp;
                                        {{$subCategory->name}}
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                @foreach($subCategory->additionalCategory as $additionalCategory)
                                <tr>
                                    <td colspan="3" style="color: violet">
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        {{$additionalCategory->name}}
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                @foreach($accountCodes as $accountCode)
                                @if($accountCode->additional_category_id == $additionalCategory->id)
                                <tr>
                                    <td style="color: #1B6AAA">
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            {{$accountCode->name}}
                                    </td>
                                    <td>{{$accountCode->type == 1? 'Debit':'Credit'}}</td>
                                    <td>{{$accountCode->gst_code}}</td>
                                    <td>{{$accountCode->code}}</td>
                                    <td>{{$accountCode->note}}</td>
                                    <td>
                                        @if ($accountCode->created_by_type == 2)
                                        @php
                                            $chkLedger = App\Models\GeneralLedger::where('client_id', $client->id)->where('profession_id', $profession->id)->where('chart_id', $accountCode->code)->get();
                                        @endphp
                                        <a title="Edit Account Code" type="button" class="edit-account-code text-info" data-id="{{$accountCode->id}}" data-name="{{$accountCode->name}}"
                                            data-note="{{$accountCode->note}}" style="cursor: pointer">
                                            <i class="fa fa-edit" aria-hidden="true"></i>
                                        </a>
                                        @if ($chkLedger->count() < 1)
                                        <a title="Ledger Delete" type="button" class="delete-account-code text-danger" data-id="{{$accountCode->id}}" style="cursor: pointer">
                                            <i class="fa fa-trash" aria-hidden="true"></i>
                                        </a>
                                        @endif
                                        @endif
                                    </td>
                                </tr>
                                @endif
                                @endforeach
                                {{--                                            Account Code End--}}
                                @endforeach
                                {{--                                        Additonal Category End--}}
                                @endforeach
                                {{--                                    Sub Category End--}}
                                @endforeach
                                {{--                                Category End--}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- main form For Create New Code -->
            <div class="row">
                <div class="panel panel-success mainform">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <i class="fa fa-bars" aria-hidden="true"></i>
                            Add New Account Head
                        </h3>
                    </div>
                    <div class="panel-body">
                        @if($errors->has('single_account_code'))
                        <div class="error">
                            <small class="text-danger">{{$errors->first('single_account_code')}}</small>
                        </div>
                        @endif
                        <form method="post" action="{{route('account_chart_store')}}">
                            @csrf
                            <input type="hidden" name="profession_id" id="profession_id" value="{{$profession->id}}">
                            <input type="hidden" name="client_id" id="client_id" value="{{$client->id}}">
                            <div class="row">
                                {{--                                    industry Category--}}
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="usr"> Industry Category <strong
                                                style="color:red;">*</strong></label>
                                        <select class="form-control heading select2" id="industry_category_id"
                                            name="industry_category_id[]" multiple required style="line-height: 3px;">
                                            @foreach($industryCategories as $industryCategory)
                                            <option value="{{$industryCategory->id}}"
                                                {{old('industry_category_id') == $industryCategory->id?'selected':''}}>
                                                {{$industryCategory->name}}</option>
                                            @endforeach
                                        </select>
                                        @if($errors->has('industry_category_id'))
                                        <div class="error">
                                            <small
                                                class="text-danger">{{$errors->first('industry_category_id')}}</small>
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                {{--                                    category--}}
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="usr">Group <strong style="color:red;">*</strong></label>
                                        <select class="form-control category" id="category" name="category" required>
                                            <option>Please Select</option>
                                            @foreach($accountCodeCategories as $accountCodeCategory)
                                            <option value="{{$accountCodeCategory->id}}"
                                                data-code="{{$accountCodeCategory->code}}"
                                                {{old('category') == $accountCodeCategory->id?'selected':''}}>
                                                {{$accountCodeCategory->name}}-{{$accountCodeCategory->code}}</option>
                                            @endforeach
                                        </select>
                                        @if($errors->has('category'))
                                        <small class="text-danger">{{$errors->first('category')}}</small>
                                        @endif
                                    </div>
                                </div>
                                {{--                                    Sub Category--}}
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="usr">Sub Group <strong style="color:red;">*</strong></label>
                                        <select class="form-control sub_category" id="sub_category" name="sub_category"
                                            required="">

                                        </select>
                                        @if($errors->has('sub_category'))
                                        <small class="text-danger">{{$errors->first('sub_category')}}</small>
                                        @endif
                                    </div>
                                </div>
                                {{--                                    //Additional Category--}}
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="usr">Sub Sub Group <strong style="color:red;">*</strong></label>
                                        <select class="form-control" id="additional_category" name="additional_category"
                                            required="">

                                        </select>
                                        @if($errors->has('additional_category'))
                                        <small class="text-danger">{{$errors->first('additional_category')}}</small>
                                        @endif
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="usr">Account Name <strong style="color:red;">*</strong></label>
                                        <input type="text" name="account_name" id="account" class="form-control"
                                            placeholder="Column Name" value="{{old('account_name')}}">
                                        @if($errors->has('account_name'))
                                        <small class="text-danger">{{$errors->first('account_name')}}</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="usr">Debit/Credit<strong style="color:red;">*</strong></label>
                                        <select class="form-control" name="type" id="debitcredit" required="">
                                            <option value="">Please Select</option>
                                            <option value="1" {{old('type') == 1?'selected':''}}>Debit</option>
                                            <option value="2" {{old('type') == 2?'selected':''}}>Credit</option>
                                        </select>
                                        @if($errors->has('type'))
                                        <small class="text-danger">{{$errors->first('type')}}</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="usr">GST CODE<strong style="color:red;">*</strong></label>
                                        <select class="form-control" id="gst" name="gst_code">
                                            <option value="">Please Select</option>
                                            @foreach(aarks('gst_code') as $gst_code)
                                            <option value="{{$gst_code}}">{{$gst_code}}</option>
                                            @endforeach
                                        </select>
                                        @if($errors->has('gst_code'))
                                        <small class="text-danger">{{$errors->first('gst_code')}}</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <label for="usr">Account Code <strong style="color:red;">*</strong></label>
                                    <div class="input-group">
                                        <span type="text" style="width: 30%" id="auto-code" name="auto-code"
                                            class="form-control text-center" autocomplete="off" readonly></span>
                                        <input type="text" style="width: 60%" id="user-code" name="account_code"
                                            class="form-control" autocomplete="off" placeholder="3 digit" maxlength="3"
                                            value="{{old('account_code')}}" }}>
                                        <input type="hidden" style="width: 60%" id="account_code" class="form-control"
                                            autocomplete="off">
                                        <small class="error_msg"></small>
                                    </div>
                                    @if($errors->has('account_code'))
                                    <small class="text-danger">{{$errors->first('account_code')}}</small>
                                    @endif
                                    <strong class="mgs"></strong>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="usr">Note</label>
                                        <textarea rows="1" placeholder="Note here........." class="form-control"
                                            id="note" name="note">{{old('note')}}</textarea>
                                        @if($errors->has('note'))
                                        <small class="text-danger">{{$errors->first('note')}}</small>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row">

                            </div>
                            <div class="panel-footer">
                                <div class="row justify-content-end">
                                    <div class="float-right">
                                        <button type="submit" class="btn btn-primary submit submit-btn"><i
                                                class="fa fa-check" aria-hidden="true"></i> Add</button>
                                        <button type="button" class="btn btn-danger cancelAdd"><i class="fa fa-times"
                                                aria-hidden="true"></i> Cancel</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
{{--            Edit Account Code Modal--}}
<div class="modal fade" id="edit-account-code" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Account Code</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{route('account_chart_update')}}" method="POST">
                    @csrf @method('put')
                    <input type="hidden" name="id" class="form-control" id="id">
                    <input type="hidden" name="profession_id" class="form-control" id="profession_id"
                        value="{{$profession->id}}">
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Name:</label>
                        <input type="text" name="name" class="form-control" id="name">
                    </div>
                    <div class="form-group">
                        <label for="message-text" class="col-form-label">Note:</label>
                        <input type="text" name="note" class="form-control" id="e_note">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
{{--Edit Account Code Modal--}}
<div class="modal fade" id="delete-account-code" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel text-danger">Delete Account Code</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h2 class="text-danger">Are your sure Delete?</h2>
                <form action="{{route('account_chart_delete')}}" method="POST">
                    @csrf @method('delete')
                    <input type="hidden" name="id" class="form-control" id="delete_id">
                    <input type="hidden" name="profession_id" class="form-control" value="{{$profession->id}}">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
            </div>
        </div>
        <!-- Footer Start -->

        <script>
            $('.edit-account-code').on('click',function(event){
                $('#edit-account-code').modal('show');
                let name = $(this).attr('data-name');
                let note = $(this).attr('data-note');
                let id = $(this).attr('data-id');

                $('#id').val(id);
                $('#name').val(name);
                $('#e_note').val(note);
            });
            $('.delete-account-code').on('click',function(event){
                $('#delete-account-code').modal('show');
                let id = $(this).attr('data-id');
                $('#delete_id').val(id);
            });
        </script>

        <script type="text/javascript">
            //Important
                $("#additional_category").on('change', function(e){
                   let additional_category = $(this).val();
                    if(additional_category == 0){
                        $(".add-additional-category").css('display','block');
                        $(".mainform").css('display','none');
                        $(".add-sub-category").css('display','none');
                    }
                });

                // $(".sub_category").on('change', function(e){
                //     // let sub_category = $(this).val();
                //     // if(sub_category == 0){
                //     //     $(".add-sub-category").css('display','block');
                //     //     $(".mainform").css('display','none');
                //     // } else{
                //     //     $(".mainform").css('display', 'block');
                //     //     $(".add-sub-category").css('display', 'none');
                //     // }
                // });

                $(".cancel").on('click', function(){
                    $(".add-sub-category").css('display','none');
                    $(".add-additional-category").css('display','none');
                    $(".mainform").css('display', 'block');
                    $('#additonal_category option').prop('selected',false);
                    $('.sub_category option').prop('selected',false);
                });
                //important end
        </script>

        <script>
            $(document).ready(function(){
                    $('[data-toggle="popover"]').popover();
                });
        </script>
        <script>
            let accountCodeCategories = JSON.parse('{!! json_encode($accountCodeCategories) !!}');
                let sub_categories,additional_categories,autoCode,code_prefix = 0;

                $('.category').change(function () {
                    let category_id = $(this).val();
                    let category_code = parseInt($(this).find(':selected').data('code'));
                    sub_categories = get_subCategories(category_id);
                    generate_subCategories_options(sub_categories);
                    generate_auto_account_code(category_code, 100);

                });

                $('.sub_category').change(function () {
                    let subCategory_id = $(this).val();
                    if(subCategory_id == 0){
                            $(".add-sub-category").css('display','block');
                            $(".mainform").css('display','none');
                            $(".add-additional-category").css('display','none');
                        } else{
                            let sub_category_code = parseInt($(this).find(':selected').data('code'));
                            additional_categories = get_additionalCategories(subCategory_id);
                            generate_additionalCategories_options(additional_categories);
                            generate_auto_account_code(sub_category_code, 10);

                        }
                });

                $('#additional_category').change(function () {
                    let additionalCategory_id = $(this).val();
                    let additional_category_code = $(this).find(':selected').data('code');
                    if(additionalCategory_id != 0){
                        generate_auto_account_code(additional_category_code, 1);
                    }

                });

                function generate_subCategories_options(sub_categories) {
                    $('.sub_category').html('').append('<option>Please Select</option>');

                    for (let i = 0; i< sub_categories.length; i++){
                            let sub_category = sub_categories[i];
                            $('.sub_category').append(`<option value="${sub_category.id}" data-code="${sub_category.code}">${sub_category.name}-${sub_category.code}</option>`);
                        }
                }
                function generate_additionalCategories_options(additionalCategory){
                    $('#additional_category').html('').append('<option>Please Select</option>');

                    for (let i = 0; i< additionalCategory.length; i++){
                        let additional_category = additionalCategory[i];
                        $('#additional_category').append(`<option value="${additional_category.id}" data-code="${additional_category.code}">${additional_category.name}-${additional_category.code}</option>`);
                    }
                }
                function get_subCategories(category_id) {
                    let category = accountCodeCategories.find(function(category){
                        return category.id == category_id;
                    });

                    if(category) return category.sub_category_without_additional;
                    return [];

                }
                function  get_additionalCategories(subCategory_id) {
                    let sub_category = sub_categories.find(function(sub_category){
                        return sub_category.id == subCategory_id;
                    });

                    if(sub_category) return sub_category.additional_category;
                    return [];
                }

                function generate_auto_account_code(value, multiplier){
                    let category_code = $('#category').find(':selected').data('code');
                    let sub_category_code = $('#sub_category').find(':selected').data('code');
                    let additional_category_code = $('#additional_category').find(':selected').data('code');

                    code_prefix = (category_code ? category_code : 0) * 100;
                    code_prefix += (sub_category_code ? sub_category_code : 0) * 10;
                    code_prefix += (additional_category_code ? additional_category_code : 0) * 1;

                    $('#auto-code').text(code_prefix);
                }
        </script>
        <script>
            function account_code_category_digit_validation() {
                    if($(this).val().length > 1){
                        $('.error_msg').addClass('text-danger').html('Code Should Be Limited To 1 Digit');
                        $('.submit-btn').prop('disabled', true);
                    }else{
                        $('.error_msg').removeClass('text-danger').html('');
                        $('.submit-btn').prop('disabled', false);
                    }
                }
                $('#sub-category-account-code').keyup(account_code_category_digit_validation);

                $('#additional-category-account-code').keyup(account_code_category_digit_validation);
        </script>
        <script>
            $('#user-code').keyup(function () {
                    if($(this).val().length > 3){
                        $('.error_msg').addClass('text-danger').html('Code Should Be Limited To 3 Digit');
                        $('.submit-btn').prop('disabled', true);
                    }else{
                        $('.error_msg').removeClass('text-danger').html('');
                        $('.submit-btn').prop('disabled', false);
                    }
                });
                $('.select2').select2();
        </script>
        <!-- Page Content End -->
        <!-- Footer End -->

        @stop
