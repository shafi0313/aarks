@extends('admin.layout.master')
@section('title','Client')
@section('content')

<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="#">Home</a>
                </li>

                <li class="active">Tax Calculator</li>
            </ul><!-- /.breadcrumb -->

            <div class="nav-search" id="nav-search">
                <form class="form-search">
                    <span class="input-icon">
                        <input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input"
                            autocomplete="off" />
                        <i class="ace-icon fa fa-search nav-search-icon"></i>
                    </span>
                </form>
            </div><!-- /.nav-search -->
        </div>

        <div class="page-content">
            

            <div class="row">
                <div class="col-xs-12">
                    <!-- PAGE CONTENT BEGINS -->
                    <br>
                    <div class="row">
                        <div class="col-md-3"></div>
                        	<div class="col-md-6">                            
                                <div class="row"> 

									<!-- Nav tabs -->
									<ul class="nav nav-tabs" role="tablist">
										<li role="presentation" class="active">
										<a href="#individual" aria-controls="individual" role="tab" data-toggle="tab">Tax for an Individual</a></li>
										<li role="presentation">
										<a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Days between dates</a></li>                                        
									</ul>
								
									<!-- Tab panes -->
									<div class="tab-content" style="min-height:350px;">
										<div role="tabpanel" class="tab-pane active" id="individual">
											<form class="form-inline">
												<div class="row" style="padding-top:10px;">
													<div class="col-xs-3" align="right">
														<label class="checkbox-inline">
															<input type="checkbox" value="">Resident
														</label>
													</div>
												
													<div class="col-xs-3" align="right">
														<strong>2019-2020</strong>
													</div>
													<div class="col-xs-1"></div>
													<div class="col-xs-3" align="right">
														<strong>2020-2021</strong>
													</div>
												</div>
												<div class="row" style="padding-top:3px;">
													<div class="col-xs-3" align="right">Taxable income</div>
												
													<div class="col-xs-3">
														<input type="text" class="form-control" name="f_incme" id="f_incme">
													</div>
													<div class="col-xs-1"></div>
													<div class="col-xs-4">
														<input type="text" class="form-control" name="s_incme" id="s_incme">
													</div>
												</div>
												
												<div class="row" style="padding-top:10px;">
												
													<div class="col-xs-3" align="right">Tax free part</div>
												
													<div class="col-xs-3">
														<input type="text" id="to_amount_1" name="to_amount_1" class="form-control" value="18200.00">
													</div>
													<div class="col-xs-1"></div>
													<div class="col-xs-4">
														<input type="text" id="to_amount_6" name="to_amount_6" class="form-control" value="18200.00">
													</div>
												</div>
												
												<div class="row" style="padding-top:10px;">
													<div class="col-xs-3" align="right">
														<strong>Tax payable</strong>
													</div>
												
													<div class="col-xs-3">
														<input type="text" class="form-control" id="f_tax_payable">
													</div>
													<div class="col-xs-1"></div>
													<div class="col-xs-4">
														<input type="text" class="form-control" id="r_tax_payable">
													</div>
												</div>
												
												<div class="row" style="padding-top:10px;">
													<div class="col-xs-3" align="right">Low inc. offset</div>
												
													<div class="col-xs-3">
														<input type="text" class="form-control" id="lowincoffset" name="lowincoffset">
													</div>
													<div class="col-xs-1"></div>
													<div class="col-xs-4">
													<input type="text" class="form-control" id="r_lowincoffest" name="r_lowincoffest">
													</div>
												</div>
												
												<div class="row" style="padding-top:10px;">
												
													<div class="col-xs-3" align="right">
														<strong>Sub-total</strong>
													</div>
												
													<div class="col-xs-3">
														<input type="text" class="form-control" id="sub_total" name="sub_total">
													</div>
													<div class="col-xs-1"></div>
													<div class="col-xs-4">
														<input type="text" class="form-control" id="r_sub_total" name="r_sub_total">
													</div>
												</div>
												
												<div class="row" style="padding-top:10px;">
													<div class="col-xs-3" align="right">Medicare levy</div>
													<div class="col-xs-3">
														<input type="text" class="form-control" id="medicare_levy_show" name="medicare_levy_show">
													</div>
													<div class="col-xs-1"></div>
													<div class="col-xs-4">
														<input type="text" class="form-control" id="r_medicare_levy_show" name="r_medicare_levy_show">
													</div>
												</div>
												
												<div class="row" style="padding-top:10px;">
													<div class="col-xs-3" align="right">
														<strong>Total</strong>
													</div>
													<div class="col-xs-3">
														<input type="text" class="form-control" id="total" name="total">
													</div>
													<div class="col-xs-1"></div>
													<div class="col-xs-4">
														<input type="text" class="form-control" id="r_total" name="r_total">
													</div>
												</div>
												
												<div class="row" style="padding-top:10px;">
													<div class="col-xs-5" align="right">
														<strong>Tax Payable</strong>
													</div>
												
													<div class="col-xs-3">
														<input type="text" class="form-control" id="full_final_tax_payable" name="full_final_tax_payable">
													</div>
												</div>
												
												<div class="row" style="padding-top:10px;">
													<div class="col-xs-5" align="right"></div>
													<div class="col-xs-3">
														<button type="button" class="btn btn-primary calculate right_calculate final_calclute">Calculate</button>
													</div>
													
													<div class="col-xs-3">
														<a class="btn btn-primary" href="{{ route('admin.dashboard') }}">Close</a>
													</div>
												</div>
											</form>
										</div>
										
										<div role="tabpanel" class="tab-pane" id="profile">
											<form>
												<div class="col-md-12">
													<div class="col-md-4">
														<div class="form-group" align="center">
															<label for="exampleInputEmail1"><b>Date</b></label>
															<input type="text" class="form-control" id="date1">
														</div>
													</div>
													<div class="col-md-4">
														<div class="form-group" align="center">
															<label for="exampleInputEmail1"><b>To Date</b></label>
															<input type="text" class="form-control" id="to_date1">
														</div>
													</div>
													<div class="col-md-4">
														<div class="form-group" align="center">
															<label for="exampleInputEmail1"><b>Days</b></label>
															<input type="text" class="form-control" id="days1">
														</div>
													</div>
												</div>
														
														
												<div class="col-md-12">
													<div class="col-md-4">
														<div class="form-group" align="center">
															<input type="text" class="form-control" id="date2">
														</div>
													</div>
													<div class="col-md-4">
														<div class="form-group" align="center">
															<input type="text" class="form-control" id="to_date2">
														</div>
													</div>
													<div class="col-md-4">
														<div class="form-group" align="center">
															<input type="text" class="form-control" id="days2">
														</div>
													</div>
												</div>
													
													
													
													
												<div class="col-md-12">
													<div class="col-md-4">
														<div class="form-group" align="center">
															<input type="text" class="form-control" id="date3">
														</div>
													</div>
													<div class="col-md-4">
														<div class="form-group" align="center">
															<input type="text" class="form-control" id="to_date3">
														</div>
													</div>
													<div class="col-md-4">
														<div class="form-group" align="center">
															<input type="text" class="form-control" id="days3">
														</div>
													</div>
												</div>
													
												<div class="col-md-12">
													<div class="col-md-4">
														<div class="form-group" align="center">
														</div>
													</div>
													<div class="col-md-4">
														<div class="form-group" align="right">
															<b>Total</b>
														</div>
													</div>
													<div class="col-md-4">
														<div class="form-group" align="center">
															<input type="text" class="form-control" id="total_days">
														</div>
													</div>
												</div>
											</form>
										</div>
									</div>
                                </div>
							</div>
						</div>
					

                	<!-- PAGE CONTENT ENDS -->
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.page-content -->
    </div>
