{{--================
    ADD PAYG
=================--}}
<style>
    .label-lg.arrowed-in-right,
    .label-lg.arrowed-right {
        margin-right: 6px;
    }

    .label.arrowed-in-right,
    .label.arrowed-right {
        position: relative;
        z-index: 1;
    }

    .badge-pink,
    .badge.badge-pink,
    .label-pink,
    .label.label-pink {
        background-color: #D6487E;
    }

    .label.arrowed-in-right,
    .label.arrowed-right {
        margin-right: 5px;
    }

    .label-lg {
        padding: .3em .6em .4em;
        font-size: 13px;
        line-height: 1.1;
        height: 24px;
    }

    .label {
        line-height: 1.15;
        height: 20px;
    }

    .label {
        color: #FFF;
        display: inline-block;
    }

    .badge.no-radius,
    .btn.btn-app.no-radius>.badge.no-radius,
    .btn.btn-app.radius-4>.badge.no-radius,
    .label {
        border-radius: 0;
    }

    .badge,
    .label {
        font-size: 12px;
    }

    .badge,
    .label {
        font-weight: 400;
        background-color: #ABBAC3;
        text-shadow: none;
    }






    .label-xlg.arrowed-right:after {
    right: -14px;
    border-width: 14px 7px;
}
.label.arrowed-right:after {
    right: -10px;
    border-width: 10px 5px;
}
.label-primary.arrowed-right:after {
    border-left-color: #428BCA;
}







element.style {
}
.label-xlg.arrowed-in-right, .label-xlg.arrowed-right {
    margin-right: 7px;
}
.label.arrowed-in-right, .label.arrowed-right {
    position: relative;
    z-index: 1;
}
.badge-primary, .badge.badge-primary, .label-primary, .label.label-primary {
    background-color: #428BCA;
}
.label.arrowed-in-right, .label.arrowed-right {
    margin-right: 5px;
}
.label-xlg {
    padding: .3em .7em .4em;
    font-size: 14px;
    line-height: 1.3;
    height: 28px;
}
.label {
    line-height: 1.15;
    height: 20px;
}
.label {
    color: #FFF;
    display: inline-block;
}
.badge.no-radius, .btn.btn-app.no-radius>.badge.no-radius, .btn.btn-app.radius-4>.badge.no-radius, .label {
    border-radius: 0;
}
.badge, .label {
    font-size: 12px;
}
.badge, .label {
    font-weight: 400;
    background-color: #ABBAC3;
    text-shadow: none;
}
</style>
<div class="row" style="background:#2894FF; color:white; padding:3px; font-size:18px;margin:0 5px" align="center">
    <div class="col-md-7">
        <h4 style="display: inline-block; float: left;">
            <span class="label label-lg label-pink arrowed-right" style="font-weight: 900; ">
                Add PAYG Installment ( Check BAS Paper from ATO/ Ask Tax Agent)
            </span>
            <span class="label label-lg label-inverse"
                style="font-weight: 900; cursor: pointer; color: black; background: orange;" data-toggle="modal"
                data-target="#addPayg" id="paygbtn">
                <i class="ace-icon glyphicon glyphicon-plus-sign"></i> Add PAYG
            </span>
        </h4>
    </div>
    <div class="col-md-5">
        <div style="float: right;">
            <h4 style="display: inline-block; float: left;">
                <span class="label label-lg label-inverse "
                    style="font-weight: 900; cursor: pointer; color: black; background: orange;" data-toggle="modal"
                    data-target="#taxCredit">
                    <i class="ace-icon glyphicon glyphicon-plus-sign"></i> Add Fuel tax Credit
                </span>&nbsp;
                <span class="label label-lg label-inverse arrowed-in arrowed-right">
                    Total = $ {{$fuelLtr->sum('amount')}} </span>
                <span class="label label-lg label-inverse arrowed-in arrowed-right">
                    Total LTR = $ {{$fuelLtr->sum('ltr')}}</span>&nbsp;
            </h4>
        </div>
    </div>

</div>

