@extends('frontend.layout.master')
@section('title','Manage Wages')
@section('content')
<?php $p="mw"; $mp="payroll";?>
    <!-- Page Content Start -->
    <section class="page-content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <form id="clientWages" action="{{route('wages.update',$clientWages->id)}} " method="post" autocomplete="off">
                                @csrf
                                @method('put')
                            <div class="form-group">
                                <label class="t_b">Name: </label><strong class="t_red">*</strong>
                                <input class="form-control form-control-sm" name="name" type="text" value="{{$clientWages->name}}">
                                <input type="hidden" name="client_id" value="{{client()->id}} ">
                            </div>
                            <div class="form-group">
                                <label for="" class="t_b">Type: </label><strong class="t_red">*</strong>
                                <select class="form-control form-control-sm" name="type" >
                                    <option value="Salary" {{$clientWages->type=='Salary'?'selected':''}}>Salary</option>
                                    <option value="Hourly" {{$clientWages->type=='Hourly'?'selected':''}}>Hourly</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="link_group">Link Income Group<strong style="color:red;">*</strong></label>
                                <select class="form-control" name="link_group" id="link_group">
                                    <option {{$clientWages->link_group == 1?'selected':''}} value="1">Salary/Wages</option>
                                    <option {{$clientWages->link_group == 2?'selected':''}} value="2">Overtime</option>
                                    <option {{$clientWages->link_group == 3?'selected':''}} value="3">Bonus and Commission</option>
                                    <option {{$clientWages->link_group == 4?'selected':''}} value="4">Allowance</option>
                                    <option {{$clientWages->link_group == 5?'selected':''}} value="5">Director’s Fees </option>
                                    <option {{$clientWages->link_group == 6?'selected':''}} value="6">Lump Sum Tuple</option>
                                    <option {{$clientWages->link_group == 7?'selected':''}} value="7">ETP Tuple </option>
                                    <option {{$clientWages->link_group == 8?'selected':''}} value="8">CDEP</option>
                                    <option {{$clientWages->link_group == 9?'selected':''}} value="9">Salary Secriface</option>
                                    <option {{$clientWages->link_group == 10?'selected':''}} value="10">Exempt Foreign Income</option>
                                </select>
                            </div>
                            <div class="row">
                                <div class="col-sm-5">
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="regularrate" id="regularrate" value="regular"{{$clientWages->regular_rate != ''?'checked':''}}>
                                            Regular Rate Multiplied by:
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-7">
                                    <div class="form-group">
                                        <input class="form-control form-control-sm" type="number"  name="regular_rate" id="regularrateval" value="{{$clientWages->regular_rate}}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-5">
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="regularrate" id="regularrate" value="fixed" {{$clientWages->hourly_rate != ''?'checked':''}}>
                                            Fixed Hourly Rate of:
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-7">
                                    <div class="form-group">
                                        <input class="form-control form-control-sm" type="number" name="hourly_rate" id="fixedhourlyval" value="{{$clientWages->hourly_rate}}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="checkbox col-md-12">
                                    <label>
                                        <input type="checkbox" name="automatically" id="automatically" value="1"> Automatically Adjust Base Hourly or Base Salary Details
                                    </label>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success float-right btn3">Save</button>
                            </form>
                            <div class="card-heading mt-5">
                                <p></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Page Content End -->
    <!-- inline scripts related to this page -->
    <!-- Footer -->

    <!-- /Footer -->
    <!-- inline scripts related to this page -->
        <script>
$('#clientWages input').on('change', (e)=> {
    var radiovalue = $('input[name=regularrate]:checked', '#clientWages').val();
    if(radiovalue == 'regular'){
    $('#regularrateval').removeAttr('disabled', 'disabled');
    $('#fixedhourlyval').attr('disabled', 'disabled');
    $('#fixedhourlyval').val(0);
    } else{
    $('#regularrateval').val(0);
    $('#regularrateval').attr('disabled', 'disabled');
    $('#fixedhourlyval').removeAttr('disabled', 'disabled');
    }
});
        </script>
@stop