</div><!-- /.main-content -->
								
																	
								
<script type="text/javascript">
	$('.calculate').on('click', function(){
		var f_incme = $('#f_incme').val();
		
		var form_amount_1 	 = "0.00";
		var to_amount_1 	 = $('#to_amount_1').val();
		
		var rate_1 			 = "0.00";
		var form_amount_2 	 = "18201.00";
		var to_amount_2 	 = "37000.00";
		var rate_2 			 = "19.00";
		var form_amount_3 	 = "37001.00";
		var to_amount_3 	 = "87000.00";
		
		var rate_3 			 = "32.50";
		var form_amount_4 	 = "87001.00";
		var to_amount_4		 = "180000.00";
		var rate_4			 = "37.00";
		var form_amount_5 	 = "180001.00";
		
		var to_amount_5 	 = "99999999.00";
		var rate_5 			 = "45.00";		
		
		var low_income_offset = '37000.00';
		var offset_amount = '445.00';
		var reduce_by_rate = '1.50';
		
		if(form_amount_1 <=+ f_incme && to_amount_1 >=+ f_incme){
			
				$('#f_tax_payable').val(rate_1);
			
		}else if(form_amount_2 <=+ f_incme && to_amount_2 >=+ f_incme){
			var inputvalue = f_incme - to_amount_1;
			var final_payable = inputvalue*rate_2/100;
			$('#f_tax_payable').val(final_payable);
			
		} else if(form_amount_3 <=+ f_incme && to_amount_3 >=+ f_incme){
			var thard_stape = f_incme - to_amount_2;
			var fist_secend_stape = f_incme - thard_stape;
			var secend_stape       = fist_secend_stape - to_amount_1;
			var send_payable 	   = secend_stape*rate_2/100;
			var final_payable = thard_stape*rate_3/100;
			var thard_final_pay = final_payable + send_payable;
			$('#f_tax_payable').val(thard_final_pay);
		
		}else if(form_amount_4 <=+ f_incme && to_amount_4 >=+ f_incme){			
			var fourth_stape 				= f_incme - to_amount_3;
			var fist_secend_thard_stape     = f_incme - fourth_stape;
			var secend_stape      			 = to_amount_2 - +to_amount_1;
			var thard_stape					= +fist_secend_thard_stape - +to_amount_2;
			var send_payable 				= secend_stape*rate_2/100;
			var thard_pay					= thard_stape*rate_3/100;
			var fourth_payable = fourth_stape*rate_4/100;
			var fourth_final_pay = +fourth_payable + +thard_pay + +send_payable;
			//alert(fourth_stape);
			$('#f_tax_payable').val(fourth_final_pay);
		
		} else if(form_amount_5 <=+ f_incme && to_amount_5 >=+ f_incme){		
			var five_stape 				    	  = f_incme - to_amount_4;
			var fist_secend_thard_fouth_stape     = f_incme - five_stape;
			var secend_stape      			 	  = to_amount_2 - +to_amount_1;
			var thard_stape                       = to_amount_3 - to_amount_2;
			var fouth_stape						  = +fist_secend_thard_fouth_stape - +to_amount_3;
			
			var send_payable 					  = secend_stape*rate_2/100;
			var thard_payable 					  = thard_stape*rate_3/100;
			var fouth_pay						  = fouth_stape*rate_4/100;
			var five_payable 					  = five_stape*rate_5/100;
			var fourth_final_pay 				  = +five_payable + +fouth_pay + +thard_payable + +send_payable;

			//alert(fourth_stape);
			$('#f_tax_payable').val(fourth_final_pay);
		 }else {
		 
		 }
		
		if(low_income_offset <= f_incme ){		
			var lowincomecalulate = f_incme - low_income_offset;
			var tax_calulate = lowincomecalulate/100*reduce_by_rate;
			
			var final_value = offset_amount - +tax_calulate;		
	
			if(final_value < 0){
				$('#lowincoffset').val(0);
			 }else {
				 $('#lowincoffset').val(final_value); 
			 }
			var f_tax_payable = $('#f_tax_payable').val();
			
			if(final_value < 0){
			var final_sub_toal = f_tax_payable;
			} else {
			var final_sub_toal = f_tax_payable - +final_value;
			}
			
			if(final_sub_toal < 0){
			$('#sub_total').val(0);
			} else {
			$('#sub_total').val(final_sub_toal);
			}			
		} else {		
				 if(offset_amount < 0){
					$('#lowincoffset').val(0);
				}else{
					$('#lowincoffset').val(offset_amount);
				}
				
				var f_tax_payable = $('#f_tax_payable').val();
				
				if(offset_amount < 0){
					var final_sub_toal = f_tax_payable;
				} else {
					var final_sub_toal = f_tax_payable - offset_amount;
				}
				
				if(final_sub_toal < 0){
				$('#sub_total').val(0);
				} else {
				$('#sub_total').val(final_sub_toal);
				}
		}		
		
		//var m_levy_threshold 
		//var m_leavy 
		//var mls_threshold
		//var mls
		var m_levy_threshold   = '21655.00';
		var m_levy_threshold_end = '27068.00';
		var m_leavy 	     	= '10.00';
		var m_leavy_1 	= '2.00';
		
		var m_levy_threshold_1 	= '27069.00';
		
		var mls_threshold 	= '80000.00';
		var mls 	= '2.50';		
		
		if(m_levy_threshold < f_incme){			
			if(m_levy_threshold_end > f_incme){
				var lowarincome = f_incme - m_levy_threshold;
				var final_med_levy = lowarincome/100*m_leavy;
				$('#medicare_levy_show').val(final_med_levy.toFixed(2));
			} else {
				var final_med_levy = f_incme/100*m_leavy_1;
				$('#medicare_levy_show').val(final_med_levy.toFixed(2));
			}
			
		}else {
			$('#medicare_levy_show').val(0);
		}		
				
		var sub_total 	  = $('#sub_total').val();
		var medicare_levy_show =  $('#medicare_levy_show').val();
		var sub_total_medicare		  = +sub_total + +medicare_levy_show;
		$('#total').val(sub_total_medicare);
	});	
