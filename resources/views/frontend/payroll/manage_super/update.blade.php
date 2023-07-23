@extends('frontend.layout.master')
@section('title','Manage Superannuation')
@section('content')
<?php $p="ms"; $mp="payroll";?>
<!-- Page Content Start -->
<section class="page-content">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-body">
                        <div class="card-heading">
                            <p>Superannuation</p>
                        </div>
                        <form role="form" action=" {{route('clientannuation.update',$clientannuation->id)}} "
                            method="POST" autocomplete="off">
                            @csrf @method('put')
                            <div class="row" style="padding-left:110px">
                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <label for="exampleInputEmail1" class=" control-label text-right">Superannuation
                                            Name<strong style="color:red;">*</strong></label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control form-control-sm" name="name"
                                                id="name" value="{{$clientannuation->name}}">
                                            <strong class="duplicat"></strong>
                                            @error('name')<span class="text-danger">{{$message}} </span>@enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- <div class="col-md-12">
        <div class="form-group row">
            <label for="exampleInputEmail1" class="col-sm-2 control-label">Superannuation Name<strong
                    style="color:red;">*</strong>
            </label>
            <div class="col-sm-4">
                <input type="text" class="form-control" name="name" id="name"
                    value="{{$clientannuation->name}}">
                            @error('name')<span class="text-danger">{{$message}} </span>@enderror
                    </div>
                </div>
            </div> --}}

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label for="e_rate" class="col-sm-6 control-label" align="right">Equals</label>
                        <div class="col-sm-6">
                            <input type="number" class="form-control form-control-sm" id="e_rate" name="e_rate"
                                value="{{$clientannuation->e_rate}}">
                            @error('e_rate')<span class="text-danger">{{$message}} </span>@enderror
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label for="e_tools" class="col-sm-4 control-label" align="right">Percent of</label>
                        <div class="col-sm-6">
                            <select name="e_tools" id="e_tools" class="form-control form-control-sm">
                                <option value="BS" {{$clientannuation->e_tools == 'BS'?'selected':''}}>Basic
                                    Sallary
                                </option>
                                <option value="GH" {{$clientannuation->e_tools == 'GH'?'selected':''}}>Gross
                                    Hours</option>
                            </select>
                            </select>
                            @error('e_tools')<span class="text-danger">{{$message}} </span>@enderror
                        </div>
                    </div>
                </div>
            </div>




            {{-- <div class="col-md-6">
        <div class="form-group row">
            <label for="e_rate" class="col-sm-4 control-label" >Equals</label>
            <div class="col-sm-8">
                <input type="number" class="form-control" id="e_rate" name="e_rate"
                    value="{{$clientannuation->e_rate}}">
            @error('e_rate')<span class="text-danger">{{$message}} </span>@enderror
        </div>
    </div>
    </div>
    <div class="col-md-6">
        <div class="form-group row">
            <label for="e_tools" class="col-sm-4 control-label">Percent
                of</label>
            <div class="col-sm-8">
                <select name="e_tools" id="e_tools" class="form-control">
                    <option value="BS" {{$clientannuation->e_tools == 'BS'?'selected':''}}>Basic
                        Sallary
                    </option>
                    <option value="GH" {{$clientannuation->e_tools == 'GH'?'selected':''}}>Gross
                        Hours</option>
                </select>
                @error('e_tools')<span class="text-danger">{{$message}} </span>@enderror
            </div>
        </div>
    </div> --}}
    <div class="row">
        <div class="col-md-6">
            <div class="form-group row">
                <label for="e_fix_amt" class="col-sm-6 control-label" align="right">Equals</label>
                <div class="col-sm-6">
                    <input type="number" class="form-control form-control-sm" id="e_fix_amt" name="e_fix_amt"
                        value="{{$clientannuation->e_fix_amt}}">
                    @error('e_fix_amt')<span class="text-danger">{{$message}} </span>@enderror
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group row">
                <label for="e_period" class="col-sm-4 control-label" align="right">Dollars Per</label>
                <div class="col-sm-6">
                    <select name="e_period" id="e_period" class="form-control form-control-sm">
                        <option {{$clientannuation->e_period == 'Pay Period'?'selected':''}} value="Pay Period"
                            selected>Pay Period</option>
                        <option {{$clientannuation->e_period == 'Per Hours'?'selected':''}} value="Per Hours">Per
                            Hours</option>
                        <option {{$clientannuation->e_period == 'Per Month'?'selected':''}} value="Per Month">Per
                            Month</option>
                        <option {{$clientannuation->e_period == 'Per Forthnighty'?'selected':''}}
                            value="Per Forthnighty">Per Forthnighty
                        </option>
                    </select>
                    @error('e_period')<span class="text-danger">{{$message}} </span>@enderror
                </div>
            </div>
        </div>
    </div>




    {{-- <div class="col-md-6">
        <div class="form-group row">
            <label for="e_fix_amt" class="col-sm-4 control-label" >Equals</label>
            <div class="col-sm-8">
                <input type="number" class="form-control" id="e_fix_amt" name="e_fix_amt"
                    value="{{$clientannuation->e_fix_amt}}">
    @error('e_fix_amt')<span class="text-danger">{{$message}} </span>@enderror
    </div>
    </div>
    </div>
    <div class="col-md-6">
        <div class="form-group row">
            <label for="e_period" class="col-sm-4 control-label">Dollars
                Per</label>
            <div class="col-sm-8">
                <select name="e_period" id="e_period" class="form-control">
                    <option {{$clientannuation->e_period == 'Pay Period'?'selected':''}} value="Pay Period" selected>Pay
                        Period</option>
                    <option {{$clientannuation->e_period == 'Per Hours'?'selected':''}} value="Per Hours">Per
                        Hours</option>
                    <option {{$clientannuation->e_period == 'Per Month'?'selected':''}} value="Per Month">Per
                        Month</option>
                    <option {{$clientannuation->e_period == 'Per Forthnighty'?'selected':''}} value="Per Forthnighty">
                        Per Forthnighty
                    </option>
                </select>
                @error('e_period')<span class="text-danger">{{$message}} </span>@enderror
            </div>
        </div>
    </div> --}}

    <div class="row" style="padding-left: 81px">
        <div class="col-md-12">
            <div class="form-group row">
                <label for="e_excl_amt" class="control-label">Exclusions: Exclude
                    the first</label>
                <div class="col-sm-5">
                    <input type="number" class="form-control form-control-sm" id="e_excl_amt" name="e_excl_amt">
                    @error('e_excl_amt')<span class="text-danger">{{$message}} </span>@enderror

                </div>
                <label for="inputEmail3" class="control-label" align="left">of eligible wages
                    from</label>
            </div>
        </div>
    </div>





    {{-- <div class="col-md-12">
        <div class="form-group row">
            <label for="e_excl_amt" class="col-sm-3 control-label">Exclusions:
                Exclude the first</label>
            <div class="col-sm-3">
                <input type="number" class="form-control" id="e_excl_amt" name="e_excl_amt"
                    value="{{$clientannuation->e_excl_amt}}">
    @error('e_excl_amt')<span class="text-danger">{{$message}} </span>@enderror
    </div>
    <label for="inputEmail3" class="col-sm-4 control-label" align="left">of eligible
        wages from</label>
    </div>
    </div>
    <div class="col-md-6">
        <div class="form-group row">
            <label for="t_rate" class="col-sm-4 control-label">Equals</label>
            <div class="col-sm-8">
                <input type="number" class="form-control" id="t_rate" name="t_rate"
                    value="{{$clientannuation->t_rate}}">
                @error('t_rate')<span class="text-danger">{{$message}} </span>@enderror
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group row">
            <label for="t_tools" class="col-sm-4 control-label">Percent
                Per</label>
            <div class="col-sm-8">
                <select name="t_tools" id="t_tools" class="form-control">
                    <option value="BS" {{$clientannuation->t_tools == 'BS'?'selected':''}}>Basic
                        Sallary
                    </option>
                    <option value="GH" {{$clientannuation->t_tools == 'GH'?'selected':''}}>Gross
                        Hours</option>
                </select>
                @error('t_tools')<span class="text-danger">{{$message}} </span>@enderror
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group row">
            <label for="t_fix_amt" class="col-sm-4 control-label">Equals</label>
            <div class="col-sm-8">
                <input type="number" class="form-control" id="t_fix_amt" name="t_fix_amt"
                    value="{{$clientannuation->t_fix_amt}}">
                @error('t_fix_amt')<span class="text-danger">{{$message}} </span>@enderror
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group row">
            <label for="t_period" class="col-sm-4 control-label">Dollars
                Per</label>
            <div class="col-sm-8">
                <select name="t_period" id="t_period" class="form-control">
                    <option {{$clientannuation->t_period == 'Pay Period'?'selected':''}} value="Pay Period" selected>Pay
                        Period</option>
                    <option {{$clientannuation->t_period == 'Per Hours'?'selected':''}} value="Per Hours">Per
                        Hours</option>
                    <option {{$clientannuation->t_period == 'Per Month'?'selected':''}} value="Per Month">Per
                        Month</option>
                    <option {{$clientannuation->t_period == 'Per Forthnighty'?'selected':''}} value="Per Forthnighty">
                        Per Forthnighty</option>
                </select>
                @error('t_period')<span class="text-danger">{{$message}} </span>@enderror
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group row">
            <label for="t_excl_amt" class="col-sm-5 control-label">Threshold:
                Calculate once eligible wages of
                paid</label>
            <div class="col-sm-3">
                <input type="number" class="form-control" id="t_excl_amt" name="t_excl_amt"
                    value="{{$clientannuation->t_excl_amt}}">
                @error('t_excl_amt')<span class="text-danger">{{$message}} </span>@enderror
            </div>
            <label for="inputEmail3" class="col-sm-4 control-label" align="left">of eligible
                wages from</label>
        </div>
    </div> --}}


    <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-warning">Update</button>
    </div>
    </form>

    </div>
    </div>
    </div>
    </div>
    </div>
