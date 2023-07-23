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
              <div class="col-xs-12" >
                <div class="panel panel-primary">
                  <div class="panel-heading">
                    <h3 class="panel-title"><i class="glyphicon glyphicon-align-justify"></i> Clients Detail  :-  <strong class="pull-right"> <a class="back" href="https://www.aarks.com.au/Gst_balance"><i class="glyphicon glyphicon-chevron-left"></i> Back</a></strong></h3>
                  </div>
                  <div class="panel-body" style="padding:0px;">
          
                  </div>
				  
				  <div align="center">
				 <h2>CASH BASIS GST REPORT</h2>
				<h4><u> From Date : 22/02/2020 To : 28/03/2020</u></h4>
				  </div>
              	  <table  width="100%" >
                  <tr>
          
					
                    <td valign="top" width="60%" align="center">
					
					<table width="100%" class="customfont"> 
					<div  style="text-align:center; font-size:18px; color:#0099FF;">ACTIVITY SUMMERY</div>
                      <tr>
                        <td width="50%">
						<table width="100%" class="table table-bordered table-striped table-hover" >
							
							  <tr style="background-color:#6699FF !important; color:#FFFFFF;">
								<td width="40%"><b>Code</b></td>
								<td width="60%"><b>Amount</b></td>								
							  </tr>							
							 <tbody>
							 <!----------g1 = ac 1 gross total----->
							   <tr>
								<td style="color:#6633FF;">G1</td>
								<td>0.00</td>								
							  </tr>
							   <!----------g3 = ac 1 er free gross total----->
							  <tr>
								<td style="color:#6633FF;">G3</td>
								<td>0</td>								
							  </tr>
							   <!----------g10 = ac 6 er gst(cap) gros total(credit amoount hole minus hobe)----->
							  <tr>
								<td style="color:#6633FF;">G10</td>
								<td>
								0.00								</td>								
							  </tr>
							    <!----------g11 = ac 2 - 4 gross total----->
							  <tr>
								<td style="color:#6633FF;">G11</td>
								<td>
								0.00								</td>								
							  </tr>			  
							   
							  <tr>
								<td align="center">-</td>
								<td align="center">-</td>								
							  </tr>		
							  
							  <!-----w1 = w1+w2 er gross total------->				  
							  <tr>
								<td style="color:#6633FF;">W1</td>
								<td>0.00</td>								
							  </tr>
							  
							  <!-----Pore------>		
							  <tr>
								<td style="color:#6633FF;">7C</td>
								<td>0</td>								
							  </tr>
							   <tr>
								<td align="center">-</td>
								<td align="center">-</td>								
							  </tr>	
							  
							  <!-----percentage = add data theke asbe------>
							  <tr>
								<td style="color:#6633FF;" >PERCENTAGE(%)</td>
								<td ></td>								
							  </tr>							  	
							  </tbody>
                        </table>
						</td>
                        <td width="50%">
						<table width="100%" class="table table-bordered table-striped table-hover">
							
							  <tr style="background-color:#6699FF !important; color:#FFFFFF;">
								<td width="40%"><b>Code</b></td>
								<td width="60%"><b>Amount</b></td>								
							  </tr>
							
							 <tbody>
							  <!-----1a = ac 1 er gst total------>
							   <tr>
								<td style="color:#6633FF;">1A</td>
								<td>
								0.00								</td>								
							  </tr>							  
							  <tr>
								<td align="center">-</td>
								<td align="center">-</td>								
							  </tr>
							    <tr>
								<td align="center">-</td>
								<td align="center">-</td>								
							  </tr>							  
							  <!----1b  = ac 2 - 4(inp total) n 6(cap total) (5 baad)------>
							 <tr>
								<td style="color:#6633FF;">1B</td>
								<td>0.00</td>								
							  </tr>
							  
						 	  <!----- 9  = 1A-1B------>
						 	 <tr>
								<td style="color:#6633FF;">9</td>
								<td>0.00</td>								
							 </tr>
							 
							   <!----- w2 = w2 er gross total ------>
							  <tr>
								<td style="color:#6633FF;">W2</td>
								<td>
								0.00								</td>								
							  </tr>
							    <!----- 7d = pore(between the perod add data  fuel Ltr X rate ) ------>
							  <tr>
								<td style="color:#6633FF;">7D</td>
								<td>
								0.00								</td>								
							  </tr>	
							    <!----- t1 = g1 - 1a ------>
							  <tr>
								<td style="color:#6633FF;">T1</td>
								<td>0.00</td>													
							  </tr>		
							  <tr>
								<td style="color:#6633FF;">PAYG</td>
								<td>0.00</td>								
							  </tr>
							  							  
							  </tbody>
                        </table>
						</td>
                      </tr>
					  
					  
					  	
						
					  <tr class="table-customm">
					  	<td align="right">
						 <b>
						<span style="color:red;">Total Receivable : </span>						 </b>
						</td>
						<td align="left">
						<b>
						
						<span style="color:red;">0.00</span>					
						 </b> 
						 </td>
					  </tr>
                    </table>			
					</td>
                  </tr>
                </table>
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