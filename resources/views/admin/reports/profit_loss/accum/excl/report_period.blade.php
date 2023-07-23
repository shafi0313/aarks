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

                <li>Report</li>
                <li>Accumulated P/L GST Exclusive</li>
                <li class="active"></li>
            </ul><!-- /.breadcrumb -->
        </div>

        <div class="page-content">
            

            <div class="row">
                <div class="col-xs-12">
                    <!-- PAGE CONTENT BEGINS -->

					<div class="row">
						<div class="col-xs-12">
							<h3>Accumulated P/L GST Exclusive</h3>
							<div class="panel panel-success">
								<div class="panel-heading">
									<h3 class="panel-title">Report for the Activity : <strong>{{$client->company }}</strong></h3>
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
                                            <td>{{ $client->company }}</td>
                                            <td>{{ $client->first_name }}</td>
                                            <td>{{ $client->last_name }}</td>
                                            <td>{{ $client->email }}</td>
                                            <td>{{ $client->abn_number }}</td>
                                            <td>{{ $client->tax_file_number }}</td>
                                            <td>{{ $client->birthday }}</td>
                                            <td>{{ $client->phone }}</td>
                                        </tr>

                                    </table>
								</div>
							</div>

							<div class="panel panel-primary">
								<div class="panel-heading">
									<h3 class="panel-title">Report for the Period</h3>
								</div>
								<div class="panel-body" style="padding:0px;">
									<div class="row">
										<form action="{{ route('console_accum.excl_report') }}" method="get" autocomplete="off">
                                            <input type="hidden" name="client_id" value="{{$client->id}} " />

                                            <div class="col-md-4">
                                            </div>
                                            <div class="col-md-2" style="padding-top:20px;">
                                                <div class="form-group">
                                                    <label>Form Date</label>
                                                    <input type="" id="from_date" name="from_date" class="form-control date-picker datepicker" id="datepicker"
                                                        data-date-format="dd/mm/yyyy" Placeholder="DD/MM/YYYY" />
                                                </div>
                                            </div>
                                            <div class="col-md-2" style="padding-top:20px;">
                                                <div class="form-group">
                                                    <label>To Date</label>
                                                    <input type="" id="to_date" name="to_date" class="form-control date-picker datepicker" id="datepicker"
                                                        data-date-format="dd/mm/yyyy" Placeholder="DD/MM/YYYY" />
                                                </div>
                                            </div>
                                            <div class="col-md-2" style="padding-top:40px;">
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-primary">Show Report</button>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
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
<script>
    $(".datepicker").datepicker({
    dateFormat: "dd/mm/yy",
    changeMonth: true,
    changeYear: true
    });
</script>

@endsection