<div class="row" style="margin:0 5px">
    <div class="col-md-12">
        <div class="row" style="border:1px solid #CCCCCC; min-height:150px;">
            <p style="padding:10px;">
                <b>Electronic Lodgment Declaration: </b><br>
                This declaration is to be completed where a taxpayer elects to use an
                approved ATO electronic
                channel. It is the responsibility of the taxpayer to retain this
                declaration for a period of
                five years after the declaration is made, penalties may apply for
                failure to do so. <br>
                <b>Privacy: </b><br>
                The ATO is authorised by the Taxation Administration Act 1953 to request
                your tax file number
                (TFN). We will use your TFN to identify you in our records. It is not an
                offence not to provide
                your TFN. However, you cannot lodge your income tax form electronically
                if you do not quote your
                TFN. Taxation law authorises the ATO to collect information and to
                disclose it to other
                government agencies. For information about your privacy go to
                ato.gov.au/privacy <br>
                <b>Electronic Funds Transfer - Direct Debit:</b><br>
                Where you have requested an EFT direct debit some of your details will
                be provided to your
                financial institution and the Tax Office's sponsor bank to facilitate
                the payment of your
                taxation liability from your nominated account <br>
                <b>Important:</b><br>
                The tax law imposes heavy penalties for giving false or misleading
                information. <br>
                <b>I Declare That:</b>
                the information provided to my registered tax agent for the preparation
                of this tax return,
                including any applicable schedules is true and correct, and the agent is
                authorised to lodge
                this TAX / BAS / GST return.<br>
                <b>Tax file number : </b> ABN :52167460657 , Year : 2017 Period :
                01/07/2016 - 31/03/2017
            </p>
            <button class="btn btn-primary" disabled="">Submit</button>
        </div>
    </div>
</div>



<!-- Add Payg Modal -->
<form id="payg_form" action="{{route('payg.store')}} " method="POST" enctype="multipart/form-data" class=" form-inline">
    @csrf
    <div class="modal fade" id="addPayg" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6>
                        Add PAYG Installment {{ $period->end_date->format(aarks('frontend_date_format'))}}
                    </h6>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body row">
                    <div class="col-lg-5">
                        <div class="form-group">
                            <input type="hidden" name="clientId" value="{{$client->id}} ">
                            <input type="hidden" name="periodId" value="{{$period->id}} ">
                            <label for="payg_percenttige">Percent</label>
                            <input type="text" class="form-control payg_percenttige" name="payg_percenttige" id="payg_percenttige" required="required" value="" placeholder="Enter Percentage">
                        </div>
                    </div>
                    <div style="padding-top: 30px; margin-left: 18px">
                        OR
                    </div>
                    <div class="col-lg-5">
                        <div class="form-group">
                            <label for="payg_amount">Amount</label>
                            <input type="text" class="form-control payg_amount" id="payg_amount" name="payg_amount" placeholder="Enter Amount" required="required" value="">
                        </div>
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
<!-- Tax Credit Model -->
<div id="taxCredit" class="modal fade" tabindex="-1" data-backdrop="static">
    <form id="fueltaxform" action="{{route('fuel.store')}} " method="POST" enctype="multipart/form-data" class="form-inline">
        @csrf
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header justify-content-center">
                    <div class="">
                        <h5 class="modal-title" style="padding: 10px 0">
                            <span class="text-warning label-primary mr-3" style="padding: 3px 5px">
                                Current Rate: @if($fuel) {{$fuel->rate===''?0:$fuel->rate}}@endif
                            </span>
                            <span class="label-pink mr-2" style="padding: 3px 5px">Fuel
                                Tax Credit</span>
                            <span class="text-warning label-primary" style="padding: 3px 5px"">Date:
                                {{ $period->end_date->format(aarks('frontend_date_format'))}}
                            </span>
                        </h5>
                    </div>
                    <div class="">
                        <button style="padding: 5px 0 0 0%; margin:0;" type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="exampleInputName2">Date</label>
                                <input type="" class="form-control"
                                    value="{{ $period->end_date->format(aarks('frontend_date_format'))}}" name="date"
                                    id="datepicker" autocomplete="off">
                                <small id="taxMsg" style="display: none;color: red">Message</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="exampleInputName2">LTR.</label>
                                <input type="text" class="form-control" id="ltr" name="ltr" placeholder="LTR...">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group" style="padding-top:25px;">
                                <input type="hidden" name="clientId" value="{{$client->id}} ">
                                <input type="hidden" name="periodId" value="{{$period->id}} ">
                                <input type="hidden" id="startDate"
                                    value="{{\Carbon\Carbon::parse($period->start_date)->format('d-m-Y')}} ">
                                <input type="hidden" id="endDate"
                                    value="{{\Carbon\Carbon::parse($period->end_date)->format('d-m-Y')}} ">
                                <button type="submit" class="ltrvalidate btn btn-primary savebtn"
                                    id="ltrBtn">Submit</button>
                            </div>
                        </div>

                    </div>
                    <!--- form vertical ---->
                    <br>
                    <div id="data_info_clinet" align="center">
                        <table class="table table-sm table-striped table-bordered table-hover item-table itrTable">
                            <thead>
                                <tr>
                                    <th>Date </th>
                                    <th>LTR </th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Total Amount: </td>
                                    <td>Total LTR: </td>
                                    <td>Total LTR: </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </form>
