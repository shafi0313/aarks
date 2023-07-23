@extends('admin.layout.master')

@section('title', 'Master Chart')

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

        @if($errors->has('password'))
        <div class="error">
            <small class="text-danger" style="margin: 5px">* {{$errors->first('password')}}</small>
        </div>
        @endif

        <div class="page-content">

            <div class="row">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="fa fa-bars" aria-hidden="true"></i> Master Chart</h3>
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
                                    <td colspan="3" style="color: red">
                                        <span type="button" class="edit-account-code"
                                            data-type="category"
                                            data-route="{{route('edit.master.account.code')}}"
                                            data-id="{{$accountCodeCategory->id}}"
                                            data-name="{{$accountCodeCategory->name}}"
                                            data-note="{{$accountCodeCategory->note}}"
                                            style="cursor: pointer">{{$accountCodeCategory->name}}
                                        </span>
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                @foreach($accountCodeCategory->subCategory as $subCategory)
                                <tr>
                                    <td colspan="3" style="color: green"> &nbsp;&nbsp;&nbsp;
                                        <span type="button" class="edit-account-code"
                                            data-type="category"
                                            data-route="{{route('edit.master.account.code')}}"
                                            data-parent_id="{{$accountCodeCategory->id}}"
                                            data-id="{{$subCategory->id}}"
                                            data-name="{{$subCategory->name}}"
                                            data-note="{{$subCategory->note}}"
                                            style="cursor: pointer">{{$subCategory->name}}
                                        </span>
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                @foreach($subCategory->additionalCategory as $additionalCategory)
                                <tr>
                                    <td colspan="3" style="color: violet ">
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <span type="button" class="edit-account-code"
                                            data-type="category"
                                            data-route="{{route('edit.master.account.code')}}"
                                            data-parent_id="{{$subCategory->id}}"
                                            data-id="{{$additionalCategory->id}}"
                                            data-name="{{$additionalCategory->name}}"
                                            data-note="{{$additionalCategory->note}}"
                                            style="cursor: pointer">{{$additionalCategory->name}}
                                        </span>
                                    </td>
                                    <td></td>
                                    <td></td>
                                    @can('admin.master-chart.delete')
                                    @if ($additionalCategory->is_deletable)
                                    <td>
                                        <a title="Category Delete" class="_delete"
                                            data-route="{{route('delete.master.chart.additional.category', ['id' => $additionalCategory->id])}}">
                                            <i class="ace-icon fa fa-trash-o bigger-130 red"></i></a>
                                    </td>
                                    @else
                                    <td></td>
                                    @endif
                                    @else
                                    <td></td>
                                    @endif
                                </tr>
                                @foreach($masterAccountCodes as $masterAccountCode)
                                @if($masterAccountCode->additional_category_id == $additionalCategory->id)
                                <tr>
                                    <td style="color: #1B6AAA">
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <span type="button" class="edit-account-code"
                                            data-type="code"
                                            data-route="{{route('edit.master.account.code')}}"
                                            data-id="{{$masterAccountCode->id}}"
                                            data-name="{{$masterAccountCode->name}}"
                                            data-note="{{$masterAccountCode->note}}"
                                            style="cursor: pointer">{{$masterAccountCode->name}}
                                        </span>
                                    </td>
                                    <td>{{$masterAccountCode->type == 1 ? 'Debit' : 'Credit'}}</td>
                                    <td>{{$masterAccountCode->gst_code}}</td>
                                    <td>{{$masterAccountCode->code}}</td>
                                    <td>{{$masterAccountCode->note}}</td>

                                    @can('admin.master-chart.delete')
                                    @if($masterAccountCode->is_deletable)
                                    <td>
                                        <a class="_delete"
                                            data-route="{{route('delete.master.chart.account.code', ['id' => $masterAccountCode->id])}}"><i
                                                class="ace-icon fa fa-trash-o bigger-130 red"></i></a>
                                    </td>
                                    @else
                                    <td></td>
                                    @endif
                                    @else
                                    <td></td>
                                    @endcan
                                </tr>
                                @endif
                                @endforeach
                                {{-- Account Code End--}}
                                @endforeach
                                {{-- Additonal Category End--}}
                                @endforeach
                                {{-- Sub Category End--}}
                                @endforeach
                                {{--Category End--}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- main form -->
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
                        <form method="post" action="{{route('create.master.account.code')}}">
                            {{csrf_field()}}
                            {{-- <input type="hidden" name="profession_id[]">--}}
                            <div class="row">
                                {{-- industry Category--}}
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="usr"> Industry Category <strong
                                                style="color:red;">*</strong></label>
                                        <select class="form-control heading select2" id="industry_category_id"
                                            name="industry_category_id[]" multiple="multiple" required>
                                            <option value="">Please Select</option>
                                            @foreach($industryCategories as $id => $industryCategory)
                                            <option value="{{$id}}" {{old('industry_category_id')==$id?'selected':''}}>
                                                {{$industryCategory}}</option>
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

                                {{-- category--}}
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="usr">Group <strong style="color:red;">*</strong></label>
                                        <select class="form-control category" id="category" name="category" required>
                                            <option>Please Select</option>

                                            @foreach($accountCodeCategories as $accountCodeCategory)
                                            <option value="{{$accountCodeCategory->id}}"
                                                data-code="{{$accountCodeCategory->code}}"
                                                {{old('category')==$accountCodeCategory->
                                                id?'selected':''}}>{{$accountCodeCategory->name}}-{{$accountCodeCategory->code}}
                                            </option>
                                            @endforeach
                                        </select>
                                        @if($errors->has('category'))
                                        <small class="text-danger">{{$errors->first('category')}}</small>
                                        @endif
                                    </div>
                                </div>
                                {{-- Sub Category--}}
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
                                {{-- //Additional Category--}}
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
                                            <option value="1" {{old('type')==1?'selected':''}}>Debit</option>
                                            <option value="2" {{old('type')==2?'selected':''}}>Credit</option>
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
                                        <span type="text" style="width: 30%" id="auto-code"
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
                                {{-- <div class="col-md-3">--}}
                                    {{-- <label for="usr">For All Profession </label>--}}
                                    {{-- <div class="form-group">--}}
                                        {{-- <input type="checkbox" name="is_for_all_professions" value="1"> Yes--}}
                                        {{-- @if($errors->has('is_for_all_professions'))--}}
                                        {{-- <small
                                            class="text-danger">{{$errors->first('is_for_all_professions')}}</small>--}}
                                        {{-- @endif--}}
                                        {{-- </div>--}}
                                    {{-- </div>--}}
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

                                <div class="row">
                                    <div class="pull-right">
                                        {{-- <a href="https://www.aarks.com.au/System_configuration/sendEmail/38"
                                            class="btn btn-primary"><i class="fa fa-check" aria-hidden="true"></i> Send
                                            Email</a>--}}
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







                {{-- Add new Additional Category--}}
                <!-- heading start -->

                <div>
                    <div class="panel panel-success add-additional-category" style="display:none;">

                        <div class="panel-heading">
                            <h3 class="panel-title"><i class="fa fa-bars" aria-hidden="true"></i> Add New Sub Sub Group
                            </h3>
                        </div>

                        <div class="panel-body">
                            <form method="post" action="{{URL::to(route('create.master.additional.category'))}}">
                                {{csrf_field()}}
                                <div class="row">

                                    {{-- <input type="hidden" name="profession_id[]">--}}

                                    <div class="col-md-12">

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="usr"> Industry Category <strong
                                                            style="color:red;">*</strong></label>
                                                    <select class="form-control heading select2"
                                                        name="industry_category[]" multiple="multiple" required=""
                                                        style="width: 100%;">
                                                        <option>Please Select</option>
                                                        @foreach($industryCategories as $id => $industryCategory)
                                                        <option value="{{$id}}"
                                                            {{old('industry_category_id')==$id?'selected':''}}>
                                                            {{$industryCategory}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="usr">Group <strong style="color:red;">*</strong></label>
                                                    <select class="form-control heading category" id="category"
                                                        name="category" required="">
                                                        <option>Please Select</option>
                                                        @foreach($accountCodeCategories as $accountCodeCategory)
                                                        <option value="{{$accountCodeCategory->id}}"
                                                            data-code="{{$accountCodeCategory->code}}">
                                                            {{$accountCodeCategory->name}}-{{$accountCodeCategory->code}}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            {{-- Sub Category--}}
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="usr">Sub Group <strong
                                                            style="color:red;">*</strong></label>
                                                    <select class="form-control sub_category" id="sub_category"
                                                        name="sub_category" required="">
                                                        <option>Please Select</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="row">

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="usr">Sub Sub Group Name <strong
                                                            style="color:red;">*</strong></label>
                                                    <input type="text" name="additional_category_name" id="heading_name"
                                                        class="form-control" placeholder="  Column Name">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="usr">Sub Sub Group Code <strong
                                                            style="color:red;">*</strong></label>
                                                    <input type="number" min="0" max="9" name="single_account_code"
                                                        id="additional-category-account-code" class="form-control"
                                                        placeholder="  Short Order" autocomplete="off" maxlength="1">
                                                    <small class="error_msg"></small>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <label for="usr">Note</label>
                                                    <textarea rows="1" placeholder="Note here........."
                                                        class="form-control" id="note" name="note"></textarea>
                                                </div>
                                            </div>
                                        </div>


                                    </div>
                                </div>
                                <div class="panel-footer">

                                    <div class="row">
                                        <div class="pull-right">

                                            <button type="submit" class="btn btn-primary submit-btn"><i
                                                    class="fa fa-check" aria-hidden="true"></i> Add</button>
                                            <button type="button" id="cancelAdd" class="btn btn-danger cancel"><i
                                                    class="fa fa-times" aria-hidden="true"></i> Cancel</button>
                                        </div>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Add new Sub Category--}}
                <!-- heading start -->

                <div>
                    <div class="panel panel-success add-sub-category" style="display:none;">

                        <div class="panel-heading">
                            <h3 class="panel-title"><i class="fa fa-bars" aria-hidden="true"></i> Add New Sub Group</h3>
                        </div>

                        <div class="panel-body">
                            <form method="post" action="{{URL::to(route('create.master.sub.category'))}}">
                                {{csrf_field()}}
                                <div class="row">

                                    {{-- <input type="hidden" name="profession_id[]">--}}

                                    <div class="col-md-12">

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="usr"> Industry Category <strong
                                                            style="color:red;">*</strong></label>
                                                    <select class="form-control heading select2"
                                                        name="industry_category[]" multiple="multiple" required=""
                                                        style="width: 100%;">
                                                        <option>Please Select</option>
                                                        @foreach($industryCategories as $id => $industryCategory)
                                                        <option value="{{$id}}"
                                                            {{old('industry_category_id')==$id?'selected':''}}>
                                                            {{$industryCategory}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="usr">Group <strong style="color:red;">*</strong></label>
                                                    <select class="form-control heading category" id="category"
                                                        name="category" required="">
                                                        <option>Please Select</option>
                                                        @foreach($accountCodeCategories as $accountCodeCategory)
                                                        <option value="{{$accountCodeCategory->id}}"
                                                            data-code="{{$accountCodeCategory->code}}">
                                                            {{$accountCodeCategory->name}}-{{$accountCodeCategory->id}}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>


                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="usr">Sub Group Name <strong
                                                            style="color:red;">*</strong></label>
                                                    <input type="text" name="sub_category_name" id="heading_name"
                                                        class="form-control" placeholder="  Column Name">
                                                </div>
                                            </div>

                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="usr">Sub Group Code <strong
                                                            style="color:red;">*</strong></label>
                                                    <input type="number" min="0" max="9" name="single_account_code"
                                                        id="sub-category-account-code" class="form-control"
                                                        placeholder="  Short Order" autocomplete="off" maxlength="1">
                                                    <small class="error_msg"></small>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="usr">Note</label>
                                                    <textarea rows="1" placeholder="Note here........."
                                                        class="form-control" id="note" name="note"></textarea>
                                                </div>
                                            </div>
                                        </div>


                                    </div>
                                </div>
                                <div class="panel-footer">

                                    <div class="row">
                                        <div class="pull-right">

                                            <button type="submit" class="btn btn-primary submit-btn"><i
                                                    class="fa fa-check" aria-hidden="true"></i> Add</button>
                                            <button type="button" id="cancelAdd" class="btn btn-danger cancel"><i
                                                    class="fa fa-times" aria-hidden="true"></i> Cancel</button>
                                        </div>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>
                </div>



            </div>
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
                        <form action="{{route('edit.master.account.code')}}" method="POST" id="edit-form">
                            @csrf
                            <input type="hidden" name="ac_type" id="type">
                            <input type="hidden" name="parent_id" value="0" id="parent_id">
                            <input type="hidden" name="id" id="id">
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
    </div>
</div>

<script>
    $('.edit-account-code').on('click',function(event){
            $('#edit-account-code').modal('show');
            let route     = $(this).attr('data-route');
            let parent_id = $(this).attr('data-parent_id');
            let type      = $(this).attr('data-type');
            let name      = $(this).attr('data-name');
            let note      = $(this).attr('data-note');
            let id        = $(this).attr('data-id');

            $('#id').val(id);
            $('#name').val(name);
            $('#e_note').val(note);
            $('#type').val(type);
            $('#parent_id').val(parent_id);
            $('#edit-form').attr('action', route);

        });


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
        $(document).ready(function(){
            $('[data-toggle="popover"]').popover();
        });

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

            if(category) return category.sub_category;
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

        $('#sub-category-account-code').keyup(function () {
            if($(this).val().length > 1){
                $('.error_msg').addClass('text-danger').html('Code Should Be Limited To 1 Digit');
                $('.submit-btn').prop('disabled', true);
            }else{
                $('.error_msg').removeClass('text-danger').html('');
                $('.submit-btn').prop('disabled', false);
            }
        });

        $('#additional-category-account-code').keyup(function () {
            if($(this).val().length > 1){
                $('.error_msg').addClass('text-danger').html('Code Should Be Limited To 1 Digit');
                $('.submit-btn').prop('disabled', true);
            }else{
                $('.error_msg').removeClass('text-danger').html('');
                $('.submit-btn').prop('disabled', false);
            }
        });

        $('#user-code').keyup(function () {
            if($(this).val().length > 3){
                $('.error_msg').addClass('text-danger').html('Code Should Be Limited To 6 Digit');
                $('.submit-btn').prop('disabled', true);
            }else{
                $('.error_msg').removeClass('text-danger').html('');
                $('.submit-btn').prop('disabled', false);
            }
        });

        $('.select2').select2();
</script>
@stop
