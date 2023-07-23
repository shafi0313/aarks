@extends('frontend.layout.master')
@section('title','Prepare Payroll')
@section('content')
<?php $p="pp"; $mp="payroll";?>

    <!-- Page Content Start -->
    <section class="page-content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <form action="{{route('prepayroll.emplist')}} " method="post" autocomplete="off">
                            @csrf
                            <input type="hidden" name="client_id" value="{{$client->id}}">
                            <input type="hidden" name="profession_id" value="{{$profession->id}}">
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label for="pay_period" class="t_b">Pay Period</label>
                                    <select required class="form-control" name="pay_period" id="pay_period">
                                        <option disabled selected value>Select Pay Period</option>
                                        <option value="Weekly">Weekly</option>
                                        <option value="Fortnightly">Fortnightly</option>
                                        <option value="Monthly">Monthly</option>
                                        <option value="Bonous">Bonous</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="payment_date" class="t_b">Payment Date</label>
                                    <input required type="date" class="form-control " id="payment_date" name="payment_date">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="pay_period_start" class="t_b">Pay Period Start</label>
                                    <input required type="date" class="form-control " id="pay_period_start" name="pay_period_start">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="pay_period_end" class="t_b">Pay Period End</label>
                                    <input required type="date" class="form-control " id="pay_period_end" name="pay_period_end">
                                </div>
                            </div>
                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="" class="t_b">Process Payroll &nbsp;&nbsp;</label>
                                        <label class="radio-inline mr-3">
                                            <input type="radio" name="process" id="process" value="all"> All Empolyee
                                        </label>
                                        <label class="radio-inline mr-3">
                                            <input type="radio" name="process" id="process" value="single"> Individual Empolyee
                                        </label>

                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <select class="form-control employecss" name="employeid" id="employeid" required>
                                            <option value="">Select Employee</option>
                                        </select>
                                    </div>
                                </div>

                                <div>
                                    <button type="submit" class="btn btn-info checkdate">Start Process</button>
                                </div>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Page Content End -->

    <!-- Footer Start -->

    <!-- Footer End -->

<script>
    $('.datepicker').datepicker({
        autoclose: true,
        todayHighlight: true
    });
    $('#pay_period').on('change',e=>{
        var pay_period = $('#pay_period').val();
        var client_id = '{{$client->id}}';
        var profession_id = '{{$profession->id}}';
        var peyurl = '{{route("prepayroll.payemp")}}';
        $.ajax({
            url:peyurl,
            type:"get",
            data:{
                pay_period:pay_period,
                client_id:client_id,
                profession_id:profession_id,
            },
            success:res=>{
                res = $.parseJSON(res);
                if(res.status == 200){
                    $('#employeid').html(res.html);
                }
            }
        });
    });
    $('input').on('change',e=> {
        var emplotye = $('input[name=process]:checked').val();
        if(emplotye == 'all'){
            $('.employecss').attr('disabled', 'disabled');
        }else{
            $('.employecss').removeAttr('disabled', 'disabled');
        }
    });
</script>

@stop
