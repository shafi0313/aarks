@extends('admin.layout.master')
@section('title','Periodic GST/BAS(Cash)')
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
              <li class="active">Periodic GST/BAS(Cash)</li>
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
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h3 class="panel-title"><i class="glyphicon glyphicon-align-justify"></i> Clients Detail <strong
                                        class="pull-right"><a class="back" href=""><i
                                                class="glyphicon glyphicon-chevron-left"></i> Back</a></strong></h3>
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
                                       <td>{{$client->company}}</td>
                                        <td>{{$client->first_name}}</td>
                                        <td>{{$client->last_name}}</td>
                                        <td>{{$client->email}}</td>
                                        <td>{{$client->abn_number}}</td>
                                        <td>{{$client->tax_file_number}}</td>
                                        <td>{{$client->birthday}}</td>
                                        <td>{{$client->phone}}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="panel panel-success">
                            <div class="panel-heading">
                                <h3 class="panel-title"><i class="glyphicon glyphicon-align-justify"></i> Business Activity Report</h3>
                            </div>
                            <div class="panel-body" style="padding:50px;">
                                <select class="form-control" id="proid" name="proid" tabindex="7" onchange="location = this.value;">
                                    <option value="">Select Business Activity Report</option>
                                @foreach ($client->professions as $profession)
                                <option value="{{ route('cash_periodic.date',[$client->id,$profession->id]) }}"> {{$profession->name}} </option>
                                @endforeach
                                </select>
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