</div>
<script>
readData();
readPayg();
// PAYG CAL
$("#payg_percenttige").on('keyup', function(){
    var payg_percenttige = $(this).val();
        $("#payg_amount").attr('disabled', 'disabled');
        $("#payg_amount").val('');
    if(payg_percenttige ==''){
        $("#payg_amount").removeAttr('disabled', 'disabled');
    }
});
$("#payg_amount").on('keyup', function(){
    var payg_amount = $(this).val();
        $("#payg_percenttige").attr('disabled', 'disabled');
        $("#payg_percenttige").val('');
    if(payg_amount ==''){
        $("#payg_percenttige").removeAttr('disabled', 'disabled');
    }
});

$('#payg_form').on('submit', function (e) {
    e.preventDefault();
    form = $(this);
    if (checForm(form)) {
        $.ajax({
            url: form.attr('action'),
            method: form.attr('method'),
            data: form.serialize(),
            success: function (msg) {
                if (msg == 1){
                    alert('success');
                    $("form").trigger("reset");
                    $("#addPayg .close").click();
                }else {
                    alert('error');
                    $("form").trigger("reset");
                }
                readPayg();
            }
        });
    } else {
        alert('error');
    }
});
function readPayg(){
    let client_id = "{{$client->id}}";
    let period_id = "{{$period->id}}";
    let form = $("form");
    $.ajax({
        url:"{{route('payg.index')}}",
        method: 'get',
        data:{
            client_id:client_id,
            period_id:period_id
        },
        success: function (msg) {
            msg = $.parseJSON(msg);
            if (msg.status == 'success') {
                if(msg.data['percent'] !== ''){
                    form.find("#payg_percenttige").val(msg.data['percent']);
                }
                if(msg.data['amount'] !== ''){
                    form.find("#payg_amount").val(msg.data['amount']);
                }
            }
        }
    });
}
// END PAYG

$("#datepicker").keyup(function(){
    let date = $("#datepicker").val();
    let startDate = $("#startDate").val();
    let endDate = $("#endDate").val();
    if(date.length == 10){
        if (startDate <= date && endDate >= date) {
            console.log('In Between');
        } else {
            $('#taxMsg').show().html('Date Must between '+startDate+' TO '+endDate);
            console.log('In NOt Between');
        }
    }else{
        $('#taxMsg').hide();
    }
});


$('#fueltaxform').on('submit', function (e) {
    e.preventDefault();
    form = $(this);
    if (checForm(form)) {
        $.ajax({
            url: form.attr('action'),
            method: form.attr('method'),
            data: form.serialize(),
            success: function (msg) {
                if (msg == 1){
                    alert('success');
                    $("form").trigger("reset");
                    $("#taxCredit .close").click();
                }else {
                    alert('error');
                    $("form").trigger("reset");
                }
                readData();
                console.log(msg);
            }
        });
    } else {
        alert('error');
    }
});
function checForm(form) {
    let inputList = form.find('input');
    for (let i = 0; i < inputList.length; i++) {
        if (inputList[i].value==='' || inputList[i].value===null ||
        inputList[i].value===' ' ) {
            return false;
        } else {
            return true;
        }
    }
}

function readData(){
    let client_id = "{{$client->id}}";
    let period_id = "{{$period->id}}";
    $.ajax({
        url:"{{route('fuel.index')}}",
        method: 'get',
        data:{
            client_id:client_id,
            period_id:period_id
        },
        success: function (data) {
            data = $.parseJSON(data);
            if (data.status == 'success') {
                $('.itrTable').html(data.html);
            }
        }
    });
}
</script>
