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
            <form method="post" action="{{URL::to(route('create.account.code'))}}">
                {{csrf_field()}}
                <input type="hidden" name="profession_id" id="profession_id" value="{{$profession->id}}">
                <div class="row">
                    {{-- industry Category--}}
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="usr"> Industry Category <strong style="color:red;">*</strong></label>
                            <select class="form-control heading select2" id="industry_category_id"
                                name="industry_category_id[]" multiple="multiple" required style="line-height: 3px;">
                                <option value="">Please Select</option>
                                @foreach($industryCategories as $industryCategory)
                                <option value="{{$industryCategory->id}}"
                                    {{old('industry_category_id')==$industryCategory->
                                    id?'selected':''}}>{{$industryCategory->name}}</option>
                                @endforeach
                            </select>
                            @if($errors->has('industry_category_id'))
                            <div class="error">
                                <small class="text-danger">{{$errors->first('industry_category_id')}}</small>
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
                                <option value="{{$accountCodeCategory->id}}" data-code="{{$accountCodeCategory->code}}"
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
                            <select class="form-control sub_category" id="sub_category" name="sub_category" required="">

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
                            <textarea rows="1" placeholder="Note here........." class="form-control" id="note"
                                name="note">{{old('note')}}</textarea>
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
                            <button type="submit" title="Check" class="btn btn-primary submit submit-btn"><i title="Check" class="fa fa-check"
                                    aria-hidden="true"></i> Add</button>
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
                <form method="post" action="{{URL::to(route('create.additional.category'))}}">
                    {{csrf_field()}}
                    <div class="row">
                        <input type="hidden" name="profession_id" value="{{$profession->id}}">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="usr"> Industry Category <strong style="color:red;">*</strong></label><br>
                                <select class="form-control heading select2" id="" name="industry_category[]"
                                    required="" multiple style="width: 100%">
                                    <option>Please Select</option>
                                    @foreach($industryCategories as $industryCategory)
                                    <option value="{{$industryCategory->id}}">{{$industryCategory->name}}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="usr">Group <strong style="color:red;">*</strong></label>
                                <select class="form-control heading category" id="category" name="category" required="">
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
                                <label for="usr">Sub Group <strong style="color:red;">*</strong></label>
                                <select class="form-control sub_category" id="sub_category" name="sub_category"
                                    required="">
                                    <option>Please Select</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="usr">Sub Sub Group Name <strong style="color:red;">*</strong></label>
                                <input type="text" name="additional_category_name" id="heading_name"
                                    class="form-control" placeholder="  Column Name">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="usr">Sub Sub Group Code <strong style="color:red;">*</strong></label>
                                <input type="text" name="single_account_code" id="additional-category-account-code"
                                    class="form-control" placeholder="  Short Order" autocomplete="off">
                                <small class="error_msg"></small>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="usr">Note</label>
                                <textarea rows="1" placeholder="Note here........." class="form-control" id="note"
                                    name="note"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="panel-footer">

                        <div class="row">
                            <div class="pull-right">

                                <button type="submit" class="btn btn-primary submit-btn"><i class="fa fa-check"
                                        aria-hidden="true"></i> Add</button>
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
                <form method="post" action="{{URL::to(route('create.sub.category'))}}">
                    {{csrf_field()}}
                    <div class="row">

                        <input type="hidden" name="profession_id" value="{{$profession->id}}">

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="usr"> Industry Category <strong style="color:red;">*</strong></label><br>
                                <select class="form-control heading select2" id="" name="industry_category[]"
                                    required="" multiple style="width: 100%;">
                                    <option>Please Select</option>
                                    @foreach($industryCategories as $industryCategory)
                                    <option value="{{$industryCategory->id}}">{{$industryCategory->name}}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="usr">Group <strong style="color:red;">*</strong></label>
                                <select class="form-control heading category" id="category" name="category" required="">
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


                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="usr">Sub Group Name <strong style="color:red;">*</strong></label>
                                <input type="text" name="sub_category_name" id="heading_name" class="form-control"
                                    placeholder="  Column Name">
                            </div>
                        </div>
                    </div>
                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="usr">Sub Group Code <strong style="color:red;">*</strong></label>
                                <input type="text" name="single_account_code" id="sub-category-account-code"
                                    class="form-control" placeholder="  Short Order" autocomplete="off">
                                <small class="error_msg"></small>
                                <small id="error_msg"></small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="usr">Note</label>
                                <textarea rows="1" placeholder="Note here........." class="form-control" id="note"
                                    name="note"></textarea>
                            </div>
                        </div>


                    </div>
                    <div class="panel-footer">

                        <div class="row">
                            <div class="pull-right">

                                <button type="submit" class="btn btn-primary submit-btn"><i class="fa fa-check"
                                        aria-hidden="true"></i> Add</button>
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
