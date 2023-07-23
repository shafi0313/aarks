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
              <li>Tools</li>
              <li class="active">Verify & Fixed Transactions</li>
              <li class="active">Select Activity</li>
              <li class="active">Verify & Fixed Transactions Report</li>
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
                  <div class="col-xs-12">
                    <h3>Verify & Fixed Transactions Report</h3>
                    <div class="panel panel-success">
                      <div class="panel-heading">
                        <h3 class="panel-title">Report for the Activity : <strong>Taxi Owner & Driver</strong></h3>
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
                            <td>Mohamed</td>
                            <td>Shieta</td>
                            <td>saumya.ranasinghe@gmail.com</td>
                            <td>88363397551</td>
                            <td></td>
                            <td>08/05/1959</td>
                          </tr>                
                        </table>
                      </div>
                    </div>
                
                    <div class="panel panel-primary">
                      <form action="" method="post">
                        <div class="panel-body" style="padding:15px 0;">
                          <div class="col-md-10">
                            <div class="row">
                              <div class="col-md-2" style="padding-top:7px; text-align: right;">Form Date :</div>
                              <div class="col-md-4">
                                <input type="" class="form-control date-picker" name="form_date" required id="form_date"
                                  data-date-format="dd/mm/yyyy" />
                                <input type="hidden" name="professionid" id="professionid" value="27" />
                                <input type="hidden" name="client_id" id="client_id" value="630" />
                              </div>
                              <div class="col-md-2" style="padding-top:7px; text-align: right;">To Date :</div>
                              <div class="col-md-4">
                                <input type="" class="form-control date-picker" required name="to_date" id="to_date" data-date-format="dd/mm/yyyy" />
                              </div>                
                            </div>                
                          </div>
                          <div class="col-md-2">                
                            <a href="{{ route('verify_accounts_final_report') }}" class="btn btn-primary btn-sm">Show Report</a>
                            {{-- <button type="submit" class="btn btn-primary btn-sm">Show Report</button> --}}
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
