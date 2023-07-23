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
              <li class="active">>Retio Analysis</li>
              <li class="active">GST/BAS</li>
            </ul><!-- /.breadcrumb -->

            {{-- <div class="nav-search" id="nav-search">
              <form class="form-search">
                <span class="input-icon">
                  <input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" />
                  <i class="ace-icon fa fa-search nav-search-icon"></i>
                </span>
              </form>
            </div><!-- /.nav-search --> --}}
          </div>

          <div class="page-content">            
            

            <div class="row">
              <div class="col-xs-12">               

                <!-- PAGE CONTENT BEGINS -->
                
          <div class="row">
            <div class="col-xs-12" >
              <div class="panel panel-primary">
                <div class="panel-heading">
                  <h3 class="panel-title"><i class="glyphicon glyphicon-align-justify"></i> Clients Detail <strong class="pull-right"><a class="back" href=""><i class="glyphicon glyphicon-chevron-left"></i> Back</a></strong></h3>
                </div>
                <div class="panel-body" style="padding:0px;">
                  <table class="table table-bordered table-striped">
                    <tr>
                      <th>Company Name</th>
                      <th>First Name</th>
                      <th>Last Name</th>
                      <th>Email</th>
                      <th>ABN No</th>
                      <th>TFN</th>
                      <th>Date Of Birth</th>
                      <th>Phone Number</th>
                    </tr>
                    <tr>
                      <td>B AL Haify & A ALZOUBAYDY </td>
                      <td></td>
                      <td></td>
                      <td>haify@hotmail.com</td>
                      <td>47733241709</td>
                      <td></td>
                      <td></td>
                      <td>0402930398</td>
                    </tr>
                  </table>
                </div>
              </div>
              <div class="panel panel-success">
                <div class="panel-heading">
                  <h3 class="panel-title"><i class="glyphicon glyphicon-align-justify"></i> GST BAS</h3>
                </div>
                <div class="panel-body" style="padding:0px;">			  
				 <form action="" method="post">	
			   	
                      <input type="hidden" name="client_id" value="512" />
                      
					<div class="col-md-4" >				
					</div>	
					<div class="col-md-2" style="padding-top:20px;">
						<div class="form-group">
							<label>Form Date</label>
							<input type="text" id="form_date" name="form_date" class="form-control date-picker" data-date-format="dd/mm/yyyy" Placeholder="DD/MM/YYYY"/>
						</div>
					</div>				
					<div class="col-md-2" style="padding-top:20px;">
						<div class="form-group">
							<label>To Date</label>
							<input type="text" id="to_date" name="to_date" class="form-control date-picker" data-date-format="dd/mm/yyyy"  Placeholder="DD/MM/YYYY"/>
						</div>
					</div>				
					<div class="col-md-2" style="padding-top:40px;">
					<div class="form-group">
          
						{{-- <button type="submit" class="btn btn-primary">Show Report</button> --}}
					</div>
					</div>		
					<div class="col-md-4">				
					</div>
							
				    
	   		 </form>
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
