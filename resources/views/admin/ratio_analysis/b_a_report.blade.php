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
              <li>Reports</li>
              <li class="active">Activity Balance Sheet Report</li>
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
                    <h3>Balance Sheet Report</h3>
                    <div class="panel panel-success">
                      <div class="panel-heading">
                        <h3 class="panel-title">Report for the Activity : <strong>Kebab Shop</strong></h3>
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
                            <td>Hama</td>
                            <td>Barzanji</td>
                            <td>abcd@gmail.com.au</td>
                            <td>12312312312</td>
                            <td></td>
                            <td>01/01/1990</td>
                          </tr>

                        </table>
                      </div>
                    </div>


                    <div class="col-md-3"></div>

                    <div class="col-md-5">
                      <div class="row">
                        <form action="" method="">

                          <div class="panel-body" style="padding:0px;">
                            <div class="col-md-3" style="padding-top:7px;" align="right"><strong>As at</strong></div>
                            <div class="col-md-5">
                              <div class="row">

                                <input type="" class="form-control date-picker" name="form_date" id="form_date" placeholder="DD/MM/YYYY"
                                  data-date-format="dd/mm/yyyy" />
                                <input type="hidden" name="professionid" id="professionid" value="14" />
                                <input type="hidden" name="client_id" id="client_id" value="628" />
                              </div>
                            </div>

                            <div class="col-md-4">
                              <div class="row">
                                <a style="background:#FF9900; color:white; border:none; padding:7px;" href="{{ route('balance_sheet_select_date') }}">Show Report</a>
                                {{-- <button class="" style="background:#FF9900; color:white; border:none; padding:7px;">Show Report</button> --}}
                              </div>
                            </div>
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
