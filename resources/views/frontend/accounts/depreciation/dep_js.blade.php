<script>
    const token = $('meta[name="csrf-token"]').attr('content');
    readAssetName();
    $('#addAssetName').on('submit', (e)=>{
        e.preventDefault();
        $.ajax({
            url: '{{route("client.dep_name.store")}}',
            type: "post",
            data: {
                _token       : token,
                asset_name   : $("#subname").val(),
                dep_id       : "{{$dep->id}}",
                client_id    : "{{$dep->client_id}}",
                profession_id: "{{$dep->profession_id}}"
            },
            success: (res)=> {
                readAssetName();
                $("form").trigger("reset");
                $("#assetNameSave").removeAttr('disabled');
            }
        });
    });
    function readAssetName(){
        let profession_id = "{{$dep->id}}";
        $.ajax({
            url:'{{route("client.dep_name.read")}}',
            method:'get',
            data:{
                dep_id:"{{$dep->id}}"
            },
            success: function (res) {
                if (res.status == 'success') {
                    $('#readAssetName').html(res.html);
                    $("#dep_subgrup").html(res.dep_asset_name);
                    $("#old_asset_name").html(res.old_asset_name);
                }
            }
        });
    }

    // Edit data
    // $('.table').on('click', function (e) {
    //     e.preventDefault();
    //     let anchor = $(e.target).parent('.btn');
    //     let id = anchor.attr('data-id');
    //     if (anchor.hasClass('btn')) {
    //         getRecord(anchor.attr('id'), id);
    //     }
    // });
    function getRecord(actionName, id) {
        $.ajax({
            url: '{{route("client.dep_name.edit")}}',
            method: 'get',
            data: { id: id },
            success: function (response) {
                response = $.parseJSON(response);
                if (response.status == 'success') {
                    if (actionName == 'update') {
                        let modal = $('#update-modal');
                        let form = modal.find('.form');
                        form.find('#up_asset_name').val( response.data['asset_name']);
                        form.find('#updateId').val( response.data['id']);

                        modal.modal('show');

                    } else if (actionName == 'delete') {
                        // console.log(modal.html());
                    }
                }
            }
        });
    }


    $('body').on('click', '._delete', function(e){
        let modal = $('#delete-modal');
        let form = modal.find('.form');
        $(".assetDeleteForm").attr('action', $(this).data('route'));
        modal.modal('show');
    });
    $('#update-modal').on('submit', (e)=>{
        e.preventDefault();
        $.ajax({
            url: '{{route("client.dep_name.update")}}',
            type: "post",
            data: {
                _token       : token,
                asset_name: $("#up_asset_name").val(),
                id        : $("#updateId").val(),
            },
            success: (res)=> {
                res = $.parseJSON(res)
                readAssetName();
                $("form").trigger("reset");
                $('#update-modal').modal('hide');
            }
        });
    });

    $("#purchasedate").on('keyup',(e)=>{
        let financial_year = $("#financial_year").val();
        let date = $("#purchasedate").val();
        date = date.split('/').reverse().join('-');
        let start_year  = (financial_year-15)+'-01-01';
        let end_year  = (financial_year)+'-31-12';
        if(date.length >= 10){
            if (start_year <= date && end_year>= date) {
                console.log('In Between');
            } else {
                $('#taxMsg').show().html('Date Must between '+start_year+' TO '+end_year);
            }
        }else{
            $('#taxMsg').hide();
        }
    });

	$('#purchasedateee').on('change', function(){
        var paymentdate = $(this).val();
        var clientid    = $('#clientid').val();
        var peyurl = "https://www.aarks.com.au/Record_journal_entry/yearCloseCheck";
        $.ajax({
            url:peyurl,
            type:"POST",
            data:{paymentdate:paymentdate, clientid:clientid},
            success:function(data){
            //$('#employeid').html(data);
                if(data=='available'){
                        $('.checkdate').attr('disabled', 'disabled');
                        $('.txtmsg').text('You have selected closed financial year! Please select open financial year.');
                        $('.txtmsg').css({ 'color': 'red', 'font-size': '100%' });
                        //alert('You have selected closed financial year please select open fiancial year.');
                        // location.reload();
                } else if(data=='ok'){
                        $('.checkdate').attr('disabled', 'disabled');
                        $('.txtmsg').text('You have selected closed financial year! Please select open financial year.');
                        $('.txtmsg').css({ 'color': 'red', 'font-size': '100%' });
                        //alert('You have selected closed financial year please select open fiancial year.');
                        //location.reload();
                } else if(data=='outOfRange'){
                        $('.checkdate').attr('disabled', 'disabled');
                        $('.txtmsg').text('You have selected closed financial year! Please select open financial year.');
                        $('.txtmsg').css({ 'color': 'red', 'font-size': '100%' });
                        // alert('You have selected closed financial year please select open fiancial year.');
                        //location.reload();
                } else {
                        $('.checkdate').removeAttr('disabled', 'disabled');
                        $('.txtmsg').text('');
                }
            }
        });
	});

	$(document).on("click", ".assetRowID", function(){
			var id 	= $(this).attr("data-id");
			$('#myModal2').modal('show');
			$('#depID').val(id);
	});

	$('#deprPass').on('keyup', function(e){
		var deprPass = $(this).val();
		var checkUrl = "https://www.aarks.com.au/Input_desprecation_sch/depPassCheck";
		$.ajax({
			url:checkUrl,
			type:"POST",
			data:{deprPass:deprPass},
			success:function(data){
			    if(data == 'no'){
					$('.datamgs2').html('Password did not match');
					$('.btnOff').attr('disabled', 'disabled');
				}
			 if(data == 'ok'){

					  $('.datamgs2').html('');
					  $(".btnOff").attr("disabled", false);
				}

			}
		});
	 });




    $('#disposal_dates').on('blur', function(){
        var disposal_dates = $(this).val();
        var asset_id 	   = $('#asset_id').val();
        var finshian_year  = $('#finshian_year').val();
        var validate = findyear(disposal_dates, finshian_year);

        if(validate != 0){
            var add_just_amunt = $('#add_just_amunt').val();
            var serurl = "https://www.aarks.com.au/Input_desprecation_sch/deprationamount";
            $.ajax({
                url:serurl,
                type:"POST",
                data:{disposal_dates:disposal_dates, asset_id:asset_id},
                dataType:"json",
                success:function(data){
                    $('#first_debit').val(data.credit_amount + +add_just_amunt);
                    $(".wrongmgs").text("");
                }
            });
        }else{
            $(".wrongmgs").text("Entered Financial year is already closed OR new Financial year is not opened yet!");
            $('#disposal_dates').focus();

        }

    });

    function findyear(disposal_dates, finshian_year)
    {
        var disposedate = disposal_dates.split('/');
        var month 		= disposedate[1];
        if(month >= 7){
            var year = +disposedate[2]+1;
        }else{
            var year = disposedate[2];
        }
        if(year == finshian_year){
            return 1;
        }else{
            return 0;
        }
    }

    // Update Asset Name Value
    $("#asset_entryvalue").submit(function(e)
    {
        e.preventDefault();
        $('.loading').css('display', 'block');
        var postData = $(this).serializeArray();
        var formURL = $(this).attr("action");

        $.ajax({
            url : formURL,
            //timeout: 1000,
            type: "put",
            //async:false,
            //crossDomain:true,
            data : postData,
            success:function(res){
                res = $.parseJSON(res)
                if (res.status == '200'){
                    toast('success','Insert Successful');
                    $("form").trigger("reset");
                    $('.loading').css('display', 'none');
                    readAssetName();
                }else if (res.status == '500'){
                    toast('error',"You can't insert more than one time");
                    // $("form").trigger("reset");
                    $('.loading').css('display', 'none');
                }else if (res.status == '403'){
                    alert('Your enter data period is locked, check administration');
                    toast('error',"Your enter data period is locked, check administration");
                    // $("form").trigger("reset");
                    $('.loading').css('display', 'none');
                }else {
                    toast('error','Something is wrong');
                    // $("form").trigger("reset");
                    $('.loading').css('display', 'none');
                }
            }

        });
    });

    $('#assetupdate').submit(function(e){
        var postData = $(this).serializeArray();
        var formURL = $(this).attr("action");
        var purchase_dates = $('#purchase_dates').val().split("/");
        var disposal_dates = $('#disposal_dates').val().split("/");
        var formdate = purchase_dates[2]+ "-" +purchase_dates[1]+ "-" +purchase_dates[0];
        var todate = disposal_dates[2]+ "-" +disposal_dates[1]+ "-" +disposal_dates[0];
        if(todate < formdate){
            alert("Invalid Date Range");
            return false;
            }
            else{

            $.ajax(
            {
            url : formURL,
            type: "POST",
            data : postData,
            success:function(data){
            location.reload();
            }

            });
        }
		e.preventDefault();
	});

	$('#asset_editaction').submit(function(e){
			var postData = $(this).serializeArray();
			var formURL = $(this).attr("action");
			$.ajax(
			{
				url : formURL,
				type: "POST",
				data : postData,
				success:function(data){
					location.reload();
				}
			});


		e.preventDefault();
	});

	$('.green').on('click', function(e){
		var id = $(this).attr('data-id');
		var url = "https://www.aarks.com.au/Input_desprecation_sch/assetedit";
		$.ajax({
			url:url,
			type:"POST",
			data:{id:id},
			dataType:'json',
			success:function(data){
				$('#modal-form').modal('show');
				$('#assetid').val(data.id);
				$('#assetedit').val(data.subname);
			}
		});


		e.preventDefault();
	});

	$('.assetdelete').on('click', function(e){
		var assetid = $(this).attr('data-id');
		var deleteurl = "https://www.aarks.com.au/Input_desprecation_sch/assetdelete";
		$.ajax({
			url:deleteurl,
			type:"POST",
			data:{assetid:assetid},
			success:function(data){
				if(data == 1){
					$('.delegemgs').text('Deprection /Assets data exist cannot be deleted.');
					return false;
				}else{
					location.reload();
				}

			}
		});

		e.preventDefault();
	});

	// purchase date culculate start
    $("#purchaseprince").on('keyup', function(){
        var depreciation_method = $('#depreciation_method').val();
        if(depreciation_method == 'D'){
            diminishing();
        }else if(depreciation_method == 'P'){
            primecost();
        }else{
            write_of();
        }

    });

    $('#balancingin').on('keyup', function(){
        var depreciation_method = $('#depreciation_method').val();
        if(depreciation_method == 'D'){
            diminishing();
        }else if(depreciation_method == 'P'){
            primecost();
        }else{
            write_of();
        }
    });

    $('#private_use').on('keyup', function(){
        var depreciation_method = $('#depreciation_method').val();
        if(depreciation_method == 'D'){
            diminishing();
        }else if(depreciation_method == 'P'){
            primecost();
        }else{
            write_of();
        }
    });

    $('#depreciation_rate').on('keyup', function(){
        var depreciation_method = $('#depreciation_method').val();
        if(depreciation_method == 'D'){
            diminishing_rate();
        }else if(depreciation_method == 'P'){
            primecost_rate();
        }else{
            write_of();
        }
    });

    $('#effectivelife').on('keyup', function(){
        var depreciation_method = $('#depreciation_method').val();
        if(depreciation_method == 'D'){
            diminishing();
        }else if(depreciation_method == 'P'){
            primecost();
        }else{
            write_of();
        }
    });

    $("#purchasedate").on('blur', function(){
        var depreciation_method = $('#depreciation_method').val();
        if(depreciation_method == 'D'){

            diminishing();

        }else if(depreciation_method == 'P'){

            primecost();

        }else{

            write_of();

        }
    });

    $("#depreciation_method").on('change', function(){
        var depreciation_method = $(this).val();
        if(depreciation_method == 'D'){
            diminishing();
        }else if(depreciation_method == 'P'){
            primecost();
        }else{
            write_of();
        }

    });
    function diminishing()
    {

        var final_day = minusday();
        var purchaseprince = parseInt($('#purchaseprince').val());
        var balancingin = $('#balancingin').val();
        if(balancingin != ""){
            var adjustvalue = balancingin;
        }else{
            var adjustvalue = 0;
        }

        var effectivelife = $('#effectivelife').val();
        var depriction_rate = $('#depreciation_rate').val();

        var percentive 	  = 200;

        if(effectivelife != ""){
            var evelive = +effectivelife;
            var finalpercenntige = percentive / evelive;
        }else{
            var evelive = 0;
            var finalpercenntige = +depriction_rate;
        }

        $('#originalvalue').val(purchaseprince.toFixed(2));
        $('#actual_owdv').val((purchaseprince - adjustvalue).toFixed(2));
        var actutal_owdv = purchaseprince - adjustvalue;
        $('#undeducted_owdv').val((purchaseprince - adjustvalue).toFixed(2));
        $("#depreciation_rate").val(finalpercenntige.toFixed(2));
        var deprication_per = actutal_owdv / 100 * finalpercenntige;
        var financial_year = $('#financial_year').val();
        var leave = leavyearjust(financial_year);
        if(leave !=0){
            var perday_value = deprication_per / 365;
        }else{
            var perday_value = deprication_per / 366;
        }
        var actural_dereciation = perday_value * final_day;

        var private_use = $('#private_use').val();
        if(private_use != ''){
            var private_perchent = actural_dereciation / 100 * +private_use;
            var final_depricition = actural_dereciation - private_perchent;

            $('#actual_depreciation').val(final_depricition.toFixed(2));
            $('#undeducted_depreciation').val(actural_dereciation.toFixed(2));
            $('#actual_cwdv').val((actutal_owdv - final_depricition).toFixed(2));
            $('#undeducted_cwdv').val((actutal_owdv - actural_dereciation).toFixed(2));

            $('#net_depreciation').val(final_depricition.toFixed(2));
            $('#net_shareof_depreciation').val(final_depricition.toFixed(2));

        }else{
            $('#actual_depreciation').val(actural_dereciation.toFixed(2));
            $('#undeducted_depreciation').val(actural_dereciation.toFixed(2));
            $('#actual_cwdv').val((actutal_owdv - actural_dereciation).toFixed(2));
            $('#undeducted_cwdv').val((actutal_owdv - actural_dereciation).toFixed(2));

            $('#net_depreciation').val(actural_dereciation.toFixed(2));
            $('#net_shareof_depreciation').val(actural_dereciation.toFixed(2));
        }
    };
    function diminishing_rate() // diminishing rate
    {

        var final_day = minusday();
        var purchaseprince = parseInt($('#purchaseprince').val());
        var balancingin = $('#balancingin').val();
        if(balancingin != ""){
            var adjustvalue = balancingin;
        }else{
            var adjustvalue = 0;
        }

        var effectivelife    = $('#effectivelife').val();
        var depriction_rate  = $('#depreciation_rate').val();
        var finalpercenntige = +depriction_rate;


        $('#originalvalue').val(purchaseprince.toFixed(2));
        $('#actual_owdv').val((purchaseprince - adjustvalue).toFixed(2));
        var actutal_owdv = purchaseprince - adjustvalue;
        $('#undeducted_owdv').val((purchaseprince - adjustvalue).toFixed(2));
        var deprication_per = actutal_owdv / 100 * finalpercenntige;

        var financial_year = $('#financial_year').val();

        var leave = leavyearjust(financial_year);
        if(leave !=0){
            var perday_value = deprication_per / 365;
        }else{
            var perday_value = deprication_per / 366;
        }

        var actural_dereciation = perday_value * final_day;

        var private_use = $('#private_use').val();
        if(private_use != ''){
            var private_perchent = actural_dereciation / 100 * +private_use;
            var final_depricition = actural_dereciation - private_perchent;

            $('#actual_depreciation').val(final_depricition.toFixed(2));
            $('#undeducted_depreciation').val(actural_dereciation.toFixed(2));
            $('#actual_cwdv').val((actutal_owdv - final_depricition).toFixed(2));
            $('#undeducted_cwdv').val((actutal_owdv - actural_dereciation).toFixed(2));

            $('#net_depreciation').val(final_depricition.toFixed(2));
            $('#net_shareof_depreciation').val(final_depricition.toFixed(2));


        }else{
            $('#actual_depreciation').val(actural_dereciation.toFixed(2));
            $('#undeducted_depreciation').val(actural_dereciation.toFixed(2));
            $('#actual_cwdv').val((actutal_owdv - actural_dereciation).toFixed(2));
            $('#undeducted_cwdv').val((actutal_owdv - actural_dereciation).toFixed(2));

            $('#net_depreciation').val(actural_dereciation.toFixed(2));
            $('#net_shareof_depreciation').val(actural_dereciation.toFixed(2));
        }
    };
    function primecost()
    {

        var final_day = minusday();
        var purchaseprince = parseInt($('#purchaseprince').val());
        var balancingin = $('#balancingin').val();
        if(balancingin != ""){
            var adjustvalue = balancingin;
        }else{
            var adjustvalue = 0;
        }

        var effectivelife = $('#effectivelife').val();
        var percentive 	  = 100;
        if(effectivelife != ""){
            var evelive = +effectivelife;
        }else{
            var evelive = 0;
        }
        var finalpercenntige = percentive / evelive;


        $('#originalvalue').val(purchaseprince.toFixed(2));
        $('#actual_owdv').val((purchaseprince - adjustvalue).toFixed(2));
        $('#undeducted_owdv').val((purchaseprince - adjustvalue).toFixed(2));
        $("#depreciation_rate").val(finalpercenntige.toFixed(2));
        var actutal_owdv = purchaseprince - adjustvalue;
        var deprication_per = purchaseprince / 100 * finalpercenntige;


        var financial_year = $('#financial_year').val();

        var leave = leavyearjust(financial_year);
        if(leave !=0){
            var perday_value = deprication_per / 365;
        }else{
            var perday_value = deprication_per / 366;
        }

        var actural_dereciation = perday_value * final_day;

        var private_use = $('#private_use').val();
        if(private_use != ''){
            var private_perchent = actural_dereciation / 100 * +private_use;
            var final_depricition = actural_dereciation - private_perchent;
            $('#actual_depreciation').val(final_depricition.toFixed(2));
            $('#undeducted_depreciation').val(actural_dereciation.toFixed(2));
            $('#actual_cwdv').val((actutal_owdv - final_depricition).toFixed(2));
            $('#undeducted_cwdv').val((actutal_owdv - actural_dereciation).toFixed(2));

            $('#net_depreciation').val(final_depricition.toFixed(2));
            $('#net_shareof_depreciation').val(final_depricition.toFixed(2));


        }else{
            $('#actual_depreciation').val(actural_dereciation.toFixed(2));
            $('#undeducted_depreciation').val(actural_dereciation.toFixed(2));
            $('#actual_cwdv').val((actutal_owdv - actural_dereciation).toFixed(2));
            $('#undeducted_cwdv').val((actutal_owdv - actural_dereciation).toFixed(2));

            $('#net_depreciation').val(actural_dereciation.toFixed(2));
            $('#net_shareof_depreciation').val(actural_dereciation.toFixed(2));
        }


    };
    function primecost_rate(){

        var final_day = minusday();
        var purchaseprince = parseInt($('#purchaseprince').val());
        var balancingin = $('#balancingin').val();
        if(balancingin != ""){
            var adjustvalue = balancingin;
        }else{
            var adjustvalue = 0;
        }

        var depriction_rate  = $('#depreciation_rate').val();
        var finalpercenntige = +depriction_rate;

        $('#originalvalue').val(purchaseprince.toFixed(2));
        $('#actual_owdv').val((purchaseprince - adjustvalue).toFixed(2));
        $('#undeducted_owdv').val((purchaseprince - adjustvalue).toFixed(2));
        var actutal_owdv = purchaseprince - adjustvalue;
        var deprication_per = actutal_owdv / 100 * finalpercenntige;


        var financial_year = $('#financial_year').val();

        var leave = leavyearjust(financial_year);
        if(leave !=0){
            var perday_value = deprication_per / 365;
        }else{
            var perday_value = deprication_per / 366;
        }

        var actural_dereciation = perday_value * final_day;
        var private_use = $('#private_use').val();
        if(private_use != ''){
            var private_perchent = actural_dereciation / 100 * +private_use;
            var final_depricition = actural_dereciation - private_perchent;
            $('#actual_depreciation').val(final_depricition.toFixed(2));
            $('#undeducted_depreciation').val(actural_dereciation.toFixed(2));
            $('#actual_cwdv').val((actutal_owdv - final_depricition).toFixed(2));
            $('#undeducted_cwdv').val((actutal_owdv - actural_dereciation).toFixed(2));

            $('#net_depreciation').val(final_depricition.toFixed(2));
            $('#net_shareof_depreciation').val(final_depricition.toFixed(2));


        }else{
            $('#actual_depreciation').val(actural_dereciation.toFixed(2));
            $('#undeducted_depreciation').val(actural_dereciation.toFixed(2));
            $('#actual_cwdv').val((actutal_owdv - actural_dereciation).toFixed(2));
            $('#undeducted_cwdv').val((actutal_owdv - actural_dereciation).toFixed(2));

            $('#net_depreciation').val(actural_dereciation.toFixed(2));
            $('#net_shareof_depreciation').val(actural_dereciation.toFixed(2));

        }
    };
    function write_of(){

        var final_day = minusday();
        var purchaseprince = parseInt($('#purchaseprince').val());
        var balancingin = $('#balancingin').val();
        if(balancingin != ""){
            var adjustvalue = balancingin;
        }else{
            var adjustvalue = 0;
        }



        $('#originalvalue').val(purchaseprince.toFixed(2));
        $('#actual_owdv').val((purchaseprince - adjustvalue).toFixed(2));
        $('#undeducted_owdv').val((purchaseprince - adjustvalue).toFixed(2));




        var private_use = $('#private_use').val();
        if(private_use != ''){

            var deprication_amount = purchaseprince - adjustvalue;
            var private_perchent = deprication_amount / 100 * +private_use;
            var final_depricition = deprication_amount - private_perchent;

            $('#actual_depreciation').val(final_depricition.toFixed(2));
            $('#undeducted_depreciation').val((purchaseprince - adjustvalue).toFixed(2));
            $('#actual_cwdv').val((0).toFixed(2));
            $('#undeducted_cwdv').val((0).toFixed(2));

            $('#net_depreciation').val(final_depricition.toFixed(2));
            $('#net_shareof_depreciation').val(final_depricition.toFixed(2));

        }else{
            $('#actual_depreciation').val((purchaseprince - adjustvalue).toFixed(2));
            $('#undeducted_depreciation').val((purchaseprince - adjustvalue).toFixed(2));
            $('#actual_cwdv').val((0).toFixed(2));
            $('#undeducted_cwdv').val((0).toFixed(2));
            $('#net_depreciation').val((purchaseprince - adjustvalue).toFixed(2));
            $('#net_shareof_depreciation').val((purchaseprince - adjustvalue).toFixed(2));
        }
    };
    function leavyearjust(year){
        return year%4;
    };
	function minusday(){
			var purchasedate = $("#purchasedate").val();
			var financial_year = $('#financial_year').val();

			var fulldate = purchasedate.split('/');
			var day   = fulldate[0]-1;
			var month = fulldate[1];
			var year  = fulldate[2];
			var leave = leavyearjust(financial_year);

			if(financial_year == year){
				if(month > 6){
					alert("incorrect financial year");
					return 0;
				}
			}


			var finan_shialyar = +financial_year - 1;
			if(finan_shialyar > year){

				if(leave != 0){
					var final_total_day = 365;
				}else{
					var final_total_day = 366;
				}
				return final_total_day;
			}else if(finan_shialyar == year){
				if(month < 7){

					if(leave != 0){
					var final_total_day = 365;
					}else{
						var final_total_day = 366;
					}
					return final_total_day;
				}
			}




			var july 		= 31;
			var aug  		= 31;
			var sectormber = 30;
			var octorbar 	= 31;
			var novber 	= 30;
			var december 	= 31;
			var junuary 	= 31;
			if(leave != 0){
			var february 	= 28;
			}else{
			var february 	= 29;
			}

			var martch 	= 31;
			var appril 	= 30;
			var may 	= 31;
			var jun    	= 30;

			if(leave != 0){
			var first_number  = 365;
			var secend_number = 334;
			var thard_number  = 303;
			var fourth_number = 273;
			var five_number   = 242;
			var six_number    = 212;
			var seven_number  = 181;
			var eight_number  = 150;
			var nine_number   = 122;
			var ten_number    = 91;
			var elevent_number = 61;
			var twelve_number  = 30;

		}else{
			var first_number  = 366;
			var secend_number = 335;
			var thard_number  = 304;
			var fourth_number = 274;
			var five_number   = 243;
			var six_number    = 213;
			var seven_number  = 182;
			var eight_number  = 151;
			var nine_number   = 122;
			var ten_number    = 91;
			var elevent_number = 61;
			var twelve_number  = 30;
		}


		if(month == 7){

			var deprication_count = 12;
			var first_cal  	   = july - day;
			var totalday 	   = first_number;

		}else if(month == 8){

			var first_cal  	   = aug- day;
			var deprication_count = 11;
			var totalday = first_number - july;

		}else if(month == 9){
			var first_cal  	   = sectormber - day;
			var deprication_count = 10;
			var totalday 		   = secend_number - aug;
		}else if(month == 10){
			var first_cal  	   = octorbar  - day;
			var deprication_count = 9;
			var totalday = thard_number - sectormber;

		}else if(month == 11){
			var first_cal  	   = novber  - day;
			var deprication_count = 8;
			var totalday = fourth_number - octorbar;

		}else if(month == 12){
			var first_cal  	   = december  - day;
			var deprication_count = 7;
			var totalday = five_number - novber;
		}else if(month == 1){
			var first_cal  	   = junuary  - day;
			var deprication_count = 6;
			var totalday = six_number - december;

		}else if(month == 2){
			var first_cal  	   = february  - day;
			var deprication_count = 5;
			var totalday = seven_number - junuary;


		}else if(month == 3){
			var first_cal  	   = martch  - day;
			var deprication_count = 4;
			var totalday   = eight_number - february; // 28 Feb

		}else if(month == 4){
			var first_cal  	   = appril  - day;
			var deprication_count = 3;
			var totalday = nine_number - martch;

		}else if(month == 5){
			var first_cal  	   = may  - day;
			var deprication_count = 2;
			var totalday = ten_number - appril;
		}else if(month = 6){
			var first_cal  	   = twelve_number  - day;
			var deprication_count = 1;
			var totalday = elevent_number - may;
		}

		var final_total_day = totalday - day;

		return final_total_day;

	};

    // purchase date end
    $('.chekloc').on('click', function(){
        var start_date = $('#purchasedate').val().split('/');
        var invoice_date = start_date[2]+ "-" +start_date[1]+ "-" +start_date[0];
        var datalock = '';
        if(invoice_date <= datalock){
            //$('.datelock').text('your enter data period is locked, check administration');
            //$('.datelock').css('color','red');
            //return false;
        }else{
            //$('.datelock').text('');
        }
    });

    /*==================
    === Asset Disposal
    ===================*/
    function getDisposal(){
        $.get('{{route('client.dep_disposal.getAsset')}}',{
            client_id    : {{$dep->client_id}},
            profession_id: {{$dep->profession_id}},
            dep_id       : {{$dep->id}},
        }, res=>{
            $(".disposal_content").html(res.data);
        });
    }
    function getDisposalModal(id){
        $.get('{{route('client.dep_disposal.getModal')}}',{
            id:id
        }, res=>{
            $("#disposable_ajax_modal").html(res.modal);
            $("#disposemodal").modal('show');
        });
    }

    function getDisposalAmount(target,id,url){
        const date = target.value;
        if(date.length==10){
            $.get(url, {
                purchase_date: $('#purchase_date').val(),
                disposal_date: date,
                asset        : id,
            },(res)=>{
                if(res.status == 500){
                    $('.wrongmgs').html(res.message);
                }else{
                    $('.wrongmgs').html('');
                }
                $("#dep_debit").val(res.dep_amt.toFixed(2));
            });
        }
    }



    // ================
    // ===Asset Rollover/Reinstated
    // =================
    $("#rollover_year").on('change', function(){
        let client_id     = $('#rollover_year option:selected').data('client_id');
        let profession_id = $('#rollover_year option:selected').data('profession_id');
        let depreciation_id = $('#rollover_year option:selected').data('depreciation_id');
        let year          = $(this).val();
        $.get('{{route("rollover.get_asset")}}',{
            client_id      : client_id,
            profession_id  : profession_id,
            depreciation_id: depreciation_id,
            year           : year,
        },(res)=>{
            $("#rollover_asset").html(res.opt)
        });
    });
    $("#reinstated_year").on('change', function(){
        let client_id       = $('#reinstated_year option:selected').data('client_id');
        let profession_id   = $('#reinstated_year option:selected').data('profession_id');
        let depreciation_id = $('#reinstated_year option:selected').data('depreciation_id');
        let year            = $(this).val();
        $.get('{{route("reinstated.get_asset")}}',{
            client_id    : client_id,
            profession_id: profession_id,
            depreciation_id: depreciation_id,
            year         : year,
        },(res)=>{
            if(res.status == 403){
                toast('warning', res.msg);
            } else{
                $("#reinstated_asset").html(res.opt);
            }
        });
    });
    function rolloverCheck(type, value){
        let rollover   = $("#rollover").find('input, select');
        let reinstated = $("#reinstated").find('input, select');
        if(type == 'rollover'){
            reinstated.attr('disabled', 'disabled');
            rollover.removeAttr('disabled');
        } else{
            rollover.attr('disabled', 'disabled');
            reinstated.removeAttr('disabled');
        }
        if( value == '' || value == 'undefined'){
            rollover.removeAttr('disabled');
            reinstated.removeAttr('disabled');
        }
    }
</script>