</script>


<script type="text/javascript">
	$('.right_calculate').on('click', function(){
		var f_incme = $('#s_incme').val();
		
		var form_amount_1 	 = "0.00";
		var to_amount_1 	 = $('#to_amount_6').val();
		
		var rate_1 			 = "0.00";
		var form_amount_2 	 = "18201.00";
		var to_amount_2 	 = "37000.00";
		var rate_2 			 = "19.00";
		var form_amount_3 	 = "37001.00";
		var to_amount_3 	 = "87000.00";
		
		var rate_3 			 = "32.50";
		var form_amount_4 	 = "87001.00";
		var to_amount_4		 = "180000.00";
		var rate_4			 = "37.00";
		var form_amount_5 	 = "180001.00";
		
		var to_amount_5 	 = "999999.00";
		var rate_5 			 = "45.00";
		
		
		var low_income_offset = '37000.00';
		var offset_amount = '445.00';
		var reduce_by_rate = '1.50';
		
		
		
		var m_levy_threshold 	 = '21655.00';
		var m_leavy 			 = '10.00';
		var m_levy_threshold_end = '27068.00';
		
		var mls_threshold = '80000.00';
		var mls = '2.50';


		
		if(form_amount_1 <=+ f_incme && to_amount_1 >=+ f_incme){			
				$('#r_tax_payable').val(rate_1);
			
		}else if(form_amount_2 <=+ f_incme && to_amount_2 >=+ f_incme){
			var inputvalue = f_incme - to_amount_1;
			var final_payable = inputvalue*rate_2/100;
			$('#r_tax_payable').val(final_payable);
			
		} else if(form_amount_3 <=+ f_incme && to_amount_3 >=+ f_incme){
			var thard_stape = f_incme - to_amount_2;
			var fist_secend_stape = f_incme - thard_stape;
			var secend_stape       = fist_secend_stape - to_amount_1;
			var send_payable 	   = secend_stape*rate_2/100;
			var final_payable = thard_stape*rate_3/100;
			var thard_final_pay = final_payable + send_payable;
			$('#r_tax_payable').val(thard_final_pay);
		
		}else if(form_amount_4 <=+ f_incme && to_amount_4 >=+ f_incme){			
			var fourth_stape 				= f_incme - to_amount_3;
			var fist_secend_thard_stape     = f_incme - fourth_stape;
			var secend_stape      			 = to_amount_2 - +to_amount_1;
			var thard_stape					= +fist_secend_thard_stape - +to_amount_2;
			var send_payable 				= secend_stape*rate_2/100;
			var thard_pay					= thard_stape*rate_3/100;
			var fourth_payable = fourth_stape*rate_4/100;
			var fourth_final_pay = +fourth_payable + +thard_pay + +send_payable;
			//alert(fourth_stape);
			$('#r_tax_payable').val(fourth_final_pay);
		
		} else if(form_amount_5 <=+ f_incme && to_amount_5 >=+ f_incme){		
			var five_stape 				    	  = f_incme - to_amount_4;
			var fist_secend_thard_fouth_stape     = f_incme - five_stape;
			var secend_stape      			 	  = to_amount_2 - +to_amount_1;
			var thard_stape                       = to_amount_3 - to_amount_2;
			var fouth_stape						  = +fist_secend_thard_fouth_stape - +to_amount_3;
			
			var send_payable 					  = secend_stape*rate_2/100;
			var thard_payable 					  = thard_stape*rate_3/100;
			var fouth_pay						  = fouth_stape*rate_4/100;
			var five_payable 					  = five_stape*rate_5/100;
			var fourth_final_pay 				  = +five_payable + +fouth_pay + +thard_payable + +send_payable;
			//alert(fourth_stape);
			$('#r_tax_payable').val(fourth_final_pay);
		 }else {
		 
		 	
		 
		 
		 }
		
		if(low_income_offset <= f_incme ){		
			var lowincomecalulate = f_incme - low_income_offset;
			var tax_calulate = lowincomecalulate/100*reduce_by_rate;
			
			var final_value = offset_amount - +tax_calulate;
		
	
			if(final_value < 0){
				$('#r_lowincoffest').val(0);
			 }else {
				 $('#r_lowincoffest').val(final_value); 
			 }
			var f_tax_payable = $('#r_tax_payable').val();
			
			if(final_value < 0){
			var final_sub_toal = f_tax_payable;
			} else {
			var final_sub_toal = f_tax_payable - +final_value;
			}
			
			// start now
			
			if(final_sub_toal < 0){
			$('#r_sub_total').val(0);
			} else {
			$('#r_sub_total').val(final_sub_toal);
			}
			
		} else {
		
				 if(offset_amount < 0){
					$('#r_lowincoffest').val(0);
				}else{
					$('#r_lowincoffest').val(offset_amount);
				}
				
				var f_tax_payable = $('#r_tax_payable').val();
				
				if(offset_amount < 0){
					var final_sub_toal = f_tax_payable;
				} else {
					var final_sub_toal = f_tax_payable - offset_amount;
				}

				if(final_sub_toal < 0){
				$('#r_sub_total').val(0);
				} else {
				$('#r_sub_total').val(final_sub_toal);
				}
		}
		
		//var m_levy_threshold 
		//var m_leavy 
		//var mls_threshold
		//var mls
		// start now
		if(m_levy_threshold < f_incme){
			if(mls_threshold < f_incme){
				var towperchtive = +m_leavy + +mls;
				var secend_m_p = f_incme/100*m_leavy;
				$('#r_medicare_levy_show').val(secend_m_p);
			} else {
				var secend_m_p = f_incme/100*m_leavy;
				$('#r_medicare_levy_show').val(secend_m_p);
			}
		}else {
			$('#r_medicare_levy_show').val(0);
		}	
		var sub_total 	  		= $('#r_sub_total').val();
		var medicare_levy_show 	=  $('#r_medicare_levy_show').val();
		var sub_total_medicare		  = +sub_total + +medicare_levy_show;
		$('#r_total').val(sub_total_medicare);
	});
