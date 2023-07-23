@extends('frontend.layout.master')
@section('title', $client->fullname)
@section('content')
<?php $p="mp"; $mp="setting";?>
<!-- Page Content Start -->
<section class="page-content">
    <div class="container">
        <div class="row">
            <div class="row">
                <div class="col-xs-12">
                    @if($errors->all())
                    <div class="alert alert-block alert-danger">
                        {{ $errors->first() }}
                    </div>
                    @endif
                    <!-- PAGE CONTENT BEGINS -->
                    <form action="{{ route('profile.update', $client->id) }}" method="post">
                        @csrf
                        @method('put')
                        <div class="col-md-12 showform">
                            <div class="widget-box">
                                <div class="widget-header">
                                    <h4 class="widget-title"></h4>
                                </div>
                                <div class="widget-body">
                                    <div class="widget-main">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="person_color">Company /Trust/Partner ship Name</label>
                                                    <input type="text" class="form-control" name="company"
                                                        value="{{$client->company}}">
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="person_color">Contact Person<span
                                                            class=" nomenforcontactperson"
                                                            style="color:red;"></span></label>
                                                    <div class="contact_persons">
                                                        <input type="text" class="form-control" name="contact_person"
                                                            value="{{$client->contact_person}}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="person_color">First Name<span class="notmontory"
                                                            style="color:red;">*</span></label>

                                                    <div class="firstname">

                                                        <input type="text" class="form-control" name="first_name"
                                                            value="{{$client->first_name}}" required>
                                                    </div>


                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="person_color">Last Name<span
                                                            class="removeclass notmontory"
                                                            style="color:red;">*</span></label>
                                                    <div class="lastname">
                                                        <input type="text" class="form-control" name="last_name"
                                                            value="{{$client->last_name}}" required>
                                                    </div>


                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="person_color">Date of Birth<span
                                                            class="removeclass notmontory"
                                                            style="color:red;"></span></label>
                                                    <div class="birth">
                                                        <input class="form-control date-picker" name="birthday"
                                                            type="date" data-date-format="dd/mm/yyyy"
                                                            value="{{$client->birthday?$client->birthday->format('Y-m-d'):''}}">
                                                    </div>

                                                </div>
                                            </div>


                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="person_color">Phone Number <span
                                                            style="color:red;">*</span></label>
                                                    <input type="text" class="form-control" name="phone"
                                                        value="{{$client->phone}}">
                                                </div>
                                            </div>


                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="person_color">Email Address <span
                                                            style="color:red;">*</span></label>
                                                    <input type="text" class="form-control" name="email"
                                                        value="{{$client->email}}">
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="person_color">ABN Number<span
                                                            style="color:red;">*</span> <strong class="abn_mgs"
                                                            style="color:red;"></strong></label>
                                                    <input type="text" class="form-control" name="abn_number"
                                                        value="{{$client->abn_number}}">
                                                </div>
                                            </div>

                                            <div class="col-md-1">
                                                <div class="form-group">
                                                    <label class="person_color">Branch<span style="color:red;">*</span>
                                                    </label>
                                                    <input type="text" class="form-control" name="branch"
                                                        value="{{$client->branch}}">
                                                </div>
                                            </div>



                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="person_color">Tax File Number <span
                                                            style="color:red;">*</span></label>
                                                    <input type="text" class="form-control" name="tax_file_number"
                                                        value="{{$client->tax_file_number}}">
                                                </div>
                                            </div>



                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="person_color">Street Address<span
                                                            style="color:red;">*</span></label>
                                                    <input type="text" class="form-control" name="street_address"
                                                        value="{{$client->street_address}}">
                                                </div>
                                            </div>



                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="person_color">Suburb<span
                                                            style="color:red;">*</span></label>
                                                    <input type="text" class="form-control" name="suburb"
                                                        value="{{$client->suburb}}">
                                                </div>
                                            </div>


                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="person_color">State<span
                                                            style="color:red;">*</span></label>

                                                    <select class="form-control" name="state">
                                                        <option value="">Please Select State</option>
                                                        <option value="WA" {{$client->state == 'WA'?'selected':''}}>WA
                                                        </option>
                                                        <option value="NSW" {{$client->state == 'NSW'?'selected':''}}>
                                                            NSW</option>
                                                        <option value="VIC" {{$client->state == 'VIC'?'selected':''}}>
                                                            VIC</option>
                                                        <option value="SA" {{$client->state == 'SA'?'selected':''}}>SA
                                                        </option>
                                                        <option value="NT" {{$client->state == 'NT'?'selected':''}}>NT
                                                        </option>
                                                        <option value="ACT" {{$client->state == 'ACT'?'selected':''}}>
                                                            ACT</option>
                                                        <option value="TAS" {{$client->state == 'TAS'?'selected':''}}>
                                                            TAS</option>
                                                        <option value="QLD" {{$client->state == 'QLD'?'selected':''}}>
                                                            QLD</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="person_color">Post Code<span
                                                            style="color:red;">*</span></label>
                                                    <input type="text" class="form-control" name="post_code"
                                                        value="{{$client->post_code}}">
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="person_color">Country<span
                                                            style="color:red;">*</span></label>
                                                    <input type="text" class="form-control" name="country"
                                                        value="{{$client->country}}">
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="person_color">Web Address</label>
                                                    <input type="text" class="form-control" name="website"
                                                        value="{{$client->website}}">
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="dirc_color">Director Name</label>
                                                    <input type="text" class="form-control" autocomplete="off"
                                                        name="director_name" value="{{$client->director_name}}">

                                                </div>
                                            </div>


                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="dirc_color">Director Address</label>
                                                    <input type="text" class="form-control" autocomplete="off"
                                                        name="director_address" value="{{$client->director_address}}">

                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="dirc_color">Accountant/Tax Agent Name <span
                                                            style="color:red;">*</span></label>
                                                    <input type="text" class="form-control" autocomplete="off"
                                                        name="agent_name" value="{{$client->agent_name}}">

                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="dirc_color">Accountant/Tax Agent Address</label>
                                                    <input type="text" class="form-control" autocomplete="off"
                                                        name="agent_address" value="{{$client->agent_address}}">

                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="dirc_color">Accountant/Tax Agent Number <span
                                                            style="color:red;">*</span></label>
                                                    <input type="text" class="form-control" autocomplete="off"
                                                        name="agent_number" value="{{$client->agent_number}}">
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="dirc_color">Accountant/Tax Agent ABN Number <span
                                                            style="color:red;">*</span></label>
                                                    <input type="text" class="form-control" autocomplete="off"
                                                        name="agent_abn_number" value="{{$client->agent_abn_number}}">
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="dirc_color">Auditor Name</label>
                                                    <input type="text" class="form-control" autocomplete="off"
                                                        name="auditor_name" value="{{$client->auditor_name}}">

                                                </div>
                                            </div>



                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="dirc_color">Auditor Address</label>
                                                    <input type="text" class="form-control" autocomplete="off"
                                                        name="auditor_address" value="{{$client->auditor_address}}">
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="dirc_color">Auditor Phone</label>
                                                    <input type="text" class="form-control" autocomplete="off"
                                                        name="auditor_phone" value="{{$client->auditor_phone}}">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="sec_color">Password<span
                                                            style="color:red;">*</span></label>
                                                    <input type="password" class="form-control" autocomplete="off"
                                                        name="password" placeholder="password"
                                                        autocomplete="new-password"
                                                        onblur="this.setAttribute('readonly', 'readonly');"
                                                        onfocus="this.removeAttribute('readonly');" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="sec_color" for="password-confirm">Confirm
                                                        Password<span style="color:red;">*</span></label>
                                                    <input id="password-confirm" type="password" class="form-control"
                                                        name="password_confirmation" required
                                                        onblur="this.setAttribute('readonly', 'readonly');"
                                                        onfocus="this.removeAttribute('readonly');" readonly
                                                        autocomplete="new-password" placeholder="confirm password">
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label style="font-size:20px;">Service<span
                                                            style="color:red;"></span></label>
                                                    <br />
                                                    <div class="checkbox">
                                                        @foreach($services as $service)
                                                        <label><input class="ace ace-checkbox-2" type="checkbox"
                                                                name="services[]" value="{{$service->id}}"

                                                            {{in_array($service->id, $client->services->pluck('id')->toArray())? 'checked':''}}
                                                            >
                                                            <span class="lbl">{{$service->name}}</span>
                                                        </label>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label style="font-size:20px;">GST Resgitaration:<span
                                                            style="color:red;"></span></label>
                                                    <br />
                                                    <input type="radio" name="is_gst_enabled" value="1"
                                                        {{$client->is_gst_enabled == 1?'checked':''}} id="gstYes">Yes
                                                    <input type="radio" name="is_gst_enabled" value="0"
                                                        {{$client->is_gst_enabled == 0?'checked':''}} id="gstNo">No
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label style="font-size:20px;">GST Method:<span
                                                            style="color:red;"></span></label>
                                                    <br />
                                                    <input type="radio" name="gst_method" value="2"
                                                        {{$client->gst_method == 2?'checked':''}} class="gstMethod"
                                                    {{$client->is_gst_enabled == 0?'disabled':''}}>Accrued
                                                    <input type="radio" name="gst_method" value="1"
                                                        {{$client->gst_method == 1?'checked':''}} class="gstMethod"
                                                    {{$client->is_gst_enabled == 0?'disabled':''}}>Cash
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <label style="font-size:20px;">Profession<span
                                                        style="color:red;"></span></label>
                                                <br />
                                                <div class="checkbox">
                                                    @foreach($professions as $profession)
                                                    <label>
                                                        <input class="ace ace-checkbox-2 professions" type="checkbox"
                                                            name="professions[]" value="{{$profession->id}}"
                                                            @foreach($client->professions as $client_profession )
                                                        {{$client_profession->id == $profession->id?'checked':''}}
                                                        @endforeach >
                                                        <span class="lbl"> {{ $profession->name }} </span>
                                                    </label>

                                                    @endforeach
                                                </div>
                                            </div>
                                            <div class="col-md-4" style="padding-top:20px;">

                                                <div class="form-group">
                                                    <button type="submit"
                                                        class="btn btn-info submit update">Update</button>
                                                </div>

                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div><!-- /.span -->
                    </form>
                </div><!-- PAGE CONTENT ENDS -->
            </div><!-- /.col -->
            <div class="col-md-12">
                <form action="{{route('bsbtable.store')}}" method="post" autocomplete="off">
                    @csrf
                    <div class="row">
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Select Business Activity</label>
                                        <select class="form-control" id="profession_id" name="profession_id" required>
                                            <option value selected disabled>Select Business</option>
                                            @foreach ($client->professions as $profession)
                                            <option value="{{$profession->id}}">{{$profession->name}} </option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>BSB Number <span style="color:red;">*</span></label>
                                        <input type="text" pattern="\d{3}-\d{3}" placeholder="000-000" id="bsb_number"
                                            name="bsb_number" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Account Number <span style="color:red;">*</span></label>
                                        <input type="text" id="account_number" name="account_number"
                                            class="form-control">
                                    </div>
                                </div>

                                <div class="col-md-1" style="padding-top:25px;">
                                    <button type="submit" class="btn btn-primary btn-sm add-item">Add</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-12">
                <table class="table table-bordered item-table">
                    <thead>
                        <tr>
                            <th style="text-align:center;">Activity Name</th>
                            <th style="text-align:center;">BSB Number</th>
                            <th style="text-align:center;">Account Number</th>
                            <th style="text-align:center;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bsbs as $bsb)
                        <tr>
                            <td align="center">{{$bsb->profession->name}}</td>
                            <td align="center">{{$bsb->bsb_number}}</td>
                            <td align="center">{{$bsb->account_number}}</td>
                            <td align="center">
                                <a title="BSB Delete" class="text-danger" href="{{route('bsbtable.destroy', $bsb->id)}}">
                                    <i class="ace-icon fa fa-trash bigger-130"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
<!-- Page Content End -->
<script>
    $(".add-item").on('click', function(e){
    var bsb_number		 = $('#bsb_number').val();
    var account_number   = $('#account_number').val();
    if(bsb_number == ""){
        toast("error","BSB Number Can not be empty!");
        $("#bsb_number").focus();
        return false;
    }
    if(account_number == ""){
        toast("error","Account Number  Can not be empty!");
        $("#account_number").focus();
        return false;

    }
});

</script>
<!-- Footer Start -->

<!-- Footer End -->

@stop
