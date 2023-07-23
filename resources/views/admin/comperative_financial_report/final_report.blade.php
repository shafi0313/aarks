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
                    <style>						
						.doubleUnderline {
							text-decoration:underline;
							border-bottom: 1px solid #000;
						}
					</style>
						<form action="" target="_blank" method="post">
						<input type="hidden" name="professionid" value="21" />
						<input type="hidden" name="client_id" value="629" />
						<input type="hidden" name="form_date" value="27/03/2020" />
						<input type="hidden" name="shortingdate" value="2020-03-27" />
						<div class="col-md-12" style="padding-right:20px; padding-left:20px;">
							<div class="col-xs-12">								
								<div class="col-md-3"></div>
								<div class="col-md-6" align="center">
									<div style="font-size:24px; font-weight:800;">Rohan Arora <br />
											
									</div>										
										Unit 14/22-24, Bailey Street, Westmead, NSW, 2145<br>
											<strong style="font-size:14px;">ABN 75718257251</strong>
										<br>
										<strong style="font-size:16px;"><u>Detailed Balance Sheet as at: 27/03/2020</u></strong>
										<br />								
								</div>							
								
																
								<div class="col-md-12" style="padding-top:10px; ">
								
									<table class="table table-bordered">
										<thead>
											<tr>
												<th style="width:40%;">Account Name</th>
												<th>Note</th>
												<th>2020</th>
												<th>2019</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td colspan="2"><strong>Current Assets</strong></td>
												
												<td><strong>102.78</strong></td>
												<td><strong>0.00</strong></td>
											</tr>
											<tr>
												<td> &nbsp; &nbsp; <strong>Liquid Assets</strong></td>
												<td></td>
												<td><strong>&nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp;102.78</strong></td>
												<td><strong>&nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp;0.00</strong></td>
											</tr>										
												 
													<tr>
														<td> &nbsp; &nbsp;  &nbsp; &nbsp; Suspense clearing account</td>
														<td></td>
														<td>&nbsp; &nbsp;  &nbsp; &nbsp; &nbsp; &nbsp;  &nbsp; &nbsp;&nbsp; &nbsp;  &nbsp; &nbsp;102.78 </td>
														<td>&nbsp; &nbsp;  &nbsp; &nbsp; &nbsp; &nbsp;  &nbsp; &nbsp;&nbsp; &nbsp;  &nbsp; &nbsp;0.00</td>
													</tr>																				
											<tr>
												<td colspan="2"><strong style>TOTAL ASSETS -</strong></td>
												<td><strong><u>102.78</u></strong></td>
												<td><strong><u>0.00</u></strong></td>
											</tr>
											<tr>
												<td colspan="2"><strong>Current Libilty</strong></td>
												
												<td><strong>9.35</strong></td>
												<td><strong>0.00</strong></td>
											</tr>
											<tr>
												<td> &nbsp; &nbsp; <strong>GST and Income Tax Payable control Account</strong></td>
												<td></td>
												<td><strong>&nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp;9.35</strong></td>
												<td><strong>&nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp;0.00</strong></td>
											</tr>											
												 
													<tr>
														<td> &nbsp; &nbsp;  &nbsp; &nbsp; GST Payable Controll Account</td>
														<td></td>
														<td>&nbsp; &nbsp;  &nbsp; &nbsp; &nbsp; &nbsp;  &nbsp; &nbsp;&nbsp; &nbsp;  &nbsp; &nbsp;39.45 </td>
														<td>&nbsp; &nbsp;  &nbsp; &nbsp; &nbsp; &nbsp;  &nbsp; &nbsp;&nbsp; &nbsp;  &nbsp; &nbsp;0.00</td>
													</tr>
												 
													<tr>
														<td> &nbsp; &nbsp;  &nbsp; &nbsp; GST Clearing Account</td>
														<td></td>
														<td>&nbsp; &nbsp;  &nbsp; &nbsp; &nbsp; &nbsp;  &nbsp; &nbsp;&nbsp; &nbsp;  &nbsp; &nbsp;-30.10 </td>
														<td>&nbsp; &nbsp;  &nbsp; &nbsp; &nbsp; &nbsp;  &nbsp; &nbsp;&nbsp; &nbsp;  &nbsp; &nbsp;0.00</td>
													</tr>
											<tr>
												<td colspan="2"><strong style>TOTAL EQUITY -</strong></td>
												<td><strong class="doubleUnderline">0.00</strong></td>
												<td><strong class="doubleUnderline">0.00</strong></td>
											</tr>											
										</tbody>									
									</table>																
								</div>								
							</div>
						</div>
						</form>
					</div>
				</div>
			</div>
                    

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