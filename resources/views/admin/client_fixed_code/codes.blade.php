@extends('admin.layout.master')

@section('title', 'Account Codes')

@section('content')
<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs" id="breadcrumbs">
            <script type="text/javascript">
                try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
            </script>

            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="{{ route('admin.dashboard') }}">Home</a>
                </li>

                <li>
                    <a href="#">Codes</a>
                </li>
            </ul><!-- /.breadcrumb -->

        </div>

        <div class="page-content">
            <div class="row">
                <div class="col-md-3">
                    <h2>Select Professions</h2>
                </div>

                <div class="col-md-4" style="padding-top:20px;">
                    <form action="#" name="topform">
                        <div class="form-group">
                            <select class="form-control" onchange="location = this.value">
                                <option disabled selected value>Select Professions</option>
                                @foreach($client->professions as $prof)
                                <option
                                    {{$prof->id==$profession->id?'selected':''}}
                                    value="{{route('client_fixed_code.show',['client' => $client->id,'profession' => $prof->id])}}">
                                    {{$prof->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
            </div>

            <div class="row">

                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-md-10">
                                <h3 class="panel-title" style="margin:15px;"><i class="fa fa-bars"
                                        aria-hidden="true"></i> Chart Account</h3>
                            </div>
                            @if (!$profession->is_master_account_code_synced &&
                            $profession->can_perform_sync)
                            <div class="col-md-2 text-right">
                                <a class="btn btn-success"
                                    href="{{route('master-account-code.sync', $profession->id)}}"><b>Sync
                                        Master Chart</b></a>
                            </div>
                            @endif
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
                                </tr>


                                @foreach($accountCodeCategories as $accountCodeCategory)
                                <tr>
                                    <td colspan="3" style="color: red">{{$accountCodeCategory->name}}</td>
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
                                </tr>
                                @foreach($subCategory->additionalCategory as $additionalCategory)
                                <tr>
                                    <td colspan="3" style="color: violet">
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        {{$additionalCategory->name}}
                                    </td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                @foreach($accountCodes as $accountCode)
                                @if($accountCode->additional_category_id == $additionalCategory->id)
                                <tr>
                                    <td style="color: #1B6AAA">
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        @if(!$accountCode->is_universal)
                                        <a type="button" class="edit-account-code" data-id="{{$accountCode->id}}"
                                            data-name="{{$accountCode->name}}" data-note="{{$accountCode->note}}"
                                            style="cursor: pointer">
                                            {{$accountCode->name}}
                                        </a>
                                        @else
                                        <a type="button" style="text-decoration: none">
                                            {{$accountCode->name}}
                                        </a>
                                        @endif
                                    </td>
                                    <td>{{$accountCode->type == 1? 'Debit':'Credit'}}</td>
                                    <td>{{$accountCode->gst_code}}</td>
                                    <td>{{$accountCode->code}}</td>
                                    <td>{{$accountCode->note}}</td>
                                </tr>
                                @endif
                                @endforeach
                                {{-- Account Code End--}}
                                @endforeach
                                {{-- Additonal Category End--}}
                                @endforeach
                                {{-- Sub Category End--}}
                                @endforeach
                                {{-- Category End--}}

                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
            {{-- @include('admin.client_fixed_code.add_code') --}}
        </div>



        {{-- Edit Account Code Modal--}}
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
                        <form action="{{route('client_fixed_code.update')}}" method="POST">
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
        </script>
        {{-- Edit Account Code Modal--}}


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
                    $('.sub_category').html('').append('<option>Please Select</option>').append(`<option value="0">New</option>`);

                    for (let i = 0; i< sub_categories.length; i++){
                            let sub_category = sub_categories[i];
                            $('.sub_category').append(`<option value="${sub_category.id}" data-code="${sub_category.code}">${sub_category.name}-${sub_category.code}</option>`);
                        }

                }
                function generate_additionalCategories_options(additionalCategory){
                    $('#additional_category').html('').append('<option>Please Select</option>').append(`<option value="0">New</option>`);

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
        @endsection