</script>

<script type="text/javascript">
$('.final_calclute').on('click', function(){
	var s_incme = $('#s_incme').val();
	var f_incme = $('#f_incme ').val();
	var total   = $('#total').val();
	var r_total = $('#r_total').val();
		if(s_incme != ''){
			if(f_incme != ''){			
				var final_tax = total - +r_total;
				$('#full_final_tax_payable').val(final_tax);
			} else {
				$('#full_final_tax_payable').val(r_total);
			}			
		} else {
		
			$('#full_final_tax_payable').val(total);
		
		}
});
</script>


<script>
	$("#to_date1").on('blur', function(){
		var startdate = $("#date1").val();
		var end_date = $("#to_date1").val();
		
		if(end_date != '' && startdate != ''){
		
		var fulldate = startdate.split('/');
		var s_day = fulldate[0];
		var s_month = fulldate[1];
		var s_year  = fulldate[2];
		var fullenddate = end_date.split('/');
		var e_day = fullenddate[0];
		var e_month = fullenddate[1];
		var e_year = fullenddate[2];
		var to_date = e_month+'/'+e_day+'/'+e_year;
		var formdate = s_month+'/'+s_day+'/'+s_year;
		var final_day = showDays(to_date, formdate);
		$("#days1").val(final_day+1);
	
		toal_daycout();
		} else {
			alert("Please Enter Date");
			
		}		
	});
	
	$("#to_date2").on('blur', function(){
		var startdate = $("#date2").val();
		var end_date = $("#to_date2").val();
		
		if(end_date != '' && startdate != ''){
		
		var fulldate = startdate.split('/');
		var s_day = fulldate[0];
		var s_month = fulldate[1];
		var s_year  = fulldate[2];
		
		var fullenddate = end_date.split('/');
		var e_day = fullenddate[0];
		var e_month = fullenddate[1];
		var e_year = fullenddate[2];
		var to_date = e_month+'/'+e_day+'/'+e_year;
		var formdate = s_month+'/'+s_day+'/'+s_year;
		var final_day = showDays(to_date, formdate);
		$("#days2").val(final_day+1);
		toal_daycout();
		} else {
			alert("Please Enter Date");
		}		
	});
	
	$("#to_date3").on('blur', function(){
		
		var startdate = $("#date3").val();
		var end_date = $("#to_date3").val();
		
		if(end_date != '' && startdate != ''){
		
		var fulldate  	= startdate.split('/');
		var s_day 	  	= fulldate[0];
		var s_month   	= fulldate[1];
		var s_year    	= fulldate[2];
		var fullenddate = end_date.split('/');
		var e_day 		= fullenddate[0];
		var e_month 	= fullenddate[1];
		var e_year 		= fullenddate[2];
		var to_date 	= e_month+'/'+e_day+'/'+e_year;
		var formdate 	= s_month+'/'+s_day+'/'+s_year;
		var final_day 	= showDays(to_date, formdate);
		$("#days3").val(final_day+1);
		toal_daycout();
		
		} else {
			alert("Please Enter Date");
		}		
	});
	
	function showDays(firstDate, secondDate){
                
                  var startDay = new Date(firstDate);
                  var endDay = new Date(secondDate);
                  var millisecondsPerDay = 1000 * 60 * 60 * 24;

                  var millisBetween = startDay.getTime() - endDay.getTime();
                  var days = millisBetween / millisecondsPerDay;
					
				  return days;
				
				// alert( Math.floor(days));

              };
			  
			  
	function toal_daycout()
	{
		
		var total = 0;
		var days_1 = $('#days1').val();
		
		var days_2 = $('#days2').val();
		
		var days_3 = $('#days3').val();
		if(days_1 != ''){
		var total  = +days_1;
		}
		if(days_2 != ''){
			var total  = +days_2 + +days_1;
		}if(days_3 != ''){
			var total  = +days_3 + +days_1 + +days_2;
		}
		
		$("#total_days").val(total); 
		
	}
</script>





<!-- inline scripts related to this page -->

@endsection