</section>
<!-- Page Content End -->


















<!-- Footer Start -->

<!-- Footer End -->

<!-- inline scripts related to this page -->
<script>
    $("#e_rate").on('keyup', e => {
        let e_rate = $("#e_rate").val();
        if (e_rate.length >= 1) {
            $('#e_fix_amt').attr('disabled', 'disabled');
        } else {
            $('#e_fix_amt').removeAttr('disabled', 'disabled');
        }
    });
    $("#e_fix_amt").on('keyup', e => {
        let e_rate = $("#e_fix_amt").val();
        if (e_rate.length >= 1) {
            $('#e_rate').attr('disabled', 'disabled');
        } else {
            $('#e_rate').removeAttr('disabled', 'disabled');
        }
    });

    $("#t_rate").on('keyup', e => {
        let e_rate = $("#t_rate").val();
        if (e_rate.length >= 1) {
            $('#t_fix_amt').attr('disabled', 'disabled');
        } else {
            $('#t_fix_amt').removeAttr('disabled', 'disabled');
        }
    });
    $("#t_fix_amt").on('keyup', e => {
        let e_rate = $("#t_fix_amt").val();
        if (e_rate.length >= 1) {
            $('#t_rate').attr('disabled', 'disabled');
        } else {
            $('#t_rate').removeAttr('disabled', 'disabled');
        }
    });
    $(document).ready(function () {
        $('#example').DataTable({
            "order": [
                [0, "asc"]
            ]
        });
    });
</script>

@stop
