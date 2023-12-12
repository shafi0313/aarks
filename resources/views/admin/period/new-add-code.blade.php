<div class="" style="padding-top:0px;">
    @if ($errors->any())
        <span class="text-danger">{{ $errors->first() }}</span>
    @endif
    <form id="addForm" action="{{ route('dataStore.store') }}" method="post" autocomplete="off">
        @csrf
        <input type="hidden" name="clientId" value="{{ $client->id }} ">
        <input type="hidden" name="professionId" value="{{ $professions->id }} ">
        <input type="hidden" name="periodId" value="{{ $period->id }} ">
        <input type="hidden" name="fyear" value="{{ $period->year }} ">
        <input type="hidden" name="startDate" value="{{ $period->start_date }} ">
        <input type="hidden" name="endDate" value="{{ $period->end_date }} ">
        <input type="hidden" name="chartId" value="{{ $sub_profession->code }} ">
        <input type="hidden" name="code_id" value="{{ $client_account->id }} ">
        <input type="hidden" name="sub_cat_type" value="{{ $sub_profession->type }} ">
        <input type="hidden" name="gst_method" value="{{ $client->gst_method }} ">
        <input type="hidden" name="gst_code" value="{{ $sub_profession->gst_code }} ">
        <input type="hidden" name="is_gst_enabled" value="{{ $client->is_gst_enabled }} ">
        <strong class="datelock"></strong>
        <table width="100%" border="1" cellspacing="0" cellpadding="0">
            <tbody>
                <tr>
                    <td style="width:12%">
                        <input type="text" id="date" class="form-control" name="date" style="width:100%"
                            value="{{ bdDate($period->end_date) }}">
                    </td>
                    <td style="width:10%">
                        <input type="text" id="amount" name="amount" style="width:100%" placeholder=" Amount $">
                    </td>
                    @if ($sub_profession->gst_code === 'GST' || $sub_profession->gst_code === 'CAP' || $sub_profession->gst_code === 'INP')
                        <td style="width:8%" id="gstfild">
                            <input class="gst form-control" type="number" id="gstamt" name="gstamt"
                                style="width:100%" placeholder=" GST $">
                        </td>
                        <td style="width:9%" id="totalinvfild">
                            <input class="totalinv form-control" type="text" id="totalinv" name="totalinv"
                                style="width:100%" placeholder=" T/Inv $">
                        </td>
                    @else
                        <td style="width:8%" id="gstfild">
                            <input class="gst form-control" type="number" id="gstamt" name="gstamt"
                                style="width:100%" placeholder=" GST $" disabled>
                        </td>
                        <td style="width:9%" id="totalinvfild">
                            <input class="totalinv form-control" type="text" id="totalinv" name="totalinv"
                                style="width:100%" placeholder=" T/Inv $" disabled>
                        </td>
                    @endif
                    <td style="width:10%" id="balancefiled">
                        <input class="balance" type="text" id="balance" name="balance" style="width:100%"
                            placeholder=" Balance" readonly>
                    </td>
                    <td style="width:10%">
                        <input type="text" id="percentile" name="percentile" style="width:100%"
                            placeholder=" %">
                    </td>
                    <td style="width:40%">
                        <input type="text" id="note" name="note" style="width:100%" placeholder=" Note">
                    </td>
                </tr>
            </tbody>
        </table>

        <div style="font-weight:16px; font-weight:800; color:red;">Total Entry = {{ $data->count() }} <strong
                style="margin-left:80px;">Total Balance : $ {{ $balance }}</strong></div>

        <div class="mgs"></div>
        <button type="submit" class="valueempty" id="date_inv_submit" style="padding:0px;"></button>
    </form>

