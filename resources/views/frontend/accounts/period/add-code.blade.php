
<div style="padding-top:0px;">
@if ($errors->any())
<span class="text-danger">{{$errors->first()}}</span>
@endif
    <table width="100%" border="1" cellspacing="0" cellpadding="0">
        <tbody>
            <tr>
                <form id="amount_addForm" action="{{route('adtperiod.store')}}" method="post"
                    onsubmit="return validate()" name="datastore">
                    @csrf
                    <td style="width:12%">
                        <input type="text" id="date" class="form-control" name="date" style="width:100%"
                            value="{{ $period->end_date->format('d/m/Y')}}" tabIndex='1'>
                    </td>
                    <td style="width:10%">
                        <input type="hidden" name="clientId" value="{{$client->id}} ">
                        <input type="hidden" name="professionId" value="{{$profession->id}} ">
                        <input type="hidden" name="periodId" value="{{$period->id}} ">
                        <input type="hidden" name="fyear" value="{{$period->year}} ">
                        <input type="hidden" name="startDate" value="{{$period->start_date}} ">
                        <input type="hidden" name="endDate" value="{{$period->end_date}} ">
                        <input type="hidden" name="chartId" value="{{$client_account->code}} ">
                        <input type="hidden" name="code_id" value="{{$client_account->id}} ">
                        <input type="hidden" name="sub_cat_type" value="{{$client_account->type}} ">
                        <input type="hidden" name="gst_method" value="{{$client->gst_method}} ">
                        <input type="hidden" name="gst_code" value="{{$client_account->gst_code}} ">
                        <input type="hidden" name="is_gst_enabled" value="{{$client->is_gst_enabled}} ">
                        <input type="number" step="any" id="amount" class="form-control" name="amount" style="width:100%" placeholder=" Amount $" required tabIndex='2'>
                        <div id="msg"></div>
                    </td>
                    @if ($client_account->gst_code == 'GST' ||
                    $client_account->gst_code == 'CAP' ||
                    $client_account->gst_code == 'INP')
                    <td style="width:8%" id="gstfild">
                        <input class="gst form-control" step="any" type="number" id="gstamt" name="gstamt" style="width:100%" required placeholder=" GST $" tabIndex='3'>
                    </td>
                    <td style="width:9%" id="totalinvfild">
                        {{-- <div id="msginv" class=" text-danger" ></div> --}}
                        <input class="totalinv form-control" type="text" id="totalinv" name="totalinv"
                            style="width:100%" placeholder=" T/Inv $" tabIndex='4'  step="any">
                    </td>
                    @else
                    <td style="width:8%" id="gstfild">
                        <input class="gst form-control" type="number" id="gstamt" name="gstamt" style="width:100%"
                            placeholder=" GST $" tabIndex='3' disabled  step="any">
                    </td>
                    <td style="width:9%" id="totalinvfild">
                        {{-- <div id="msginv" class=" text-danger" ></div> --}}
                        <input class="totalinv form-control" type="text" id="totalinv" name="totalinv"
                            style="width:100%" placeholder=" T/Inv $" tabIndex='4' disabled  step="any">
                    </td>
                    @endif
                    <td style="width:11%" id="balancefiled">
                        <input class="balance form-control" type="text" id="balance" name="balance" style="width:100%"
                            placeholder=" Balance" disabled  step="any">
                    </td>
                    <td style="width:10%">
                        <input type="text" id="percentile" name="percentile" style="width:100%" class="form-control"
                            placeholder=" %" tabIndex='5'  step="any">
                    </td>
                    <td style="width:40%">
                        <input type="text" id="note" name="note" style="width:100%" class="form-control"
                            placeholder=" Note" tabIndex='6' required>
                    </td>
                    <td style="width:5%">
                        <button type="submit" class="valueempty_client width-100 btn btn-info btn-sm"
                            id="date_inv_submit" TabIndex='7'><i class="fa fa-arrow-right-from-bracket"></i></button>
                    </td>
            </tr>
        </tbody>
    </table>
    <div style="font-weight:16px; font-weight:800; color:red;">
        Total Entry = {{$data->count()}}
        <strong style="margin-left:80px;">Total Balance :$ {{ $balance}}
        </strong>
    </div>
    <div class="mgs mb-3"></div>
    <button type="submit" class="valueempty_client " id="date_inv_submit" style="padding:0px; display:none" tabIndex='7'></button>
    </form>
</div>

<script>
    $('input').on('keyup', function(e) {
        if($(this).attr('tabIndex') < 7){
            e.preventDefault()
        }else{
            e.which != 13 || $('[tabIndex=' + (+this.tabIndex + 1) + ']')[0].focus();
        }
    });
    function validate() {

        var amount = document.forms["datastore"]["amount"].value;
        var gst = document.forms["datastore"]["gstamt"].value;
        var tinv = document.forms["datastore"]["totalinv"].value;
        var percent = document.forms["datastore"]["percentile"].value;
        var gstmult = gst * 11;
        // if(amoun != '' && gst ==''){
        //   alert("All Fild are Empty");
        //   return false;
        // }
        if (gst != "" && tinv =='') {
            $("#totalinv").attr('required');
            alert("T/Inv Field Requierd");
            return false;
        }
        if(tinv != "" && gst == ""){
            $("#gstamt").attr('required');
            alert("GST Field Requierd");
            return false;
        }
        if(gst !='' && gst<0 && tinv > 0){
            alert("Use (-) Sing in T/Inv");
            return false;
        }
        if(gst<0 && gst !='' && gstmult <= tinv){
            alert("Bigger than " + gstmult);
            return false;
        }
        if(gst >0 && gst !='' && tinv <=  gstmult){
            alert("T/Inv Must be Bigger than " + gstmult);
            return false;
        }
        $("#date_inv_submit").attr('disabled', 'disabled');
    }
    $("#totalinv").on('keyup',function(e){
        let gstamt = $("#gstamt").val();
        let totalinv = $("#totalinv").val();
        let inv = gstamt * 11;
        if(totalinv < inv){
            $("#msginv").html('T/Inv must be bigger than '+inv);
        }
        if(totalinv >=inv || totalinv == ''){
            $("#msginv").html('');
        }
    });
    $("#amount").on('keyup',function(e){
        $("#balance").attr('disabled','disabled');
        $("#totalinv").attr('disabled','disabled');
        $("#gstamt").attr('disabled','disabled');
        if ($("#amount").val() =='') {
            $("#gstamt").removeAttr('disabled','disabled');
            $("#balance").removeAttr('disabled','disabled');
            $("#totalinv").removeAttr('disabled','disabled');
        }
    });
    $("#gstamt").on('keyup',function(e){
        $("#balance").attr('disabled','disabled');
        $("#amount").attr('disabled','disabled');
        if ($("#gstamt").val() =='') {
            $("#balance").removeAttr('disabled','disabled');
            $("#amount").removeAttr('disabled','disabled');
        }
    });
    $(".valueempty_client").on('click', function(){
        var amount = $("#amount").val();
        var gstamt = $("#gstamt").val();
        var totalinv = $("#totalinv").val();
        if(amount == ''){
            if(gstamt == ''){
                alert("Amount Can Not be empty!");
                $("#amount").focus();
                return false;
            } else {}
        }
    });
    $('.delete').click(function (){
        var answer = confirm("Are you sure delete this data?");
        if (answer) {
            return true;
        }else{
            return false;
        }
    });
</script>
