@extends('admin.layout.master')
@section('title','Period List')
@section('content')

<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="{{ route('admin.dashboard') }}">Home</a>
                </li>
                <li>Report</li>
                <li class="active">Comperative Balance Sheet</li>
              <li class="active">Select Profession</li>
              <li class="active">Select Date</li>
              <li class="active">Final Report</li>
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
                    <div class="row">
						<div class="col-xs-12">
							<h3>For the year ended</h3>
							<div class="panel panel-success">
								<div class="panel-heading">
									<h3 class="panel-title">Report for the Activity : <strong>Ride Sharing(UBER)</strong></h3>
								</div>
								<div class="panel-body" style="padding:0px;">
									<table class="table table-bordered">
										<tr>
											<th>Company Name</th>
											<th>First Name</th>
											<th>Last Name</th>
											<th>Email</th>
											<th>ABN No</th>
											<th>TFN</th>
											<th>Date Of Birth</th>
										</tr>
										<tr>
											<td></td>
											<td>Rohan</td>
											<td>Arora</td>
											<td>ROHANARORA82@YAHOO.COM</td>
											<td>75718257251</td>
											<td></td>
											<td>06/06/1982</td>
										</tr>
									</table>
								</div>
							</div>
							<form id="idForm" action="#" method="post">
								<div class="col-md-3"></div>
								<div class="col-md-5">
									<div class="row">
										<div class="panel-body" style="padding:0px;">
											<div class="col-md-3" style="padding-top:7px;" align="right"><strong>As at : </strong></div>
											<div class="col-md-5">
												<div class="row">
													<input type="" class="form-control date-picker" name="form_date" id="form_date"
														placeholder="DD/MM/YYYY" data-date-format="dd/mm/yyyy" />
													<input type="hidden" name="professionid" id="professionid" value="21" />
													<input type="hidden" name="client_id" id="client_id" value="629" />
												</div>
											</div>
											<div class="col-md-4">
												<div class="row">
													<button class="" style="background:#FF9900; color:white; border:none; padding:7px;">Show
														Report</button>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-12">
									<div class="col-md-2"></div>
									<div class="col-md-4" align="left">
										<div class="checkbox">
											<label>
												<input type="checkbox" name="cover_page" value="1">
												Cover Page </label>
										</div>
										<div class="checkbox">
											<label>
												<input type="checkbox" name="balance_sheet" value="1">
												Balance Sheet </label>
										</div>
										<div class="checkbox">
											<label>
												<input type="checkbox" name="incomestatment_note" value="1">
												Income statement </label>
										</div>
										<div class="checkbox">
											<label>
												<input type="checkbox" name="details_balance_sheet" value="1">
												Detailed Balance Sheet </label>
										</div>
					
					
										<div class="checkbox">
											<label>
												<input type="checkbox" name="trading_profit_loss" value="1">
												Detailed Trading, Profit & Loss </label>
										</div>
					
										<div class="checkbox">
											<label>
												<input type="checkbox" name="trial_balance" value="1">
												Trial Balance </label>
										</div>
					
					
										<div class="checkbox">
											<label>
												<input type="checkbox" name="cash_flow_statement" value="1">
												Statement of Cash Flows</label>
										</div>
					
										<div class="checkbox">
											<label>
												<input type="checkbox" name="statement_of_receipts_and_payments" value="1">
												Statement of receipts and payments </label>
										</div>
					
										<div class="checkbox">
											<label>
												<input type="checkbox" name="statement_of_chanes_in_equity" value="1">
												Statement of change in equity </label>
										</div>
					
										<div class="checkbox">
											<label>
												<input type="checkbox" name="rental_income_statement" value="1">
												Rental income statement </label>
										</div>
										<div class="checkbox">
											<label>
												<input type="checkbox" name="retal_income_consolidated" value="1">
												Rental income (consolidated) </label>
										</div>
					
										<div class="checkbox">
											<label>
												<input type="checkbox" name="depreciation_report" value="1">
												Depreciation Report </label>
										</div>
					
										<div class="checkbox">
											<label>
												<input type="checkbox" name="livestock_trading_statement" value="1">
												Livestock trading statement </label>
										</div>
					
					
					
									</div>
									<div class="col-md-4" align="left">
										<div class="checkbox">
											<label>
												<input type="checkbox" name="notes_to_financial_statemts" value="1">
												Notes to financial statements </label>
										</div>
					
										<div class="checkbox">
											<label>
												<input type="checkbox" name="business_analysis_five_yeartrading" value="1">
												Business analysis - Five year trading results </label>
										</div>
										<div class="checkbox">
											<label>
												<input type="checkbox" name="business_analysis_monthly" value="1">
												Business analysis - Monthly P & L </label>
										</div>
										<div class="checkbox">
											<label>
												<input type="checkbox" name="business_analysis_five_yearpl" value="1">
												Business analysis - Five Years P & L </label>
										</div>
										<div class="checkbox">
											<label>
												<input type="checkbox" name="business_analysis_pl_with_sales" value="1">
												Business analysis - P & L with % of Sales </label>
										</div>
										<div class="checkbox">
											<label>
												<input type="checkbox" name="business_analysis_monthly_sales" value="1">
												Business analysis - Monthly sales </label>
										</div>
										<div class="checkbox">
											<label>
												<input type="checkbox" name="business_analysis_expenses_bank_account" value="1">
												Business analysis - Expenses & Bank account </label>
										</div>
					
										<div class="checkbox">
											<label>
												<input type="checkbox" name="directors_report" value="1">
												Directors' report </label>
										</div>
										<div class="checkbox">
											<label>
												<input type="checkbox" name="directors_declaration" value="1">
												Directors' declaration </label>
										</div>
										<div class="checkbox">
											<label>
												<input type="checkbox" name="audit_report" value="1">
												Audit report </label>
										</div>
										<div class="checkbox">
											<label>
												<input type="checkbox" name="compilation_report" value="1">
												Compilation report </label>
										</div>
					
										<div class="checkbox">
											<label>
												<input type="checkbox" name="contents" value="1">
												Comments </label>
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>
					</div>
					</div>
					</div>
					</div>
					</div>
					<style type="text/css">
						.loading {
							position: static;
							top: 50%;
							left: 50%;
							transform: translate(-50%, -50%);
							-ms-transform: translate(-50%, -50%);
						}
					</style>
					<script type="text/javascript">
						$("#idForm").submit(function(e) {
					     e.preventDefault(); 
					   var form_date                            = $('#form_date').val()
					   var client_id                            = $('#client_id').val()
					   var professionid                         = $('#professionid').val()
					   
					   
					   if($('input[name="incomestatment_note"]').prop('checked') == true){
					         var incomestatment_note            = $('input[name="incomestatment_note"]').val();       
					    }   
					   if($('input[name="balance_sheet"]').prop('checked') == true){
					          var balance_sheet                        = $('input[name="balance_sheet"]').val();   
					    }  
					   if($('input[name="details_balance_sheet"]').prop('checked') == true){
					           var details_balance_sheet                = $('input[name="details_balance_sheet"]').val();
					    }
					 
					  if($('input[name="trading_profit_loss"]').prop('checked') == true){
					          var trading_profit_loss                  = $('input[name="trading_profit_loss"]').val();
					    }
					
					  if($('input[name="cash_flow_statement"]').prop('checked') == true){
					          var cash_flow_statement                  = $('input[name="cash_flow_statement"]').val();
					    } 
					    
					  if($('input[name="trial_balance"]').prop('checked') == true){
					        var trial_balance                        = $('input[name="trial_balance"]').val();
					    } 
					
					  if($('input[name="statement_of_receipts_and_payments"]').prop('checked') == true){
					          var statement_of_receipts_and_payments   = $('input[name="statement_of_receipts_and_payments"]').val();
					    }
					
					  if($('input[name="statement_of_chanes_in_equity"]').prop('checked') == true){
					          var statement_of_chanes_in_equity        = $('input[name="statement_of_chanes_in_equity"]').val();
					    }
					
					  if($('input[name="rental_income_statement"]').prop('checked') == true){
					          var rental_income_statement              = $('input[name="rental_income_statement"]').val();
					    }
					  
					  if($('input[name="business_analysis_monthly_sales"]').prop('checked') == true){
					         var business_analysis_monthly_sales      = $('input[name="business_analysis_monthly_sales"]').val();
					    }
					
					  if($('input[name="retal_income_consolidated"]').prop('checked') == true){
					         var retal_income_consolidated            = $('input[name="retal_income_consolidated"]').val();
					    } 
					
					  if($('input[name="depreciation_report"]').prop('checked') == true){
					        var depreciation_report                  = $('input[name="depreciation_report"]').val();
					    }
					  
					   if($('input[name="livestock_trading_statement"]').prop('checked') == true){
					        var livestock_trading_statement          = $('input[name="livestock_trading_statement"]').val();
					    }
					
					  if($('input[name="notes_to_financial_statemts"]').prop('checked') == true){
					        var notes_to_financial_statemts          = $('input[name="notes_to_financial_statemts"]').val();
					    }
					
					  if($('input[name="business_analysis_five_yeartrading"]').prop('checked') == true){
					         var business_analysis_five_yeartrading   = $('input[name="business_analysis_five_yeartrading"]').val();
					    }
					
					  if($('input[name="business_analysis_monthly"]').prop('checked') == true){
					         var business_analysis_monthly            = $('input[name="business_analysis_monthly"]').val();
					    }
					
					   if($('input[name="business_analysis_five_yearpl"]').prop('checked') == true){
					         var business_analysis_five_yearpl        = $('input[name="business_analysis_five_yearpl"]').val();
					    }
					
					   if($('input[name="business_analysis_pl_with_sales"]').prop('checked') == true){
					         var business_analysis_pl_with_sales      = $('input[name="business_analysis_pl_with_sales"]').val();
					    }
					
					   if($('input[name="business_analysis_expenses_bank_account"]').prop('checked') == true){
					         var business_analysis_expenses_bank_account      = $('input[name="business_analysis_expenses_bank_account"]').val();
					    }
					
					    if($('input[name="directors_report"]').prop('checked') == true){
					            var directors_report                     = $('input[name="directors_report"]').val();
					    }
					   
					   if($('input[name="directors_declaration"]').prop('checked') == true){
					           var directors_declaration                 = $('input[name="directors_declaration"]').val();
					    }
					
					  if($('input[name="audit_report"]').prop('checked') == true){
					           var audit_report                          = $('input[name="audit_report"]').val();
					    }
					   
					   if($('input[name="compilation_report"]').prop('checked') == true){
					            var compilation_report                   = $('input[name="compilation_report"]').val();
					    }
					
					   if($('input[name="contents"]').prop('checked') == true){
					            var contents                             = $('input[name="contents"]').val();
					    }
					   
					   var cover_page                           = $('input[name="cover_page"]').val();
					
					   
					    $("#loaderr").css('display', 'block'); 
					
					    var url = "https://www.aarks.com.au/single_year_financial_report/finalreport";
					    $.ajax({
					           type: "POST",
					           url: url,           
					           data:{client_id:client_id, professionid:professionid, form_date:form_date, form_date:form_date, cover_page:cover_page, incomestatment_note:incomestatment_note, balance_sheet:balance_sheet, details_balance_sheet:details_balance_sheet, trading_profit_loss:trading_profit_loss, trial_balance:trial_balance, cash_flow_statement:cash_flow_statement, statement_of_receipts_and_payments:statement_of_receipts_and_payments, statement_of_chanes_in_equity:statement_of_chanes_in_equity, rental_income_statement:rental_income_statement, retal_income_consolidated:retal_income_consolidated, depreciation_report:depreciation_report, livestock_trading_statement:livestock_trading_statement, notes_to_financial_statemts:notes_to_financial_statemts, business_analysis_five_yeartrading:business_analysis_five_yeartrading, business_analysis_monthly:business_analysis_monthly, business_analysis_five_yearpl:business_analysis_five_yearpl, business_analysis_pl_with_sales:business_analysis_pl_with_sales, business_analysis_expenses_bank_account:business_analysis_expenses_bank_account, directors_report:directors_report, directors_declaration:directors_declaration, audit_report:audit_report, compilation_report:compilation_report, contents:contents, business_analysis_monthly_sales:business_analysis_monthly_sales},            
					           success: function(data)
					           {
					             $("#replace_report").html(data); 
					             $("#loader").css('display', 'none');              
					           }
					         });
					
					  });
					
					
					
					$('#abnnumber').on('keyup', function(){
					    var abnnumber = $(this).val();
					    var urlmgs = "https://www.aarks.com.au/AddClient/check";
					    $.ajax({
					      url:urlmgs,
					      type:"POST",
					      data:{abnnumber:abnnumber},
					      success:function(data){
					        if(data == 1){
					          $('.abn_mgs').text('Client Allready Exist!');
					          $('.submit').attr('disabled', 'disabled');
					        } else {
					        $('.abn_mgs').text('');
					        $('.submit').removeAttr('disabled', 'disabled');
					        }
					      }
					    
					    });
					  });
					
					
					
					
					
					</script>
                    

                    <!-- PAGE CONTENT ENDS -->
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.page-content -->
    </div>
</div><!-- /.main-content -->

<!-- Script -->
<script>
    jQuery(function($) {
            var myTable =
            $('#period-table')
                .DataTable({
                    bAutoWidth: false,
                    "aoColumns": [
                        {"bSortable": false},
                        null, null, null, null, null,
                        {"bSortable": false}
                    ],
                    "aaSorting": [],
                    select: {
                        style: 'multi'
                    }
                });
             });
        $(".datepicker").datepicker({
            dateFormat: "dd/mm/yy",
            changeMonth: true,
            changeYear: true
        });

        $('#financial-year').keyup(function(){
            let year = $(this).val();
            if (year.length == 4){
                $('#msg').show().html('Your selected financial year july '+(year-1) +' to June '+year);
            }else{
                $('#msg').hide();
                //$('#msg').show().html('Must be 4 Digit');
            }

        });
</script>
@stop