</div>
{{-- <script>
    $('.gst').tooltip({
     placement: "right",
     trigger: "focus"
});
</script>
<script>
    $('.delete').click(function (){
        var answer = confirm("Are you sure delete this data?");
        if (answer) {
            return true;
        }else{
            return false;
        }
    });
    $(".valueempty").on('click', function(){
        var amount = $("#amount").val();
        var gstamt = $("#gstamt").val();
        var totalinv = $("#totalinv").val();
        if(amount == ''){
            if(gstamt == ''){
                alert("Amount Can Not be empty!");
                $("#amount").focus();
                return false;

            } else {
            }
        }
    });



    $("#totalinv" ).on('keypress', function(e) {
        var amount = $("#amount").val();
        var totalinv = $("#totalinv").val();
        if(amount == ''){
            if(totalinv == ''){
                if (e.keyCode == 13){
                    alert('Amount or total invoice must be entered!');
                    $("#gstamt").focus(1);
                    return false;
                }
            }

        }

    });

    $("#amount").on('keyup', function(){
        var amount = $(this).val();
        if(amount != ""){
            //$(".gst").css('display', 'none');
            //$(".totalinv").css('display', 'none');
            //$(".balance").css('display', 'none');
            //$("#gstamt").html('disabled', true);
            //$("#totalinvfild").html('', true);
            //$("#balancefiled").html('', true)
        } else {
            //$(".gst").css('display', 'block');
            //$(".totalinv").css('display', 'block');
            //$(".balance").css('display', 'block');
            //$("#gstamt").html('disabled', true);
            //$("#totalinvfild").html('<input type="text" class="totalinv"  id="totalinv" name="totalinv" style="width:100%" placeholder=" T/Inv $"/>', true);
            //$("#balancefiled").html('<input type="text" class="balance"  id="balance" name="balance" style="width:100%" placeholder=" Balance"/>', true)
        }
    });

    /*
    $("#gstamt").on('keyup', function(){
        var gstamt = $(this).val();
        var total = gstamt * 11;
        $("#amount").val(total);

    });
    $("#totalinv").on('keyup', function(){
        var totalinv = $(this).val();
        var amount = $("#amount").val();
        var balance = totalinv - amount;
        $("#balance").val(balance);

    });
    */


		$('input').on("keypress", function(e) {
            /* ENTER PRESSED*/
            if (e.keyCode == 13) {
                /* FOCUS ELEMENT */
                var inputs = $(this).parents("form").eq(0).find(":input");
                var idx = inputs.index(this);

                if (idx == inputs.length - 1) {
                    inputs[0].select()

                } else {
                    inputs[idx + 1].focus(); //  handles submit buttons
                    inputs[idx + 1].select();
                }
                return false;
            }
        });

    /*$("#date_inv").on('keyup', function(){
        var date_inv = $(this).val();
        var startdate = "1/08/2020";
        var enddate = "31/08/2020";

        var arr = enddate.split('/');
        var eday = arr[0];
        var emon = arr[1];
        var eyear = arr[2];

        var csarray = date_inv.split('/');
        csday = csarray[0];
        csmon = csarray[1];
        csyear = csarray[2];


        var strpo = startdate.split('/');
        spday = strpo[0];
        spmon = strpo[1];
        spyear = strpo[2];

        if(csyear >= spyear){
            if(csmon >= spmon){
                if(csyear == eyear){
                    if(csmon <= emon){
                        if(csday <= eday){
                            $(".mgs").text('');
                        } else {
                            $(".mgs").text('Day is wrong');
                            $(".mgs").css('color', 'red');
                            return false;
                        }
                    }else {
                        $(".mgs").text('Month is wrong');
                        $(".mgs").css('color', 'red');
                        return false;
                    }
                }else {
                    $(".mgs").text('Year is wrong');
                    $(".mgs").css('color', 'red');
                    return false;
                }
            } else {
                $(".mgs").text('Month is wrong');
                $(".mgs").css('color', 'red');
                return false;
            }
        } else {
            $(".mgs").text('Year is wrong');
            $(".mgs").css('color', 'red');
            return false;
        }
    });*/



   /* $("#date_inv_submit").on('click', function(){
        var date_inv = $("#date_inv").val();
        var startdate = "1/08/2020";
        var enddate = "31/08/2020";

        var arr = enddate.split('/');
        var eday = arr[0];
        var emon = arr[1];
        var eyear = arr[2];

        var csarray = date_inv.split('/');
        csday = csarray[0];
        csmon = csarray[1];
        csyear = csarray[2];


        var strpo = startdate.split('/');
        spday = strpo[0];
        spmon = strpo[1];
        spyear = strpo[2];

        if(spyear <= csyear){
            if(csyear <= eyear){
                var start_yar = 2015;
                var end_yar = 2016;
                if(spyear<csyear){
                    if(csmon <= emon){

                    } else {
                    alert("Month is Wrong!");
                    return false;
                    }
                } else {
                    if(csmon >= spmon){
                    }else {
                        alert("Month is Wrong!");
                        return false;
                    }
                }
            } else {
                alert("Year is Wrong!");
                return false;
            }
        } else{
            alert("Year is Wrong!");
            return false;
        }
    });*/


    $("#addForm").on('submit',(e)=>{
        // $("#addForm").trigger("reset");
    });
</script> --}}
<script>
    /*
    $("#addForm").submit(function(e)
    {
        var date_inv = $('#date_inv').val().split('/');
        var invoice_date = date_inv[2]+ "-" +date_inv[1]+ "-" +date_inv[0];
        var datalock = '';


        if(invoice_date <= datalock){
            $('.datelock').text('your enter data period is locked, check administration');
            $('.datelock').css('color','red');
            return false;
        }else{
            $('.datelock').text('');
            var postData = $(this).serializeArray();
            var formURL = $(this).attr("action");
            $.ajax(
                {
                    url : formURL,
                    timeout: 1000,
                    type: "POST",
                    async:false,
                    crossDomain:true,
                    data : postData,
                    success:function(data){
                        $("#amount").val("");
                        $("#gstamt").val("");
                        $("#totalinv").val("");
                        $("#balance").val("");
                        $("#percentile").val("");
                        $("#note").val("");
                        $("#datatale").html(data);
                        //$("#amount").cursor();
                        //$('#amount').focus();
                        $('#date_inv').focus();
                    }
                });
        }
        e.preventDefault();
    });

    $(document).on("click", ".deletedata", function(e)
    {
        var id 		= $(this).attr("data-id");
        var invoice_date = $(this).attr('data-date');
        var datalock = '';
        if(invoice_date <= datalock){
            $('.datelockd').text('your enter data period is locked, check administration');
            $('.datelockd').css('color','red');
            return false;
        }else{
            $('.datelockd').text('');
            var formURL = "https://www.aarks.com.au/AddPriod/addDatadetailsdelete";
            $.ajax(
            {
                url : formURL,
                type: "POST",
                data :{id: id},
                success:function(data){
                $("#datatale").html(data);
                }
            });
        }
        e.preventDefault();
    });
    $("#amount").on('keyup', function(){
        var amount = $(this).val();
        if(amount != ""){

            //$(".gst").css('display', 'none');

        } else {
            //$(".gst").css('display', 'block');
        }

    });
    */

    function ConfirmDelete() {
        return confirm('Are you sure delete this data?');
    }
</script>
