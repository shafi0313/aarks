@extends('admin.layout.master')
@section('title','Accumulated P/L GST Exclusive')
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
              <li class="active">Accumulated P/L GST Exclusive</li>
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

                    <div class="col-md-3">
                    </div>
                    <div class="col-md-6" style="padding:20px; border:1px solid #999999;">
                      <form action="" method="">
                        <div class="form-group">
                          <label style="color:green;"> Activity Profit & Loss Exclude GST Report</label>
                          <select class="form-control" id="proid" name="proid" tabindex="7" onchange="location = this.value;">
                            <option value="">Select Business Activity Report</option>
                            @foreach ($client->professions as $profession)
                            <option value="{{ route('accum_excl_period',[$client->id,$profession->id]) }}"> {{$profession->name}} </option>
                            @endforeach
                        </select>
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
